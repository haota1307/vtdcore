<?php
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;

class BearerAbilityTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    private int $userId;
    private string $plainToken;

    protected function setUp(): void
    {
        parent::setUp();
        $db = Database::connect();
        foreach (['user_api_tokens','users'] as $t) { $db->table($t)->truncate(); }
        $db->table('users')->insert([
            'email'=>'bearer@example.com',
            'password'=>password_hash('secret', PASSWORD_BCRYPT),
            'created_at'=>date('Y-m-d H:i:s'),
        ]);
        $this->userId = (int)$db->insertID();
        // issue token with ability media.read only
        $issued = service('tokens')->issue($this->userId,'test',['media.read'],60);
        $this->plainToken = $issued['token'];
    }

    public function testBearerAbilityAllowed()
    {
        $res = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->plainToken
        ])->get('/media/list');
        $res->assertStatus(200);
    }

    public function testBearerAbilityDenied()
    {
        $res = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->plainToken
        ])->get('/media/list-admin'); // requires permission filter (session) not bearer ability
        // should 401 because no session user (different filter) OR 403 if passes - expect 401
        $res->assertStatus(401);
    }
}
