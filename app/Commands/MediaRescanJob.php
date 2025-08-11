<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\MediaModel;
use App\Services\VirusScannerService;

class MediaRescanJob extends BaseCommand
{
    protected $group       = 'Media';
    protected $name        = 'media:rescan-infected';
    protected $description = 'Scheduler: Re-scan all infected media files.';

    public function run(array $params)
    {
        CLI::write('Running scheduled re-scan for infected media...', 'yellow');
        $mediaModel = new MediaModel();
        $scanner = service('virusScanner');
        $infected = $mediaModel->where('scan_status', 'infected')->findAll(1000);
        foreach ($infected as $file) {
            $result = $scanner->scanFile(WRITEPATH.'uploads/'.$file['path']);
            $mediaModel->update($file['id'], ['scan_status' => $result ? 'clean' : 'infected']);
            CLI::write("File {$file['path']} re-scanned: ".($result?'clean':'infected'));
        }
        CLI::write('media:rescan-infected done.', 'green');
    }
}
