<?php
namespace App\Services;

use Config\Database;

class TwoFactorService
{
    public function enable(int $userId): array
    {
        $secret = bin2hex(random_bytes(10));
        $db = Database::connect();
        $row = $db->table('user_twofactor')->where('user_id',$userId)->get()->getRowArray();
        if ($row) {
            $db->table('user_twofactor')->where('id',$row['id'])->update(['secret'=>$secret,'enabled_at'=>date('Y-m-d H:i:s')]);
        } else {
            $db->table('user_twofactor')->insert([
                'user_id'=>$userId,
                'secret'=>$secret,
                'enabled_at'=>date('Y-m-d H:i:s'),
                'created_at'=>date('Y-m-d H:i:s'),
            ]);
        }
        return ['secret'=>$secret];
    }

    public function verify(int $userId, string $code): bool
    {
        // Placeholder: accept first 6 chars of secret reversed as code for demo
        $db = Database::connect();
        $row = $db->table('user_twofactor')->where('user_id',$userId)->get()->getRowArray();
        if (!$row) return false;
        $expected = substr(strrev($row['secret']),0,6);
        return hash_equals($expected,$code);
    }

    public function disable(int $userId): bool
    {
        $db = Database::connect();
        return (bool)$db->table('user_twofactor')->where('user_id',$userId)->delete();
    }

    public function generateBackupCodes(int $userId, int $count = 5): array
    {
        $db = Database::connect();
        $codes = [];
        for ($i=0;$i<$count;$i++) {
            $plain = strtoupper(bin2hex(random_bytes(4)));
            $codes[] = $plain;
        }
        // store hashed
        $db->table('user_twofactor_backups')->where('user_id',$userId)->delete();
        foreach ($codes as $c) {
            $db->table('user_twofactor_backups')->insert([
                'user_id'=>$userId,
                'code_hash'=>password_hash($c,PASSWORD_BCRYPT),
                'created_at'=>date('Y-m-d H:i:s')
            ]);
        }
        return $codes; // plain codes for display once
    }

    public function consumeBackupCode(int $userId, string $code): bool
    {
        $db = Database::connect();
        $rows = $db->table('user_twofactor_backups')->where('user_id',$userId)->get()->getResultArray();
        foreach ($rows as $r) {
            if (password_verify($code,$r['code_hash'])) {
                // delete used code
                $db->table('user_twofactor_backups')->where('id',$r['id'])->delete();
                return true;
            }
        }
        return false;
    }
}
