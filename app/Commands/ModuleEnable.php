<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ModuleEnable extends BaseCommand
{
    protected $group       = 'Modules';
    protected $name        = 'module:enable';
    protected $description = 'Enable a module.';
    protected $usage       = 'module:enable <moduleId>';

    public function run(array $params)
    {
        $id = $params[0] ?? null;
        if (! $id) { CLI::error('Module id required'); return; }
        $manager = service('modules');
        $manager->setEnabled($id, true);
        CLI::write("Enabled module: $id");
    }
}
