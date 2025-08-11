<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use App\Services\AuthService;
use App\Services\PermissionService;

class TestSession extends BaseCommand
{
    protected $group = 'RBAC';
    protected $name = 'test:session';
    protected $description = 'Test session and authentication directly';

    public function run(array $params)
    {
        CLI::write('=== Testing Session and Authentication ===', 'yellow');
        
        // Test AuthService
        $auth = service('auth');
        CLI::write("\n1. Testing AuthService:", 'cyan');
        CLI::write("AuthService instance: " . ($auth ? '✅ Created' : '❌ Failed'), $auth ? 'green' : 'red');
        
        // Test current user
        $user = $auth->user();
        CLI::write("Current user: " . ($user ? $user['username'] : '❌ No user logged in'), $user ? 'green' : 'red');
        
        // Test PermissionService
        $permissions = service('permissions');
        CLI::write("\n2. Testing PermissionService:", 'cyan');
        CLI::write("PermissionService instance: " . ($permissions ? '✅ Created' : '❌ Failed'), $permissions ? 'green' : 'red');
        
        if ($user) {
            // Test specific permissions
            $testPerms = ['admin.roles.view', 'admin.roles.manage'];
            foreach ($testPerms as $perm) {
                $hasPerm = $permissions->userHas($user['id'], $perm);
                CLI::write("User has $perm: " . ($hasPerm ? '✅ YES' : '❌ NO'), $hasPerm ? 'green' : 'red');
            }
        }
        
        // Test database connection
        CLI::write("\n3. Testing Database:", 'cyan');
        $db = Database::connect();
        CLI::write("Database connection: " . ($db ? '✅ Connected' : '❌ Failed'), $db ? 'green' : 'red');
        
        // Test user 2 (admin)
        $user2 = $db->table('users')->where('id', 2)->get()->getRowArray();
        CLI::write("User 2 exists: " . ($user2 ? '✅ Yes' : '❌ No'), $user2 ? 'green' : 'red');
        
        if ($user2) {
            CLI::write("User 2: {$user2['username']} ({$user2['email']})", 'white');
            
            // Test user roles
            $roles = $db->table('user_roles ur')
                ->select('r.name, r.slug')
                ->join('roles r', 'r.id = ur.role_id')
                ->where('ur.user_id', 2)
                ->get()
                ->getResultArray();
                
            CLI::write("User 2 roles:", 'white');
            foreach ($roles as $role) {
                CLI::write("- {$role['name']} ({$role['slug']})", 'white');
            }
            
            // Test user permissions
            $perms = $db->table('permissions p')
                ->select('p.slug, p.name')
                ->join('role_permissions rp', 'rp.permission_id = p.id')
                ->join('user_roles ur', 'ur.role_id = rp.role_id')
                ->where('ur.user_id', 2)
                ->whereIn('p.slug', ['admin.roles.view', 'admin.roles.manage'])
                ->get()
                ->getResultArray();
                
            CLI::write("User 2 specific permissions:", 'white');
            foreach ($perms as $perm) {
                CLI::write("- {$perm['name']} ({$perm['slug']})", 'white');
            }
        }
        
        CLI::write("\n=== Test Complete ===", 'yellow');
    }
}
