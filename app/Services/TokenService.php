<?php
namespace App\Services;

use App\Models\UserApiTokenModel;
use CodeIgniter\I18n\Time;

class TokenService
{
    protected UserApiTokenModel $model;

    public function __construct()
    {
        $this->model = new UserApiTokenModel();
    }

    protected function randomToken(int $length = 40): string
    {
        return bin2hex(random_bytes($length/2));
    }

    public function issue(int $userId, string $name = 'default', array $abilities = ['*'], ?int $ttlMinutes = null): array
    {
        $plain = $this->randomToken();
        $hash  = hash('sha256', $plain);
        $expires = $ttlMinutes ? Time::now()->addMinutes($ttlMinutes) : null;
        $id = $this->model->insert([
            'user_id' => $userId,
            'name' => $name,
            'token_hash' => $hash,
            'abilities' => json_encode($abilities),
            'expires_at' => $expires?->toDateTimeString(),
            'created_at' => Time::now()->toDateTimeString(),
        ], true);
        return [
            'id' => $id,
            'token' => $plain,
            'name' => $name,
            'abilities' => $abilities,
            'expires_at' => $expires?->toDateTimeString(),
        ];
    }

    public function findValid(string $plain): ?array
    {
        $hash = hash('sha256', $plain);
        $row = $this->model->where('token_hash',$hash)->first();
        if (!$row) return null;
        if ($row['expires_at'] && Time::now()->isAfter($row['expires_at'])) {
            return null;
        }
        $row['abilities'] = $row['abilities'] ? json_decode($row['abilities'], true) : [];
        return $row;
    }

    public function revoke(int $id): bool
    {
        return (bool)$this->model->delete($id);
    }

    public function revokeByPlain(string $plain): bool
    {
        $hash = hash('sha256', $plain);
        $row = $this->model->where('token_hash',$hash)->first();
        if (!$row) return false;
        return (bool)$this->model->delete($row['id']);
    }
}
