<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestLayoutFix extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:layout-fix';
    protected $description = 'Test layout fix and check for duplicates';

    public function run(array $params)
    {
        CLI::write('Testing Layout Fix...', 'yellow');
        CLI::newLine();

        $this->testLayoutSimplified();
        $this->testBreadcrumbSimplified();
        $this->testJavaScriptFix();
        $this->testCSSOverride();

        CLI::newLine();
        CLI::write('Layout fix test completed!', 'green');
    }

    private function testLayoutSimplified()
    {
        CLI::write('Testing Layout Fix...', 'blue');
        try {
            $mainLayout = APPPATH . 'Views/admin/layout/main.php';
            if (file_exists($mainLayout)) {
                $content = file_get_contents($mainLayout);
                
                // Check for layout fix elements
                $fixElements = [
                    'position: fixed' => 'Sidebar positioning',
                    'display: none' => 'Hide duplicates',
                    '!important' => 'CSS priority',
                    'z-index: 1000' => 'Z-index control',
                    'console.log' => 'Debug logging',
                    'DOMContentLoaded' => 'JavaScript event'
                ];
                
                $foundElements = 0;
                foreach ($fixElements as $element => $description) {
                    if (strpos($content, $element) !== false) {
                        $foundElements++;
                        CLI::write('  ✓ ' . $description . ' found', 'green');
                    } else {
                        CLI::write('  ✗ ' . $description . ' missing', 'red');
                    }
                }
                
                CLI::write('Layout fix elements: ' . $foundElements . '/' . count($fixElements), 'white');
            } else {
                CLI::write('✗ Main layout file not found', 'red');
            }
        } catch (\Exception $e) {
            CLI::write('✗ Layout fix error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }

    private function testBreadcrumbSimplified()
    {
        CLI::write('Testing Breadcrumb Fix...', 'blue');
        try {
            $mainLayout = APPPATH . 'Views/admin/layout/main.php';
            if (file_exists($mainLayout)) {
                $content = file_get_contents($mainLayout);
                
                // Check for breadcrumb elements
                if (strpos($content, 'breadcrumb') !== false) {
                    CLI::write('✓ Breadcrumb found', 'green');
                } else {
                    CLI::write('✗ Breadcrumb missing', 'red');
                }
                
                // Check for breadcrumb fix
                if (strpos($content, '.breadcrumb + .breadcrumb') !== false) {
                    CLI::write('✓ Breadcrumb duplicate fix found', 'green');
                } else {
                    CLI::write('✗ Breadcrumb duplicate fix missing', 'red');
                }
            } else {
                CLI::write('✗ Main layout file not found', 'red');
            }
        } catch (\Exception $e) {
            CLI::write('✗ Breadcrumb fix error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }

    private function testJavaScriptFix()
    {
        CLI::write('Testing JavaScript Fix...', 'blue');
        try {
            $mainLayout = APPPATH . 'Views/admin/layout/main.php';
            if (file_exists($mainLayout)) {
                $content = file_get_contents($mainLayout);
                
                // Check for JavaScript fix elements
                $jsElements = [
                    'console.log' => 'Debug logging',
                    'Found breadcrumbs:' => 'Breadcrumb counter',
                    'Found footers:' => 'Footer counter',
                    'Found page titles:' => 'Page title counter',
                    'Found sidebars:' => 'Sidebar counter',
                    'Removed duplicate' => 'Duplicate removal'
                ];
                
                $foundElements = 0;
                foreach ($jsElements as $element => $description) {
                    if (strpos($content, $element) !== false) {
                        $foundElements++;
                        CLI::write('  ✓ ' . $description . ' found', 'green');
                    } else {
                        CLI::write('  ✗ ' . $description . ' missing', 'red');
                    }
                }
                
                CLI::write('JavaScript fix elements: ' . $foundElements . '/' . count($jsElements), 'white');
            } else {
                CLI::write('✗ Main layout file not found', 'red');
            }
        } catch (\Exception $e) {
            CLI::write('✗ JavaScript fix error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }

    private function testCSSOverride()
    {
        CLI::write('Testing CSS Override...', 'blue');
        try {
            $mainLayout = APPPATH . 'Views/admin/layout/main.php';
            if (file_exists($mainLayout)) {
                $content = file_get_contents($mainLayout);
                
                // Check for CSS override elements
                $cssElements = [
                    'position: fixed' => 'Sidebar positioning',
                    'display: none' => 'Hide duplicates',
                    '!important' => 'CSS priority',
                    'z-index: 1000' => 'Z-index control'
                ];
                
                $foundElements = 0;
                foreach ($cssElements as $element => $description) {
                    if (strpos($content, $element) !== false) {
                        $foundElements++;
                        CLI::write('  ✓ ' . $description . ' found', 'green');
                    } else {
                        CLI::write('  ✗ ' . $description . ' missing', 'red');
                    }
                }
                
                CLI::write('CSS override elements: ' . $foundElements . '/' . count($cssElements), 'white');
            } else {
                CLI::write('✗ Main layout file not found', 'red');
            }
        } catch (\Exception $e) {
            CLI::write('✗ CSS override error: ' . $e->getMessage(), 'red');
        }
        CLI::newLine();
    }
}
