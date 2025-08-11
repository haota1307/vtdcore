<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class MediaPurgeJob extends BaseCommand
{
    protected $group       = 'Media';
    protected $name        = 'media:purge-job';
    protected $description = 'Scheduler: Purge soft-deleted media files (wrapper for media:purge)';

    public function run(array $params)
    {
        CLI::write('Running scheduled media:purge...', 'yellow');
        // Call the existing media:purge command
        \CodeIgniter\Console\Commands::run('media:purge', $params);
        CLI::write('media:purge-job done.', 'green');
    }
}
