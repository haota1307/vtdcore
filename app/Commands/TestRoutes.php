<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class TestRoutes extends BaseCommand
{
    protected $group = 'RBAC';
    protected $name = 'test:routes';
    protected $description = 'Test routes functionality';

    public function run(array $params)
    {
        CLI::write('=== Testing Routes ===', 'yellow');
        
        $db = Database::connect();
        
        // Test role data
        $role = $db->table('roles')->where('id', 2)->get()->getRowArray();
        if (!$role) {
            CLI::error('Role ID 2 not found!');
            return;
        }
        
        CLI::write("Testing role: {$role['name']} (ID: {$role['id']})", 'green');
        
        // Test users query
        CLI::write("\nTesting users query:", 'cyan');
        $users = $db->table('user_roles ur')
            ->select('u.id, u.username, u.email, u.created_at')
            ->join('users u', 'u.id = ur.user_id')
            ->where('ur.role_id', 2)
            ->orderBy('u.username', 'asc')
            ->get()
            ->getResultArray();
            
        CLI::write("Users found: " . count($users), 'white');
        foreach ($users as $user) {
            CLI::write("- {$user['username']} ({$user['email']})", 'white');
        }
        
        // Test permissions query
        CLI::write("\nTesting permissions query:", 'cyan');
        $permissions = $db->table('permissions')
            ->select('id, slug, name, `group`')
            ->orderBy('`group`', 'asc')
            ->orderBy('name', 'asc')
            ->get()
            ->getResultArray();
            
        CLI::write("Total permissions: " . count($permissions), 'white');
        
        // Test role permissions
        $rolePermissions = $db->table('role_permissions')
            ->where('role_id', 2)
            ->get()
            ->getResultArray();
        $rolePermissionIds = array_column($rolePermissions, 'permission_id');
        
        CLI::write("Role permissions count: " . count($rolePermissionIds), 'white');
        
        // Test grouped permissions
        $groupedPermissions = [];
        foreach ($permissions as $permission) {
            $group = $permission['group'] ?? 'General';
            if (!isset($groupedPermissions[$group])) {
                $groupedPermissions[$group] = [];
            }
            $groupedPermissions[$group][] = $permission;
        }
        
        CLI::write("Permission groups:", 'white');
        foreach ($groupedPermissions as $group => $perms) {
            CLI::write("- $group: " . count($perms) . " permissions", 'white');
        }
        
        CLI::write("\n=== Test Complete ===", 'yellow');
    }
}
