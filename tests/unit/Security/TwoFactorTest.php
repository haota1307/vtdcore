<?php
use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;

class TwoFactorTest extends CIUnitTestCase
{
    public function testEnableAndVerify()
    {
        $db = Database::connect();
        $db->table('users')->truncate();
        $db->table('users')->insert([
            'email'=>'2fa@example.com','password'=>password_hash('pw',PASSWORD_BCRYPT),'created_at'=>date('Y-m-d H:i:s')
        ]);
        $userId = (int)$db->insertID();
        $svc = service('twoFactor');
        $en = $svc->enable($userId);
        $this->assertArrayHasKey('secret',$en);
        $secret = $en['secret'];
        $expectedCode = substr(strrev($secret),0,6);
        $this->assertTrue($svc->verify($userId,$expectedCode));
        $this->assertFalse($svc->verify($userId,'000000'));
    }
}
