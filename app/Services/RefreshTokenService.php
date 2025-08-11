<?php
namespace App\Services;

use CodeIgniter\I18n\Time;
use Config\Database;

class RefreshTokenService
{
    private $db;
    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function issue(int $userId, ?int $ttlDays = 30): array
    {
        $token = bin2hex(random_bytes(32));
        $now = Time::now();
        $expires = $now->addDays($ttlDays ?? 30);
        $this->db->table('refresh_tokens')->insert([
            'user_id'=>$userId,
            'token'=>hash('sha256',$token),
            'expires_at'=>$expires->toDateTimeString(),
            'created_at'=>$now->toDateTimeString(),
        ]);
        return ['refresh_token'=>$token,'expires_at'=>$expires->toDateTimeString()];
    }

    public function rotate(string $token): ?array
    {
        $hashed = hash('sha256',$token);
        $row = $this->db->table('refresh_tokens')->where('token',$hashed)->get()->getRowArray();
        if (!$row) return null;
        if ($row['revoked_at'] || $row['rotated_at']) return null; // already used
        if (strtotime($row['expires_at']) < time()) return null;
        $newPlain = bin2hex(random_bytes(32));
        $now = Time::now();
        $expires = $now->addDays(30);
        // mark old rotated
        $this->db->table('refresh_tokens')->where('id',$row['id'])->update(['rotated_at'=>$now->toDateTimeString()]);
        // insert new referencing parent
        $this->db->table('refresh_tokens')->insert([
            'user_id'=>$row['user_id'],
            'token'=>hash('sha256',$newPlain),
            'expires_at'=>$expires->toDateTimeString(),
            'parent_id'=>$row['id'],
            'created_at'=>$now->toDateTimeString(),
        ]);
        return ['refresh_token'=>$newPlain,'expires_at'=>$expires->toDateTimeString(),'user_id'=>$row['user_id']];
    }

    public function revoke(string $token): bool
    {
        $hashed = hash('sha256',$token);
        return (bool)$this->db->table('refresh_tokens')->where('token',$hashed)->update(['revoked_at'=>Time::now()->toDateTimeString()]);
    }
}
