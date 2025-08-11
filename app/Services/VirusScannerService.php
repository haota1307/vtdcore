<?php
namespace App\Services;

class VirusScannerService
{
    private string $socket;
    private bool $failClosed;
    public function __construct(?string $socket = null, ?bool $failClosed = null)
    {
        $cfg = config(\Config\Media::class);
        $this->socket = $socket ?? ($cfg->virusSocket ?? 'tcp://127.0.0.1:3310');
        $this->failClosed = $failClosed ?? ($cfg->virusFailClosed ?? true);
    }

    /**
     * Scan path. Returns array [ 'clean'=>bool|null, 'reason'=>string ]
     * clean=true => clean; clean=false => infected; clean=null => unknown (scanner unavailable or error)
     */
    public function scanPathDetailed(string $path): array
    {
        if (!is_readable($path)) return ['clean'=>null,'reason'=>'unreadable'];
        try {
            $fp = @stream_socket_client($this->socket, $errno, $errstr, 1.5);
            if (!$fp) {
                return ['clean'=>null,'reason'=>'connect_error'];
            }
            fwrite($fp, "nINSTREAM\n");
            $fh = fopen($path,'rb');
            while (!feof($fh)) {
                $chunk = fread($fh, 8192);
                if ($chunk === '') break;
                $len = pack('N', strlen($chunk));
                fwrite($fp, $len . $chunk);
            }
            fclose($fh);
            fwrite($fp, pack('N',0));
            $response = stream_get_contents($fp) ?: '';
            fclose($fp);
            if (stripos($response,'FOUND') !== false) {
                return ['clean'=>false,'reason'=>'infected'];
            }
            if (stripos($response,'OK') !== false) {
                return ['clean'=>true,'reason'=>'ok'];
            }
            return ['clean'=>null,'reason'=>'unknown_response'];
        } catch (\Throwable $e) {
            return ['clean'=>null,'reason'=>'exception'];
        }
    }

    public function scanPath(string $path): bool
    {
        $res = $this->scanPathDetailed($path);
        if ($res['clean'] === null) {
            // unknown => allow only if failClosed false
            return $this->failClosed ? false : true;
        }
        return $res['clean'];
    }
}
