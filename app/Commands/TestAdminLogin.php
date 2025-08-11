<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestAdminLogin extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:admin-login';
    protected $description = 'Test admin login system components';

    public function run(array $params)
    {
        CLI::write('Testing Admin Login System...', 'yellow');
        CLI::newLine();

        $this->testAuthService();
        $this->testRoleService();
        $this->testPermissionService();
        $this->testPasswordResetService();
        $this->testDatabaseConnection();
        $this->testAdminUrlHelper();

        CLI::newLine();
        CLI::write('Test completed!', 'green');
    }

    private function testAuthService()
    {
        CLI::write('Testing AuthService...', 'blue');
        try {
            $auth = service('auth');
            CLI::write('✓ AuthService loaded successfully', 'green');
            CLI::write('  Class: ' . get_class($auth), 'white');
        } catch (\Exception $e) {
            CLI::write('✗ AuthService error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }

    private function testRoleService()
    {
        CLI::write('Testing RoleService...', 'blue');
        try {
            $roles = service('roles');
            CLI::write('✓ RoleService loaded successfully', 'green');
            CLI::write('  Class: ' . get_class($roles), 'white');
        } catch (\Exception $e) {
            CLI::write('✗ RoleService error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }

    private function testPermissionService()
    {
        CLI::write('Testing PermissionService...', 'blue');
        try {
            $permissions = service('permissions');
            CLI::write('✓ PermissionService loaded successfully', 'green');
            CLI::write('  Class: ' . get_class($permissions), 'white');
        } catch (\Exception $e) {
            CLI::write('✗ PermissionService error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }

    private function testPasswordResetService()
    {
        CLI::write('Testing PasswordResetService...', 'blue');
        try {
            $passwordReset = service('passwordReset');
            CLI::write('✓ PasswordResetService loaded successfully', 'green');
            CLI::write('  Class: ' . get_class($passwordReset), 'white');
        } catch (\Exception $e) {
            CLI::write('✗ PasswordResetService error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }

    private function testDatabaseConnection()
    {
        CLI::write('Testing Database Connection...', 'blue');
        try {
            $db = \Config\Database::connect();
            $result = $db->query('SELECT 1 as test')->getRow();
            CLI::write('✓ Database connection successful', 'green');
            CLI::write('  Test result: ' . $result->test, 'white');
        } catch (\Exception $e) {
            CLI::write('✗ Database error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }

    private function testAdminUrlHelper()
    {
        CLI::write('Testing admin_url() helper...', 'blue');
        try {
            if (function_exists('admin_url')) {
                $url = admin_url('auth/login');
                CLI::write('✓ admin_url() function works', 'green');
                CLI::write('  Example: ' . $url, 'white');
            } else {
                CLI::write('✗ admin_url() function not found', 'red');
            }
        } catch (\Exception $e) {
            CLI::write('✗ admin_url() error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }
}
