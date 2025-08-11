<?php
namespace App\Services;

use App\Models\UserModel;
use CodeIgniter\I18n\Time;

class AuthService
{
    private ?array $user = null;
    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function attempt(string $usernameOrEmail, string $password): bool
    {
        $user = $this->users->where('username', $usernameOrEmail)
            ->orWhere('email', $usernameOrEmail)
            ->first();
        if (! $user) {
            return false;
        }
            if (isset($user['status']) && $user['status'] !== 'active') {
                return false;
            }
        if (! password_verify($password, $user['password_hash'])) {
            return false;
        }
        if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
            $this->users->update($user['id'], ['password_hash'=>password_hash($password, PASSWORD_DEFAULT)]);
        }
        $this->user = $user;
        session()->set('uid', $user['id']);
        $this->users->update($user['id'], ['last_login_at'=>Time::now()->toDateTimeString()]);
        return true;
    }

    public function user(): ?array
    {
        if ($this->user) { return $this->user; }
        $id = session()->get('uid');
        if (! $id) { return null; }
        $u = $this->users->find($id);
        $this->user = $u ?: null;
        return $this->user;
    }

    public function logout(): void
    {
        session()->remove('uid');
        $this->user = null;
    }

    public function register(string $username, string $email, string $password): array
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $id = $this->users->insert([
            'username'=>$username,
            'email'=>$email,
            'password_hash'=>$hash,
            'status'=>'active',
        ]);
        return $this->users->find($id);
    }
}
