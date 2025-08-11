<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ModuleCacheClear extends BaseCommand
{
    protected $group       = 'Modules';
    protected $name        = 'module:cache-clear';
    protected $description = 'Clear module manifest cache.';

    public function run(array $params)
    {
        $manager = service('modules');
        $manager->clearCache();
        CLI::write('Module manifest cache cleared.');
    }
}
