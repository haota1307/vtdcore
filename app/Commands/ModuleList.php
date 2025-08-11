<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ModuleList extends BaseCommand
{
    protected $group       = 'Modules';
    protected $name        = 'module:list';
    protected $description = 'List all modules with status.';

    public function run(array $params)
    {
        $manager = service('modules');
        $manager->scan(); // ensure refresh
        $rows = [];
        foreach ($manager->all() as $m) {
            $rows[] = [
                $m->getId(),
                $m->getName(),
                $m->getVersion(),
                $manager->isEnabled($m->getId()) ? 'ENABLED' : 'DISABLED'
            ];
        }
        CLI::table($rows, ['ID','Name','Version','Status']);
    }
}
