<?php
namespace App\Commands\Audit;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class AuditPrune extends BaseCommand
{
    protected $group = 'Audit';
    protected $name = 'audit:prune';
    protected $description = 'Prune old audit logs older than given days';
    protected $usage = 'audit:prune [days]';

    public function run(array $params)
    {
        $days = isset($params[0]) ? (int)$params[0] : 30;
        $cut = date('Y-m-d H:i:s', time() - $days*86400);
        $db = Database::connect();
        $builder = $db->table('audit_logs')->where('created_at <', $cut);
        $cnt = $builder->countAllResults(false);
        $builder->delete();
        CLI::write('Pruned '.$cnt.' entries older than '.$days.' days','green');
    }
}
