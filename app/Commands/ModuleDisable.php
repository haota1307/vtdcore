<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ModuleDisable extends BaseCommand
{
    protected $group       = 'Modules';
    protected $name        = 'module:disable';
    protected $description = 'Disable a module.';
    protected $usage       = 'module:disable <moduleId>';

    public function run(array $params)
    {
        $id = $params[0] ?? null;
        if (! $id) { CLI::error('Module id required'); return; }
        $manager = service('modules');
        $manager->setEnabled($id, false);
        CLI::write("Disabled module: $id");
    }
}
