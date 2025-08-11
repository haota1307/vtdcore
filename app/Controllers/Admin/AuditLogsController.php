<?php
namespace App\Controllers\Admin;

use App\Models\AuditLogModel;

class AuditLogsController extends AdminBaseController
{
    protected ?string $requiredPermission = 'admin.audit.view';

    public function index()
    {
        $model = new AuditLogModel();
        $q = trim((string) service('request')->getGet('q'));
        $userId = service('request')->getGet('user_id');
        if ($q !== '') {
            $model->groupStart()->like('action', $q)->orLike('context', $q)->groupEnd();
        }
        if ($userId !== null && $userId !== '') {
            $model->where('user_id', (int)$userId);
        }
        $data = $this->paginate($model, 30);
        return $this->render('audit/index', [
            'title' => lang('admin.auditLogs'),
            'logs' => $data['items'],
            'meta' => $data['pager'],
            'pagerObj' => $data['pagerObj'] ?? null,
            'q' => $q,
            'filter_user_id' => $userId,
        ]);
    }
}
