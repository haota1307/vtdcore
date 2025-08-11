<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestDashboard extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:dashboard';
    protected $description = 'Test dashboard functionality';

    public function run(array $params)
    {
        CLI::write('Testing Dashboard Functionality...', 'yellow');
        CLI::newLine();

        $this->testUserModel();
        $this->testMediaModel();
        $this->testAuditLogModel();
        $this->testDashboardController();

        CLI::newLine();
        CLI::write('Dashboard test completed!', 'green');
    }

    private function testUserModel()
    {
        CLI::write('Testing UserModel...', 'blue');
        try {
            $userModel = new \App\Models\UserModel();
            $count = $userModel->countAll();
            CLI::write('✓ UserModel working correctly', 'green');
            CLI::write('  Total users: ' . $count, 'white');
        } catch (\Exception $e) {
            CLI::write('✗ UserModel error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }

    private function testMediaModel()
    {
        CLI::write('Testing MediaModel...', 'blue');
        try {
            $mediaModel = new \App\Models\MediaModel();
            $count = $mediaModel->countAll();
            CLI::write('✓ MediaModel working correctly', 'green');
            CLI::write('  Total media: ' . $count, 'white');
        } catch (\Exception $e) {
            CLI::write('✗ MediaModel error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }

    private function testAuditLogModel()
    {
        CLI::write('Testing AuditLogModel...', 'blue');
        try {
            $auditModel = new \App\Models\AuditLogModel();
            $count = $auditModel->countAll();
            CLI::write('✓ AuditLogModel working correctly', 'green');
            CLI::write('  Total audit logs: ' . $count, 'white');
        } catch (\Exception $e) {
            CLI::write('✗ AuditLogModel error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }

    private function testDashboardController()
    {
        CLI::write('Testing DashboardController...', 'blue');
        try {
            $controller = new \App\Controllers\Admin\DashboardController();
            
            // Test index method
            CLI::write('✓ DashboardController loaded successfully', 'green');
            CLI::write('  Class: ' . get_class($controller), 'white');
            
            // Test helper methods using reflection
            $reflection = new \ReflectionClass($controller);
            
            // Test getActivityColor
            $method = $reflection->getMethod('getActivityColor');
            $method->setAccessible(true);
            $color = $method->invoke($controller, 'admin.login.success');
            CLI::write('✓ getActivityColor() working', 'green');
            CLI::write('  Example: admin.login.success -> ' . $color, 'white');
            
            // Test getActivityIcon
            $method = $reflection->getMethod('getActivityIcon');
            $method->setAccessible(true);
            $icon = $method->invoke($controller, 'admin.login.success');
            CLI::write('✓ getActivityIcon() working', 'green');
            CLI::write('  Example: admin.login.success -> ' . $icon, 'white');
            
            // Test getSystemInfo
            $method = $reflection->getMethod('getSystemInfo');
            $method->setAccessible(true);
            $systemInfo = $method->invoke($controller);
            CLI::write('✓ getSystemInfo() working', 'green');
            CLI::write('  PHP Version: ' . $systemInfo['php_version'], 'white');
            
        } catch (\Exception $e) {
            CLI::write('✗ DashboardController error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }
}
