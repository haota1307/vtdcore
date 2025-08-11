<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class TestPermissions extends BaseCommand
{
    protected $group = 'RBAC';
    protected $name = 'test:permissions';
    protected $description = 'Test permission system and debug access issues';

    public function run(array $params)
    {
        $db = Database::connect();
        
        CLI::write('=== Testing Permission System ===', 'yellow');
        
        // Test user 1 (superadmin)
        $userId = 1;
        
        // Get user info
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();
        if (!$user) {
            CLI::error("User ID $userId not found!");
            return;
        }
        
        CLI::write("Testing user: {$user['username']} ({$user['email']})", 'green');
        
        // Get user roles
        $roles = $db->table('user_roles ur')
            ->select('r.*')
            ->join('roles r', 'r.id = ur.role_id')
            ->where('ur.user_id', $userId)
            ->get()
            ->getResultArray();
            
        CLI::write("User roles:", 'cyan');
        foreach ($roles as $role) {
            CLI::write("- {$role['name']} (slug: {$role['slug']})", 'white');
        }
        
        // Get user permissions
        $permissions = $db->table('permissions p')
            ->select('p.slug, p.name')
            ->join('role_permissions rp', 'rp.permission_id = p.id')
            ->join('user_roles ur', 'ur.role_id = rp.role_id')
            ->where('ur.user_id', $userId)
            ->orderBy('p.slug')
            ->get()
            ->getResultArray();
            
        CLI::write("\nUser permissions:", 'cyan');
        foreach ($permissions as $perm) {
            CLI::write("- {$perm['name']} (slug: {$perm['slug']})", 'white');
        }
        
        // Test specific permissions
        $testPermissions = [
            'admin.roles.view',
            'admin.roles.manage',
            'admin.users.view',
            'admin.users.manage'
        ];
        
        CLI::write("\nTesting specific permissions:", 'cyan');
        foreach ($testPermissions as $perm) {
            $hasPerm = false;
            foreach ($permissions as $userPerm) {
                if ($userPerm['slug'] === $perm) {
                    $hasPerm = true;
                    break;
                }
            }
            $status = $hasPerm ? '✅ YES' : '❌ NO';
            CLI::write("- $perm: $status", $hasPerm ? 'green' : 'red');
        }
        
        // Test PermissionService
        CLI::write("\nTesting PermissionService:", 'cyan');
        $permissionService = service('permissions');
        
        foreach ($testPermissions as $perm) {
            $hasPerm = $permissionService->userHas($userId, $perm);
            $status = $hasPerm ? '✅ YES' : '❌ NO';
            CLI::write("- $perm: $status", $hasPerm ? 'green' : 'red');
        }
        
        // Test current user permissions
        CLI::write("\nTesting current user permissions:", 'cyan');
        $currentUser = $auth->user();
        if ($currentUser) {
            CLI::write("Current user: {$currentUser['username']} (ID: {$currentUser['id']})", 'green');
            
            foreach ($testPermissions as $perm) {
                $hasPerm = $permissionService->userHas($currentUser['id'], $perm);
                $status = $hasPerm ? '✅ YES' : '❌ NO';
                CLI::write("- $perm: $status", $hasPerm ? 'green' : 'red');
            }
        } else {
            CLI::write("❌ No user logged in!", 'red');
        }
        
        CLI::write("\n=== Test Complete ===", 'yellow');
    }
}
