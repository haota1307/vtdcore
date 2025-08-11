<?php
namespace App\Services;

use App\Models\PasswordResetModel;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;

class PasswordResetService
{
    private PasswordResetModel $resets;
    private UserModel $users;

    public function __construct()
    {
        $this->resets = new PasswordResetModel();
        $this->users = new UserModel();
    }

    protected function randomToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    public function create(string $email, int $ttlMinutes = 30): ?array
    {
        $user = $this->users->where('email',$email)->first();
        if (!$user) return null;
        $plain = $this->randomToken();
        $hash = hash('sha256',$plain);
        $expires = Time::now()->addMinutes($ttlMinutes)->toDateTimeString();
        $this->resets->insert([
            'email'=>$email,
            'token_hash'=>$hash,
            'expires_at'=>$expires,
            'created_at'=>Time::now()->toDateTimeString(),
        ]);
        return [
            'token'=>$plain,
            'expires_at'=>$expires,
        ];
    }

    public function verify(string $email, string $plain): bool
    {
        $hash = hash('sha256',$plain);
        $row = $this->resets->where('email',$email)->where('token_hash',$hash)->orderBy('id','desc')->first();
        if (!$row) return false;
        if ($row['used_at']) return false;
        if ($row['expires_at'] && Time::now()->isAfter($row['expires_at'])) return false;
        return true;
    }

    public function consume(string $email, string $plain, string $newPassword): bool
    {
        if (! $this->verify($email,$plain)) return false;
        $hash = hash('sha256',$plain);
        $row = $this->resets->where('email',$email)->where('token_hash',$hash)->orderBy('id','desc')->first();
        $user = $this->users->where('email',$email)->first();
        if (!$user) return false;
        $this->users->update($user['id'],[
            'password_hash'=>password_hash($newPassword,PASSWORD_DEFAULT)
        ]);
        $this->resets->update($row['id'],['used_at'=>Time::now()->toDateTimeString()]);
        return true;
    }
}
