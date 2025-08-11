<?php
namespace App\Commands\Media;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\MediaModel;

class MediaRescanJob extends BaseCommand
{
    protected $group = 'Media';
    protected $name = 'media:rescan';
    protected $description = 'Rescan all media files for viruses and update scan status.';

    public function run(array $params)
    {
        $model = new MediaModel();
        $scanner = service('virusScanner');
        
        if (!$scanner) {
            CLI::error('Virus scanner service not available');
            return;
        }

        $limit = (int)($params[0] ?? 100);
        $offset = (int)($params[1] ?? 0);
        
        CLI::write("Starting media rescan job...");
        
        $query = $model->select('id, path, scan_status')
                      ->limit($limit)
                      ->offset($offset);
        
        $files = $query->findAll();
        $total = count($files);
        $scanned = 0;
        $infected = 0;
        $clean = 0;
        
        CLI::write("Found $total files to scan");
        
        foreach ($files as $file) {
            $filePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $file['path'];
            
            if (!file_exists($filePath)) {
                CLI::write("File not found: {$file['path']}", 'yellow');
                continue;
            }
            
            try {
                $result = $scanner->scanPath($filePath);
                $status = $result ? 'clean' : 'infected';
                
                $model->update($file['id'], ['scan_status' => $status]);
                
                if ($status === 'clean') {
                    $clean++;
                } else {
                    $infected++;
                    CLI::write("Infected file found: {$file['path']}", 'red');
                }
                
                $scanned++;
                
                if ($scanned % 10 === 0) {
                    CLI::write("Scanned $scanned/$total files...");
                }
                
            } catch (\Exception $e) {
                CLI::write("Error scanning {$file['path']}: " . $e->getMessage(), 'red');
            }
        }
        
        CLI::write("Rescan completed:");
        CLI::write("- Total scanned: $scanned");
        CLI::write("- Clean files: $clean");
        CLI::write("- Infected files: $infected");
    }
}
