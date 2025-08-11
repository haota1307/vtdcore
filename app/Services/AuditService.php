<?php
namespace App\Services;

use App\Models\AuditLogModel;

class AuditService
{
    private AuditLogModel $model;

    public function __construct()
    {
        $this->model = new AuditLogModel();
    }

    public function log(?int $userId, string $action, array $context = []): void
    {
        $this->model->insert([
            'user_id'=>$userId,
            'action'=>$action,
            'ip'=>service('request')->getIPAddress(),
            'context'=> $context ? json_encode($context) : null,
            'created_at'=>date('Y-m-d H:i:s'),
        ]);
    }
}
