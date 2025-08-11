<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestSidebar extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:sidebar';
    protected $description = 'Test sidebar functionality';

    public function run(array $params)
    {
        CLI::write('Testing Sidebar Functionality...', 'yellow');
        CLI::newLine();

        $this->testSidebarFile();
        $this->testSidebarLinks();
        $this->testAdminUrlHelper();

        CLI::newLine();
        CLI::write('Sidebar test completed!', 'green');
    }

    private function testSidebarFile()
    {
        CLI::write('Testing Sidebar File...', 'blue');
        try {
            $sidebarFile = APPPATH . 'Views/admin/layout/sidebar.php';
            if (file_exists($sidebarFile)) {
                $content = file_get_contents($sidebarFile);
                $menuItems = [
                    'Dashboard' => 'admin_url()',
                    'Users' => 'admin_url(\'users\')',
                    'Media' => 'admin_url(\'media\')',
                    'Settings' => 'admin_url(\'settings\')',
                    'Logs' => 'admin_url(\'logs\')',
                    'Profile' => 'admin_url(\'profile\')',
                    'Logout' => 'admin_url(\'auth/logout\')'
                ];
                
                $foundItems = 0;
                foreach ($menuItems as $item => $url) {
                    if (strpos($content, $item) !== false) {
                        $foundItems++;
                    }
                }
                
                CLI::write('✓ Sidebar file exists', 'green');
                CLI::write('  File size: ' . number_format(strlen($content)) . ' bytes', 'white');
                CLI::write('  Menu items found: ' . $foundItems . '/' . count($menuItems), 'white');
            } else {
                CLI::write('✗ Sidebar file not found', 'red');
            }
        } catch (\Exception $e) {
            CLI::write('✗ Sidebar file error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }

    private function testSidebarLinks()
    {
        CLI::write('Testing Sidebar Links...', 'blue');
        try {
            $links = [
                'Dashboard' => admin_url(),
                'Users' => admin_url('users'),
                'Media' => admin_url('media'),
                'Settings' => admin_url('settings'),
                'Logs' => admin_url('logs'),
                'Profile' => admin_url('profile'),
                'Logout' => admin_url('auth/logout')
            ];
            
            CLI::write('✓ Sidebar links generated', 'green');
            foreach ($links as $name => $url) {
                CLI::write('  ' . $name . ': ' . $url, 'white');
            }
        } catch (\Exception $e) {
            CLI::write('✗ Sidebar links error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }

    private function testAdminUrlHelper()
    {
        CLI::write('Testing admin_url() Helper...', 'blue');
        try {
            if (function_exists('admin_url')) {
                $testUrl = admin_url('test');
                CLI::write('✓ admin_url() function works', 'green');
                CLI::write('  Test URL: ' . $testUrl, 'white');
            } else {
                CLI::write('✗ admin_url() function not found', 'red');
            }
        } catch (\Exception $e) {
            CLI::write('✗ admin_url() error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }
}
