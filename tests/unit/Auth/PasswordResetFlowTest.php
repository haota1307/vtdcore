<?php
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;

class PasswordResetFlowTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    private string $email = 'resetflow@example.com';

    protected function setUp(): void
    {
        parent::setUp();
        $db = Database::connect();
        $db->table('users')->truncate();
        $db->table('password_resets')->truncate();
        $db->table('users')->insert([
            'email'=>$this->email,
            'password'=>password_hash('OldPass123', PASSWORD_BCRYPT),
            'created_at'=>date('Y-m-d H:i:s'),
        ]);
    }

    public function testFullResetFlow(): void
    {
        // Generate token via service (since HTTP endpoint hides token)
        $reset = service('passwordResets')->create($this->email, 15);
        $this->assertNotNull($reset);
        $plain = $reset['token'];

        // Consume via endpoint
        $resp = $this->post('/auth/password/reset', [
            'email'=>$this->email,
            'token'=>$plain,
            'password'=>'NewPass456'
        ]);
        $resp->assertStatus(200);

        // Login with old password should fail
        $failLogin = $this->post('/auth/login',[ 'login'=>$this->email,'password'=>'OldPass123' ]);
        $failLogin->assertStatus(401);

        // Login with new password should pass
        $okLogin = $this->post('/auth/login',[ 'login'=>$this->email,'password'=>'NewPass456' ]);
        $okLogin->assertStatus(200);
    }
}
