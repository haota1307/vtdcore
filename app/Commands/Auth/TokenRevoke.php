<?php
namespace App\Commands\Auth;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\UserApiTokenModel;

class TokenRevoke extends BaseCommand
{
    protected $group = 'Auth';
    protected $name = 'token:revoke';
    protected $description = 'Revoke an API token by ID';
    protected $usage = 'token:revoke id';

    public function run(array $params)
    {
        if (empty($params[0])) { CLI::error('Usage: ' . $this->usage); return; }
        $id = (int)$params[0];
        $m = new UserApiTokenModel();
        $row = $m->find($id);
        if (!$row) { CLI::error('Token not found'); return; }
        service('tokens')->revoke($id);
        CLI::write('Revoked token #' . $id, 'green');
    }
}
