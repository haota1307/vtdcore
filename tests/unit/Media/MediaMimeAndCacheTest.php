<?php
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;

class MediaMimeAndCacheTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        Database::connect()->table('users')->truncate();
        Database::connect()->table('media')->truncate();
        Database::connect()->table('users')->insert([
            'email'=>'cache@example.com','password'=>password_hash('pw',PASSWORD_BCRYPT),'created_at'=>date('Y-m-d H:i:s')
        ]);
        $this->post('/auth/login',[ 'login'=>'cache@example.com','password'=>'pw' ])->assertStatus(200);
    }

    public function testRejectUnsupportedMime()
    {
        $tmp = tempnam(sys_get_temp_dir(),'txt');
        file_put_contents($tmp,'plain text');
        $file = new \CodeIgniter\HTTP\Files\UploadedFile($tmp,'note.txt','text/plain',null,true);
        $res = $this->withFiles(['file'=>$file])->post('/media/upload');
        $res->assertStatus(415);
    }

    public function testETagAndHeadOnVariant()
    {
        $img = imagecreatetruecolor(12,12);
        $p = sys_get_temp_dir().'/etag.jpg';
        imagejpeg($img,$p,85); imagedestroy($img);
        $file = new \CodeIgniter\HTTP\Files\UploadedFile($p,'etag.jpg','image/jpeg',null,true);
        $res = $this->withFiles(['file'=>$file])->post('/media/upload');
        $res->assertStatus(201);
        $arr = json_decode($res->getJSON(), true);
        $id = $arr['media']['id'];
        $thumb = $this->get('/media/item/'.$id.'/thumb');
        $thumb->assertStatus(200);
        $etag = $thumb->getHeaderLine('ETag');
        $this->assertNotEmpty($etag);
        $cond = $this->withHeaders(['If-None-Match'=>$etag])->get('/media/item/'.$id.'/thumb');
        $cond->assertStatus(304);
        $head = $this->call('head','/media/item/'.$id.'/thumb');
        $head->assertStatus(200);
        $this->assertSame($etag, $head->getHeaderLine('ETag'));
    }
}
