<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class RoleAssign extends BaseCommand
{
    protected $group = 'RBAC';
    protected $name = 'role:assign';
    protected $description = 'Assign a role to user (userId roleSlug)';
    protected $usage = 'role:assign <userId> <roleSlug>';

    public function run(array $params)
    {
        $userId = (int)($params[0] ?? 0);
        $roleSlug = $params[1] ?? null;
        if ($userId <= 0 || ! $roleSlug) { CLI::error('userId and roleSlug required'); return; }
        $svc = service('permissions');
        $role = $svc->findRoleBySlug($roleSlug);
        if (! $role) { CLI::error('Role not found'); return; }
        $svc->assignRole($userId, (int)$role['id']);
        CLI::write('Assigned role ' . $roleSlug . ' to user ' . $userId);
    }
}
