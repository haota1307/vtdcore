<?php
namespace App\Commands\Media;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\MediaService;

class MediaGenerateThumbnails extends BaseCommand
{
    protected $group = 'Media';
    protected $name = 'media:generate-thumbnails';
    protected $description = 'Generate missing thumbnails for existing media files.';

    public function run(array $params)
    {
        $mediaService = new MediaService();
        
        CLI::write("=== Generating Missing Thumbnails ===", 'green');
        
        $dryRun = in_array('--dry-run', $params);
        if ($dryRun) {
            CLI::write("DRY RUN MODE - No thumbnails will be generated", 'yellow');
        }
        
        if (!$dryRun) {
            $result = $mediaService->generateMissingThumbnails();
            
            CLI::write("Thumbnail generation completed:");
            CLI::write("- Generated: {$result['generated']} thumbnails", 'green');
            CLI::write("- Errors: {$result['errors']}", $result['errors'] > 0 ? 'red' : 'green');
        } else {
            // Count how many would be generated
            $model = new \App\Models\MediaModel();
            $images = $model->where('mime LIKE', 'image/%')
                           ->where('variants IS NULL')
                           ->where('width IS NOT NULL')
                           ->where('height IS NOT NULL')
                           ->findAll();
            
            CLI::write("Would generate thumbnails for " . count($images) . " images");
        }
    }
}
