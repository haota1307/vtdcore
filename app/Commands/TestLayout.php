<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestLayout extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:layout';
    protected $description = 'Test layout functionality and check for duplicates';

    public function run(array $params)
    {
        CLI::write('Testing Layout Functionality...', 'yellow');
        CLI::newLine();

        $this->testLayoutFiles();
        $this->testBreadcrumbHelper();
        $this->testFooterFile();
        $this->testLayoutStructure();

        CLI::newLine();
        CLI::write('Layout test completed!', 'green');
    }

    private function testLayoutFiles()
    {
        CLI::write('Testing Layout Files...', 'blue');
        try {
            $layoutFiles = [
                'main.php' => APPPATH . 'Views/admin/layout/main.php',
                'sidebar.php' => APPPATH . 'Views/admin/layout/sidebar.php',
                'top-bar.php' => APPPATH . 'Views/admin/layout/top-bar.php',
                'footer.php' => APPPATH . 'Views/admin/layout/footer.php'
            ];
            
            foreach ($layoutFiles as $name => $path) {
                if (file_exists($path)) {
                    $content = file_get_contents($path);
                    CLI::write('✓ ' . $name . ' exists (' . number_format(strlen($content)) . ' bytes)', 'green');
                } else {
                    CLI::write('✗ ' . $name . ' not found', 'red');
                }
            }
        } catch (\Exception $e) {
            CLI::write('✗ Layout files error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }

    private function testBreadcrumbHelper()
    {
        CLI::write('Testing Breadcrumb Helper...', 'blue');
        try {
            if (function_exists('build_admin_breadcrumbs')) {
                $breadcrumbs = build_admin_breadcrumbs();
                CLI::write('✓ build_admin_breadcrumbs() function works', 'green');
                CLI::write('  Breadcrumbs count: ' . count($breadcrumbs), 'white');
            } else {
                CLI::write('✗ build_admin_breadcrumbs() function not found', 'red');
            }
        } catch (\Exception $e) {
            CLI::write('✗ Breadcrumb helper error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }

    private function testFooterFile()
    {
        CLI::write('Testing Footer File...', 'blue');
        try {
            $footerFile = APPPATH . 'Views/admin/layout/footer.php';
            if (file_exists($footerFile)) {
                $content = file_get_contents($footerFile);
                CLI::write('✓ Footer file exists', 'green');
                CLI::write('  File size: ' . number_format(strlen($content)) . ' bytes', 'white');
                
                // Check for common footer elements
                $elements = ['copyright', 'footer', 'container'];
                $foundElements = 0;
                foreach ($elements as $element) {
                    if (stripos($content, $element) !== false) {
                        $foundElements++;
                    }
                }
                CLI::write('  Footer elements found: ' . $foundElements . '/' . count($elements), 'white');
            } else {
                CLI::write('✗ Footer file not found', 'red');
            }
        } catch (\Exception $e) {
            CLI::write('✗ Footer file error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }

    private function testLayoutStructure()
    {
        CLI::write('Testing Layout Structure...', 'blue');
        try {
            $mainLayout = APPPATH . 'Views/admin/layout/main.php';
            if (file_exists($mainLayout)) {
                $content = file_get_contents($mainLayout);
                
                // Check for layout structure elements
                $structure = [
                    'layout-wrapper' => 'Main wrapper',
                    'main-content' => 'Main content area',
                    'page-content' => 'Page content',
                    'container-fluid' => 'Container fluid',
                    'breadcrumb' => 'Breadcrumb',
                    'footer' => 'Footer include'
                ];
                
                $foundElements = 0;
                foreach ($structure as $element => $description) {
                    if (strpos($content, $element) !== false) {
                        $foundElements++;
                        CLI::write('  ✓ ' . $description . ' found', 'green');
                    } else {
                        CLI::write('  ✗ ' . $description . ' missing', 'red');
                    }
                }
                
                CLI::write('Layout structure elements: ' . $foundElements . '/' . count($structure), 'white');
            } else {
                CLI::write('✗ Main layout file not found', 'red');
            }
        } catch (\Exception $e) {
            CLI::write('✗ Layout structure error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }
}
