<?php
use CodeIgniter\Test\CIUnitTestCase;
use App\Services\VirusScannerService;

class VirusScanTest extends CIUnitTestCase
{
    public function testScannerFailClosedRejectsWhenUnavailable()
    {
        $svc = new VirusScannerService('tcp://127.0.0.1:9', true); // port 9 likely unused -> simulate unreachable
        $tmp = tempnam(sys_get_temp_dir(),'scan');
        file_put_contents($tmp, 'cleancontent');
        $res = $svc->scanPathDetailed($tmp);
        $bool = $svc->scanPath($tmp);
        $this->assertNull($res['clean']);
        $this->assertFalse($bool, 'Fail-closed should reject when scanner unreachable');
        @unlink($tmp);
    }
}
