<?php
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;

class AuditPaginationTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $db = Database::connect();
        $db->table('audit_logs')->truncate();
        $db->table('users')->truncate();
        $db->table('users')->insert(['email'=>'admin@example.com','password'=>password_hash('pw',PASSWORD_BCRYPT),'created_at'=>date('Y-m-d H:i:s')]);
        $userId = (int)$db->insertID();
        // seed permission audit.view via direct inserts
        $db->table('roles')->truncate();
        $db->table('permissions')->truncate();
        $db->table('role_permissions')->truncate();
        $db->table('user_roles')->truncate();
        $db->table('roles')->insert(['slug'=>'adm','name'=>'Admin','created_at'=>date('Y-m-d H:i:s')]);
        $roleId = (int)$db->insertID();
        $db->table('permissions')->insert(['slug'=>'audit.view','name'=>'Audit View','created_at'=>date('Y-m-d H:i:s')]);
        $permId = (int)$db->insertID();
        $db->table('role_permissions')->insert(['role_id'=>$roleId,'permission_id'=>$permId]);
        $db->table('user_roles')->insert(['user_id'=>$userId,'role_id'=>$roleId]);
        // seed audit logs
        for ($i=0;$i<35;$i++) {
            $db->table('audit_logs')->insert([
                'user_id'=>null,'action'=>'test.event','ip'=>null,'context'=>null,'created_at'=>date('Y-m-d H:i:s', time()-$i)
            ]);
        }
        // login
        $this->post('/auth/login',[ 'login'=>'admin@example.com','password'=>'pw' ])->assertStatus(200);
    }

    public function testPagination()
    {
        $res1 = $this->get('/audit/logs?page=1&limit=10');
        $res1->assertStatus(200);
        $res2 = $this->get('/audit/logs?page=2&limit=10');
        $res2->assertStatus(200);
        $json1 = json_decode($res1->getJSON(),true);
        $json2 = json_decode($res2->getJSON(),true);
        $this->assertNotEquals($json1['data'][0]['id'],$json2['data'][0]['id']);
        $this->assertEquals(35,$json1['pager']['total']);
    }
}
