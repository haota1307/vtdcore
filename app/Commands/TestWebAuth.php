<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class TestWebAuth extends BaseCommand
{
    protected $group = 'RBAC';
    protected $name = 'test:web-auth';
    protected $description = 'Test web authentication and session';

    public function run(array $params)
    {
        CLI::write('=== Testing Web Authentication ===', 'yellow');
        
        // Test database connection
        $db = Database::connect();
        
        // Test user 2 (admin)
        $user = $db->table('users')->where('id', 2)->get()->getRowArray();
        if (!$user) {
            CLI::error('User ID 2 not found!');
            return;
        }
        
        CLI::write("Testing user: {$user['username']} ({$user['email']})", 'green');
        
        // Test password verification
        $password = 'admin123';
        $isValid = password_verify($password, $user['password_hash']);
        CLI::write("Password verification: " . ($isValid ? '✅ VALID' : '❌ INVALID'), $isValid ? 'green' : 'red');
        
        // Test user roles
        $roles = $db->table('user_roles ur')
            ->select('r.*')
            ->join('roles r', 'r.id = ur.role_id')
            ->where('ur.user_id', 2)
            ->get()
            ->getResultArray();
            
        CLI::write("User roles:", 'cyan');
        foreach ($roles as $role) {
            CLI::write("- {$role['name']} (slug: {$role['slug']})", 'white');
        }
        
        // Test user permissions
        $permissions = $db->table('permissions p')
            ->select('p.slug, p.name')
            ->join('role_permissions rp', 'rp.permission_id = p.id')
            ->join('user_roles ur', 'ur.role_id = rp.role_id')
            ->where('ur.user_id', 2)
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
        
        // Test session configuration
        CLI::write("\nSession configuration:", 'cyan');
        $sessionConfig = config('App')->sessionDriver ?? 'files';
        CLI::write("Session driver: $sessionConfig", 'white');
        
        // Test CSRF configuration
        CLI::write("\nCSRF configuration:", 'cyan');
        $csrfConfig = config('App')->CSRFProtection ?? true;
        CLI::write("CSRF protection: " . ($csrfConfig ? '✅ ENABLED' : '❌ DISABLED'), $csrfConfig ? 'green' : 'red');
        
        // Test routes
        CLI::write("\nTesting routes:", 'cyan');
        $routes = [
            '/admin/roles/1/permissions' => 'GET',
            '/admin/roles/1/users' => 'GET',
            '/admin/roles/1/data' => 'GET'
        ];
        
        foreach ($routes as $route => $method) {
            CLI::write("- $method $route", 'white');
        }
        
        CLI::write("\n=== Instructions ===", 'yellow');
        CLI::write("1. Open browser and go to: http://localhost:8080/admin/auth/login", 'white');
        CLI::write("2. Login with:", 'white');
        CLI::write("   Email: admin@vtdevcore.com", 'white');
        CLI::write("   Password: admin123", 'white');
        CLI::write("3. After login, go to: http://localhost:8080/admin/roles", 'white');
        CLI::write("4. Click on a role to test the tabs", 'white');
        
        CLI::write("\n=== Test Complete ===", 'yellow');
    }
}
