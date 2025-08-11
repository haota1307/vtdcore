<?php
namespace App\Commands\Audit;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class AuditTail extends BaseCommand
{
    protected $group = 'Audit';
    protected $name = 'audit:tail';
    protected $description = 'Show latest audit log entries';
    protected $usage = 'audit:tail [limit]';

    public function run(array $params)
    {
        $limit = isset($params[0]) ? (int)$params[0] : 20;
        $rows = Database::connect()->table('audit_logs')->orderBy('id','desc')->limit($limit)->get()->getResultArray();
        foreach (array_reverse($rows) as $r) {
            CLI::write(sprintf('%s | #%d user=%s action=%s %s', $r['created_at'],$r['id'],$r['user_id']??'-',$r['action'],$r['context']??''));
        }
    }
}
