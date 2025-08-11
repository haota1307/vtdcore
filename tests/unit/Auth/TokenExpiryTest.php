<?php
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;
use CodeIgniter\I18n\Time;

class TokenExpiryTest extends CIUnitTestCase
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
            'email'=>'expire@example.com',
            'password'=>password_hash('secret', PASSWORD_BCRYPT),
            'created_at'=>date('Y-m-d H:i:s'),
        ]);
        $this->userId = (int)$db->insertID();
        // TTL 1 minute
        $issued = service('tokens')->issue($this->userId,'exp',['media.read'],1);
        $this->plainToken = $issued['token'];
        // Force expiry by updating expires_at to past
        $db->table('user_api_tokens')->where('id',$issued['id'])->update(['expires_at'=>Time::now()->subMinutes(5)->toDateTimeString()]);
    }

    public function testExpiredTokenDenied()
    {
        $res = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->plainToken
        ])->get('/media/list');
        $res->assertStatus(401); // unauthorized token
    }
}
