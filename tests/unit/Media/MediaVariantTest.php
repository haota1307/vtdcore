<?php
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;

class MediaVariantTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        Database::connect()->table('users')->truncate();
        Database::connect()->table('media')->truncate();
        // create user & login
        Database::connect()->table('users')->insert([
            'email'=>'var@example.com','password'=>password_hash('pw',PASSWORD_BCRYPT),'created_at'=>date('Y-m-d H:i:s')
        ]);
        $this->post('/auth/login',[ 'login'=>'var@example.com','password'=>'pw' ])->assertStatus(200);
    }

    public function testVariantEndpoints()
    {
        // simulate image upload by creating a small temp JPEG
        $img = imagecreatetruecolor(10,10);
        $tmpPath = sys_get_temp_dir().'/test_up.jpg';
        imagejpeg($img,$tmpPath,90);
        imagedestroy($img);
        $file = new \CodeIgniter\HTTP\Files\UploadedFile($tmpPath,'test_up.jpg','image/jpeg',null,true);
        $res = $this->withFiles(['file'=>$file])->post('/media/upload');
        $res->assertStatus(201);
        $data = $res->getJSON();
        $arr = json_decode($data,true);
        $id = $arr['media']['id'];
        // thumb
        $thumb = $this->get('/media/item/'.$id.'/thumb');
        $thumb->assertStatus(200);
        // variant small
        $small = $this->get('/media/item/'.$id.'/variant/small');
        $small->assertStatus(200);
        // variant invalid
        $missing = $this->get('/media/item/'.$id.'/variant/xxx');
        $missing->assertStatus(404);
    }
}
