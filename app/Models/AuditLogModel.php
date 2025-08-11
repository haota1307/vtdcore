<?php
namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table = 'audit_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id','action','ip','context','created_at'];
    protected $returnType = 'array';
    public $useTimestamps = false;
}
