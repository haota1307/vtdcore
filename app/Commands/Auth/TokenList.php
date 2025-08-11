<?php
namespace App\Commands\Auth;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\UserApiTokenModel;

class TokenList extends BaseCommand
{
    protected $group = 'Auth';
    protected $name = 'token:list';
    protected $description = 'List API tokens optionally by user id.';
    protected $usage = 'token:list [user_id]';

    public function run(array $params)
    {
        $model = new UserApiTokenModel();
        if (!empty($params[0])) {
            $model->where('user_id', (int)$params[0]);
        }
        $rows = $model->orderBy('id','desc')->findAll();
        if (!$rows) { CLI::write('No tokens'); return; }
        foreach ($rows as $r) {
            CLI::write(sprintf('#%d user=%d name=%s expires=%s', $r['id'],$r['user_id'],$r['name'],$r['expires_at']??'-'));
        }
    }
}
