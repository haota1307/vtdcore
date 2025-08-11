<?php

namespace App\Core;

use App\Core\Contracts\ModuleInterface;
use FilesystemIterator;
use RuntimeException;

/**
 * Responsible for scanning module directories and producing a manifest.
 */
class ModuleScanner
{
    public function __construct(private string $modulesPath, private string $manifestFile)
    {
        $this->modulesPath = rtrim($modulesPath, '/\\') . DIRECTORY_SEPARATOR;
    }

    public function clearCache(): void
    {
        @unlink($this->manifestFile);
    }

    private function loadManifest(): ?array
    {
        if (is_file($this->manifestFile)) {
            $data = include $this->manifestFile;
            if (is_array($data)) { return $data; }
        }
        return null;
    }

    /**
     * Scan for modules, using cache if signature unchanged.
     * @return array{modules: array<string,ModuleInterface>, manifest: array}
     */
    public function scan(): array
    {
        if (! is_dir($this->modulesPath)) {
            return ['modules'=>[], 'manifest'=>['modules'=>[], 'signature'=>null, 'generated_at'=>time()]];
        }
        $dirs = [];
        $it = new FilesystemIterator($this->modulesPath, FilesystemIterator::SKIP_DOTS);
        foreach ($it as $fileInfo) {
            if ($fileInfo->isDir()) { $dirs[] = $fileInfo->getFilename(); }
        }
        sort($dirs, SORT_STRING|SORT_FLAG_CASE);
        $records = [];
        foreach ($dirs as $dir) {
            $class = 'App\\Modules\\' . $dir . '\\Module';
            $moduleFile = $this->modulesPath . $dir . DIRECTORY_SEPARATOR . 'Module.php';
            $mtime = is_file($moduleFile) ? filemtime($moduleFile) : null;
            $records[] = ['dir'=>$dir,'class'=>$class,'file_mtime'=>$mtime];
        }
        $signature = md5(json_encode($records));
        $cached = $this->loadManifest();
        $instances = [];
        if ($cached && ($cached['signature'] ?? null) === $signature) {
            // Rebuild instances from cached meta
            foreach ($cached['modules'] as $meta) {
                $class = $meta['class'];
                if (class_exists($class)) {
                    $inst = new $class();
                    if ($inst instanceof ModuleInterface) {
                        $instances[strtolower($inst->getId())] = $inst;
                    }
                }
            }
            return ['modules'=>$instances,'manifest'=>$cached];
        }
        // fresh build
        $manifestModules = [];
        foreach ($records as $rec) {
            $class = $rec['class'];
            if (class_exists($class)) {
                $inst = new $class();
                if (! $inst instanceof ModuleInterface) { continue; }
                $idLower = strtolower($inst->getId());
                if (isset($instances[$idLower])) {
                    throw new RuntimeException('Duplicate module id (case-insensitive): ' . $inst->getId());
                }
                $instances[$idLower] = $inst;
                $manifestModules[] = [
                    'id' => $inst->getId(),
                    'class' => $class,
                    'version' => $inst->getVersion(),
                    'dependencies' => $inst->getDependencies(),
                    'file_mtime' => $rec['file_mtime'],
                ];
            }
        }
        $manifest = [
            'signature' => $signature,
            'generated_at' => time(),
            'modules' => $manifestModules,
        ];
        $this->saveManifest($manifest);
        return ['modules'=>$instances,'manifest'=>$manifest];
    }

    private function saveManifest(array $manifest): void
    {
        $dir = dirname($this->manifestFile);
        if (! is_dir($dir)) { mkdir($dir, 0775, true); }
        $content = '<?php return ' . var_export($manifest, true) . ';';
        $this->atomicWrite($this->manifestFile, $content);
    }

    private function atomicWrite(string $file, string $content): void
    {
        $tmp = $file . '.' . uniqid('tmp', true);
        $fp = fopen($tmp, 'wb');
        if ($fp) {
            fwrite($fp, $content);
            fflush($fp);
            fclose($fp);
            @rename($tmp, $file);
        } else {
            // fallback direct
            file_put_contents($file, $content, LOCK_EX);
        }
    }
}
