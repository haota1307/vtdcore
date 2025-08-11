<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class RoleMake extends BaseCommand
{
    protected $group = 'RBAC';
    protected $name = 'make:role';
    protected $description = 'Create a role (slug name [description])';
    protected $usage = 'make:role <slug> <name> [description]';

    public function run(array $params)
    {
        $slug = $params[0] ?? null;
        $name = $params[1] ?? null;
        $description = $params[2] ?? null;
        if (! $slug || ! $name) { CLI::error('slug and name required'); return; }
        $svc = service('permissions');
        $role = $svc->findRoleBySlug($slug);
        if ($role) { CLI::error('Role exists'); return; }
        $id = $svc->createRole($slug, $name, $description);
        CLI::write('Created role id ' . $id);
    }
}
