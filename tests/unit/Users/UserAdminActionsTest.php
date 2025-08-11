<?php
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;

class UserAdminActionsTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    private int $adminId; private int $userId;

    protected function setUp(): void
    {
        parent::setUp();
    if (!extension_loaded('sqlite3')) { $this->markTestSkipped('sqlite3 extension not loaded'); }
        $db = Database::connect();
        foreach(['user_roles','role_permissions','roles','permissions','users'] as $t){ if($db->tableExists($t)) { $db->table($t)->truncate(); } }
        // seed permissions for manage and view
        $db->table('permissions')->insert(['slug'=>'users.manage','name'=>'Users Manage','created_at'=>date('Y-m-d H:i:s')]);
        $manageId = (int)$db->insertID();
        $db->table('permissions')->insert(['slug'=>'users.view','name'=>'Users View','created_at'=>date('Y-m-d H:i:s')]);
        $viewId = (int)$db->insertID();
        $db->table('roles')->insert(['slug'=>'admin','name'=>'Admin','created_at'=>date('Y-m-d H:i:s')]);
        $roleId = (int)$db->insertID();
        $db->table('role_permissions')->insert(['role_id'=>$roleId,'permission_id'=>$manageId]);
        $db->table('role_permissions')->insert(['role_id'=>$roleId,'permission_id'=>$viewId]);
        $db->table('users')->insert(['email'=>'admin@example.com','password'=>password_hash('pw',PASSWORD_BCRYPT),'status'=>'active','created_at'=>date('Y-m-d H:i:s')]);
        $this->adminId = (int)$db->insertID();
        $db->table('user_roles')->insert(['user_id'=>$this->adminId,'role_id'=>$roleId]);
        $db->table('users')->insert(['email'=>'user@example.com','password'=>password_hash('pw',PASSWORD_BCRYPT),'status'=>'active','created_at'=>date('Y-m-d H:i:s')]);
        $this->userId = (int)$db->insertID();
    }

    public function testToggleAndReset(): void
    {
    if (!extension_loaded('sqlite3')) { $this->markTestSkipped('sqlite3 extension not loaded'); }
        $this->post('/auth/login',['login'=>'admin@example.com','password'=>'pw'])->assertStatus(200);
        $this->post('/admin/users/'.$this->userId.'/toggle')->assertStatus(200);
        $db = Database::connect();
        $status = $db->table('users')->where('id',$this->userId)->get()->getRowArray()['status'];
        $this->assertEquals('disabled',$status,'status toggled to disabled');
        $reset = $this->post('/admin/users/'.$this->userId.'/reset-password');
        $reset->assertStatus(200);
        $new = $reset->getJSON()['new_password'] ?? '';
        $this->assertNotEmpty($new,'new password returned');
    }
}
