<?php
namespace App\Controllers\Audit;

use CodeIgniter\RESTful\ResourceController;

class AuditController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $req = service('request');
        $limit = (int)($req->getGet('limit') ?? 50); $limit = min(max($limit,1),200);
        $page = (int)($req->getGet('page') ?? 1); $page = max($page,1);
        $model = new \App\Models\AuditLogModel();
        $rows = $model->orderBy('id','desc')->paginate($limit,'default',$page);
        $pager = $model->pager;
        return $this->respond([
            'data'=>$rows,
            'pager'=>[
                'page'=>$page,
                'per_page'=>$limit,
                'total'=>$pager->getTotal(),
                'page_count'=>$pager->getPageCount(),
            ]
        ]);
    }
}
