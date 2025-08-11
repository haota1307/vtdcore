<?php
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;

class MediaFilterTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
    if (!extension_loaded('sqlite3')) { $this->markTestSkipped('sqlite3 extension not loaded'); }
        $db = Database::connect();
        foreach(['media','user_roles','role_permissions','roles','permissions','users'] as $t){ if($db->tableExists($t)) { $db->table($t)->truncate(); } }
        $db->table('users')->insert(['email'=>'u@example.com','password'=>password_hash('pw',PASSWORD_BCRYPT),'status'=>'active','created_at'=>date('Y-m-d H:i:s')]);
        $uid = (int)$db->insertID();
        // permission to access admin media
        $db->table('roles')->insert(['slug'=>'admin','name'=>'Admin','created_at'=>date('Y-m-d H:i:s')]);
        $roleId = (int)$db->insertID();
        $db->table('permissions')->insert(['slug'=>'media.manage','name'=>'Media Manage','created_at'=>date('Y-m-d H:i:s')]);
        $permId = (int)$db->insertID();
        $db->table('role_permissions')->insert(['role_id'=>$roleId,'permission_id'=>$permId]);
        $db->table('user_roles')->insert(['user_id'=>$uid,'role_id'=>$roleId]);
        $db->table('media')->insert([
            'disk'=>'local','path'=>'2025/08/img1.jpg','original_name'=>'img1.jpg','mime'=>'image/jpeg','size'=>10,'hash'=>'h1','width'=>10,'height'=>10,'owner_id'=>$uid,'created_at'=>date('Y-m-d H:i:s')
        ]);
        $db->table('media')->insert([
            'disk'=>'local','path'=>'2025/08/doc1.pdf','original_name'=>'doc1.pdf','mime'=>'application/pdf','size'=>20,'hash'=>'h2','owner_id'=>$uid,'created_at'=>date('Y-m-d H:i:s')
        ]);
    }

    public function testMimeFilter(): void
    {
    if (!extension_loaded('sqlite3')) { $this->markTestSkipped('sqlite3 extension not loaded'); }
        $this->post('/auth/login',['login'=>'u@example.com','password'=>'pw'])->assertStatus(200);
        $resp = $this->get('/admin/media?mime=image');
        $resp->assertStatus(200);
        $body = $resp->getBody();
        $this->assertStringContainsString('img1.jpg',$body);
        $this->assertStringNotContainsString('doc1.pdf',$body);
    }
}
