<?php
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;

class PermissionFilterTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $db = Database::connect();
        // Clean involved tables
        foreach (['user_roles','role_permissions','roles','permissions','users'] as $t) {
            $db->table($t)->truncate();
        }
        // Seed user
        $db->table('users')->insert([
            'email'=>'permtest@example.com',
            'password'=>password_hash('secret', PASSWORD_BCRYPT),
            'created_at'=>date('Y-m-d H:i:s'),
        ]);
        $this->userId = (int)$db->insertID();
        // Seed role & permission
        $db->table('roles')->insert([
            'slug'=>'admin','name'=>'Admin','created_at'=>date('Y-m-d H:i:s')
        ]);
        $roleId = (int)$db->insertID();
        $db->table('permissions')->insert([
            'slug'=>'media.manage','name'=>'Media Manage','created_at'=>date('Y-m-d H:i:s')
        ]);
        $permId = (int)$db->insertID();
        $db->table('role_permissions')->insert([
            'role_id'=>$roleId,'permission_id'=>$permId
        ]);
        $db->table('user_roles')->insert([
            'user_id'=>$this->userId,'role_id'=>$roleId
        ]);
    }

    public function testPermissionFilterAllows()
    {
        // login
        $res = $this->post('/auth/login', [
            'email'=>'permtest@example.com',
            'password'=>'secret'
        ]);
        $res->assertStatus(200);
        // Access protected route (list-admin requires media.manage)
        $r2 = $this->get('/media/list-admin');
        $r2->assertStatus(200);
    }
}
