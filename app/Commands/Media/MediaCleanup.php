<?php
namespace App\Commands\Media;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\MediaModel;

class MediaCleanup extends BaseCommand
{
    protected $group = 'Media';
    protected $name = 'media:cleanup';
    protected $description = 'Clean up orphaned media files and fix database inconsistencies.';

    public function run(array $params)
    {
        $model = new MediaModel();
        $uploadRoot = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR;
        
        CLI::write("=== Media Cleanup Job ===", 'green');
        
        $dryRun = in_array('--dry-run', $params);
        if ($dryRun) {
            CLI::write("DRY RUN MODE - No files will be deleted", 'yellow');
        }
        
        // Find orphaned database records (files that don't exist on disk)
        CLI::write("\n=== Checking for orphaned database records ===", 'blue');
        
        $orphanedCount = 0;
        $files = $model->select('id, path')->findAll();
        
        foreach ($files as $file) {
            $filePath = $uploadRoot . $file['path'];
            
            if (!file_exists($filePath)) {
                CLI::write("Orphaned record: {$file['path']}", 'red');
                $orphanedCount++;
                
                if (!$dryRun) {
                    $model->delete($file['id']);
                    CLI::write("  -> Deleted database record", 'green');
                }
            }
        }
        
        CLI::write("Found $orphanedCount orphaned database records");
        
        // Find orphaned files (files on disk that aren't in database)
        CLI::write("\n=== Checking for orphaned files on disk ===", 'blue');
        
        $orphanedFiles = 0;
        $this->scanDirectory($uploadRoot, $model, $orphanedFiles, $dryRun);
        
        CLI::write("Found $orphanedFiles orphaned files on disk");
        
        // Check for duplicate files by hash
        CLI::write("\n=== Checking for duplicate files ===", 'blue');
        
        $duplicates = $model->select('hash, COUNT(*) as count, GROUP_CONCAT(id) as ids')
                           ->groupBy('hash')
                           ->having('count > 1')
                           ->findAll();
        
        $duplicateCount = 0;
        foreach ($duplicates as $dup) {
            $ids = explode(',', $dup['ids']);
            $count = (int)$dup['count'];
            
            CLI::write("Duplicate hash {$dup['hash']}: $count files");
            $duplicateCount += $count - 1; // Keep one, count the rest as duplicates
            
            if (!$dryRun && $count > 1) {
                // Keep the first one, delete the rest
                array_shift($ids); // Remove first ID
                foreach ($ids as $id) {
                    $model->delete($id);
                    CLI::write("  -> Deleted duplicate record ID: $id", 'green');
                }
            }
        }
        
        CLI::write("Found $duplicateCount duplicate files");
        
        // Summary
        CLI::write("\n=== Cleanup Summary ===", 'green');
        CLI::write("Orphaned database records: $orphanedCount");
        CLI::write("Orphaned files on disk: $orphanedFiles");
        CLI::write("Duplicate files: $duplicateCount");
        
        if ($dryRun) {
            CLI::write("\nThis was a dry run. Use without --dry-run to perform actual cleanup.", 'yellow');
        }
    }
    
    private function scanDirectory($dir, $model, &$orphanedFiles, $dryRun)
    {
        $files = glob($dir . '*');
        
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->scanDirectory($file . DIRECTORY_SEPARATOR, $model, $orphanedFiles, $dryRun);
                continue;
            }
            
            // Get relative path from upload root
            $relativePath = str_replace(WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR, '', $file);
            $relativePath = str_replace('\\', '/', $relativePath);
            
            // Check if this file exists in database
            $exists = $model->where('path', $relativePath)->first();
            
            if (!$exists) {
                CLI::write("Orphaned file: $relativePath", 'red');
                $orphanedFiles++;
                
                if (!$dryRun) {
                    unlink($file);
                    CLI::write("  -> Deleted file", 'green');
                }
            }
        }
    }
}
