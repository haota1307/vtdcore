<?php
namespace App\Commands\Auth;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class PasswordResetPurge extends BaseCommand
{
    protected $group = 'Auth';
    protected $name = 'password:purge';
    protected $description = 'Delete expired or used password reset tokens';

    public function run(array $params)
    {
        $db = Database::connect();
        $now = date('Y-m-d H:i:s');
        // Gather ids to delete
        $rows = $db->table('password_resets')
            ->groupStart()
                ->where('used_at IS NOT NULL')
                ->orGroupStart()->where('expires_at <', $now)->where('expires_at IS NOT NULL')->groupEnd()
            ->groupEnd()
            ->get()->getResultArray();
        if (!$rows) { CLI::write('Nothing to purge'); return; }
        $ids = array_column($rows,'id');
        $db->table('password_resets')->whereIn('id',$ids)->delete();
        CLI::write('Purged '.count($ids).' reset tokens','green');
    }
}
