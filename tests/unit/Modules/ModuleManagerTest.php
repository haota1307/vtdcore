<?php

use App\Core\ModuleManager;
use CodeIgniter\Test\CIUnitTestCase;

class ModuleManagerTest extends CIUnitTestCase
{
    private string $modulesPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->modulesPath = APPPATH . 'Modules';
    }

    public function testEnableDisablePersists()
    {
        $manager = new ModuleManager($this->modulesPath);
        $manager->scan();
        $mods = $manager->all();
        $this->assertNotEmpty($mods, 'Need at least one module (Demo)');
        $demo = null;
        foreach ($mods as $m) { if ($m->getId() === 'demo') { $demo = $m; break; } }
        $this->assertNotNull($demo, 'Demo module missing');
        $this->assertTrue($manager->isEnabled('DEMO')); // case-insensitive
        $manager->setEnabled('DEMO', false);
        $this->assertFalse($manager->isEnabled('demo'));
        // Reload manager
        $manager2 = new ModuleManager($this->modulesPath);
        $manager2->scan();
        $this->assertFalse($manager2->isEnabled('demo'));
        $manager2->setEnabled('demo', true);
        $this->assertTrue($manager2->isEnabled('DEMO'));
    }

    public function testManifestCacheCreated()
    {
        $manager = new ModuleManager($this->modulesPath);
        $manager->scan();
        $manifest = $manager->getManifest();
        $this->assertIsArray($manifest);
        $this->assertArrayHasKey('modules', $manifest);
        // Force second scan use cache
        $before = $manifest;
        $manager->scan();
        $this->assertEquals($before['modules'], $manager->getManifest()['modules']);
    }
}
