<?php
use CodeIgniter\Test\CIUnitTestCase;
use App\Services\SettingsService;
use Config\Database;

class SettingsServiceTest extends CIUnitTestCase
{
    private SettingsService $svc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->svc = new SettingsService();
        // Ensure clean
        Database::connect()->table('settings')->truncate();
    }

    public function testPrecedenceUserOverridesModuleOverridesSystem()
    {
        $this->svc->setSystem('theme', 'light');
        $this->svc->setModule('blog','theme','dark');
        $this->svc->setUser(5,'theme','solar','blog');
        // user scope override
        $val = $this->svc->get('theme',['user_id'=>5,'module'=>'blog']);
        $this->assertEquals('solar', $val);
        // user not set -> module
        $val2 = $this->svc->get('theme',['module'=>'blog']);
        $this->assertEquals('dark',$val2);
        // fallback system
        $val3 = $this->svc->get('theme',[]);
        $this->assertEquals('light',$val3);
    }

    public function testJsonStorageAndCasting()
    {
        $this->svc->setSystem('features', ['a'=>true,'b'=>2]);
        $val = $this->svc->get('features');
        $this->assertIsArray($val);
        $this->assertTrue($val['a']);
        $this->assertEquals(2,$val['b']);
    }
}
