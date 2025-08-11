<?php
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;

class AuditPermissionDeniedTest extends CIUnitTestCase
{
    use FeatureTestTrait;    

    public function testAuditLogsForbiddenWithoutPermission()
    {
        $db = db_connect();
        $db->table('users')->insert([
            'email'=>'noperms@example.com',
            'password_hash'=>password_hash('secret', PASSWORD_BCRYPT),
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        $userId = $db->insertID();
        $_SESSION['user_id'] = $userId;
        $result = $this->get('/audit/logs');
        $result->assertStatus(403);
    }
}
