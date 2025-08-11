<?php
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;

class LoginThrottleTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $db = Database::connect();
        $db->table('users')->truncate();
        $db->table('users')->insert([
            'email'=>'throttle@example.com',
            'password'=>password_hash('secret', PASSWORD_BCRYPT),
            'created_at'=>date('Y-m-d H:i:s'),
        ]);
    }

    public function testThrottleTriggers()
    {
        // exceed attempts (default 5) by 6 wrong logins
        for ($i=0;$i<6;$i++) {
            $res = $this->post('/auth/login',[ 'login'=>'throttle@example.com','password'=>'wrong' ]);
        }
        $res->assertStatus(429);
    }
}
