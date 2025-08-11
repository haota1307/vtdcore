<?php
namespace App\Commands\RBAC;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class RolePermit extends BaseCommand
{
    protected $group = 'RBAC';
    protected $name = 'role:permit';
    protected $description = 'Attach permission(s) to a role by slug.';

    protected $usage = 'role:permit role-slug perm-slug [more-perm-slugs ...]';

    public function run(array $params)
    {
        if (count($params) < 2) {
            CLI::error('Usage: ' . $this->usage);
            return;
        }
    $roleSlug = array_shift($params);
    $perms = $params;
        $svc = service('permissions');
        $role = $svc->findRoleBySlug($roleSlug);
        if (!$role) { CLI::error("Role not found: $roleSlug"); return; }
        foreach ($perms as $permSlug) {
            $perm = $svc->findPermissionBySlug($permSlug);
            if (!$perm) { CLI::error("Permission not found: $permSlug"); continue; }
            $svc->attachPermissionToRole((int)$role['id'], (int)$perm['id']);
            CLI::write("Attached $permSlug to $roleSlug", 'green');
            audit_event('rbac.role.permit',[ 'role'=>$roleSlug,'permission'=>$permSlug ]);
        }
    }
}
