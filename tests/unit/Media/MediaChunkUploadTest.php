<?php
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;

class MediaChunkUploadTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        if (!extension_loaded('sqlite3')) {
            $this->markTestSkipped('sqlite3 extension not loaded');
        }
        $db = Database::connect();
        foreach (['media','user_roles','role_permissions','roles','permissions','users'] as $t) { if ($db->tableExists($t)) { $db->table($t)->truncate(); } }
        // user with media.manage permission
        $db->table('users')->insert(['email'=>'u@example.com','password'=>password_hash('pw',PASSWORD_BCRYPT),'created_at'=>date('Y-m-d H:i:s')]);
        $uid = (int)$db->insertID();
        $db->table('roles')->insert(['slug'=>'admin','name'=>'Admin','created_at'=>date('Y-m-d H:i:s')]);
        $roleId = (int)$db->insertID();
        $db->table('permissions')->insert(['slug'=>'media.manage','name'=>'Media Manage','created_at'=>date('Y-m-d H:i:s')]);
        $permId = (int)$db->insertID();
        $db->table('role_permissions')->insert(['role_id'=>$roleId,'permission_id'=>$permId]);
        $db->table('user_roles')->insert(['user_id'=>$uid,'role_id'=>$roleId]);
    }

    public function testChunkUploadFlow(): void
    {
    if (!extension_loaded('sqlite3')) { $this->markTestSkipped('sqlite3 extension not loaded'); }
        $this->post('/auth/login',['login'=>'u@example.com','password'=>'pw'])->assertStatus(200);
        $init = $this->post('/media/chunk/init',[ 'name'=>'big.txt','mime'=>'text/plain' ]);
        $init->assertStatus(200);
        $json = $init->getJSON();
        $uploadId = $json['upload_id'] ?? null;
        $this->assertNotEmpty($uploadId, 'upload id');
        $total = 3; $contentParts = ['Hello ','Chunk ','World'];
        for($i=0;$i<$total;$i++){
            $tmp = tempnam(sys_get_temp_dir(),'c'); file_put_contents($tmp,$contentParts[$i]);
            $uploaded = new \CodeIgniter\HTTP\Files\UploadedFile($tmp,'chunk'.$i,true);
            $resp = $this->post('/media/chunk/put', [ 'upload_id'=>$uploadId,'index'=>$i,'total'=>$total,'chunk'=>$uploaded ]);
            $resp->assertStatus(200);
        }
        $db = Database::connect();
        $row = $db->table('media')->where('original_name','big.txt')->get()->getRowArray();
        $this->assertNotEmpty($row,'media row created');
        $this->assertEquals('text/plain',$row['mime']);
    }
}
