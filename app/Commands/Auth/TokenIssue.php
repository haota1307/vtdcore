<?php
namespace App\Commands\Auth;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TokenIssue extends BaseCommand
{
    protected $group = 'Auth';
    protected $name = 'token:issue';
    protected $description = 'Issue an API token';
    protected $usage = 'token:issue user_id name [abilities comma] [ttl_minutes]';

    public function run(array $params)
    {
        if (count($params) < 2) {
            CLI::error('Usage: ' . $this->usage);
            return;
        }
        $userId = (int)$params[0];
        $name = $params[1];
        $abilities = isset($params[2]) ? array_map('trim', explode(',', $params[2])) : ['*'];
        $ttl = isset($params[3]) ? (int)$params[3] : null;
        $issued = service('tokens')->issue($userId, $name, $abilities, $ttl);
        CLI::write('Token issued (store securely):');
        CLI::write('Plain: ' . $issued['token']);
        CLI::write('ID: ' . $issued['id']);
        CLI::write('Expires: ' . ($issued['expires_at']??'-'));
    }
}
