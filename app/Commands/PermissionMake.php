<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class PermissionMake extends BaseCommand
{
    protected $group = 'RBAC';
    protected $name = 'make:permission';
    protected $description = 'Create a permission (slug name [group])';
    protected $usage = 'make:permission <slug> <name> [group]';

    public function run(array $params)
    {
        $slug = $params[0] ?? null;
        $name = $params[1] ?? null;
        $group = $params[2] ?? null;
        if (! $slug || ! $name) { CLI::error('slug and name required'); return; }
        $svc = service('permissions');
        $perm = $svc->findPermissionBySlug($slug);
        if ($perm) { CLI::error('Permission exists'); return; }
        $id = $svc->createPermission($slug, $name, $group);
        CLI::write('Created permission id ' . $id);
    }
}
