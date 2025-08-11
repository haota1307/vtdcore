<?php
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;

class MediaDeleteTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    private int $ownerId;
    private int $otherId;
    private int $mediaId;

    protected function setUp(): void
    {
        parent::setUp();
        $db = Database::connect();
        foreach (['media','users'] as $t) { $db->table($t)->truncate(); }
        // Users
        $db->table('users')->insert(['email'=>'owner@example.com','password'=>password_hash('pw',PASSWORD_BCRYPT),'created_at'=>date('Y-m-d H:i:s')]);
        $this->ownerId = (int)$db->insertID();
        $db->table('users')->insert(['email'=>'other@example.com','password'=>password_hash('pw',PASSWORD_BCRYPT),'created_at'=>date('Y-m-d H:i:s')]);
        $this->otherId = (int)$db->insertID();
        // Media owned by owner
        $db->table('media')->insert([
            'disk'=>'local','path'=>'2025/08/demo.jpg','original_name'=>'demo.jpg','mime'=>'image/jpeg','size'=>123,'hash'=>'abc','width'=>10,'height'=>10,'variants'=>null,'owner_id'=>$this->ownerId,'created_at'=>date('Y-m-d H:i:s')
        ]);
        $this->mediaId = (int)$db->insertID();
    }

    public function testOwnerCanDelete(): void
    {
        $this->post('/auth/login',[ 'login'=>'owner@example.com','password'=>'pw' ])->assertStatus(200);
        $resp = $this->delete('/media/item/'.$this->mediaId);
        $resp->assertStatus(200); // respondDeleted -> 200/204 depending CI; assume 200 here
    }

    public function testOtherForbidden(): void
    {
        $this->post('/auth/login',[ 'login'=>'other@example.com','password'=>'pw' ])->assertStatus(200);
        $resp = $this->delete('/media/item/'.$this->mediaId);
        $resp->assertStatus(403);
    }
}
