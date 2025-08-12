<?php
namespace App\Controllers\Media;

use App\Services\MediaService;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class MediaController extends ResourceController
{
    protected $format = 'json';
    protected $helpers = ['media'];
    private MediaService $media;

    public function __construct()
    {
        $this->media = service('media');
    }

    public function upload(): ResponseInterface
    {
        $req = service('request');
        $file = $req->getFile('file');
        if (! $file) {
            return $this->failValidationErrors('Thiếu trường file "file"');
        }
        try {
            $ownerId = service('auth')->user()['id'] ?? null;
            $record = $this->media->store($file, $ownerId);
            $record['url'] = $this->media->url($record);
            return $this->respondCreated(['media'=>$record]);
        } catch (\DomainException $e) {
            return $this->fail('Loại file không được hỗ trợ', 415);
        } catch (\Throwable $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function chunkInit(): ResponseInterface
    {
        $req = service('request');
        $original = (string)$req->getPost('name');
        $mime = (string)$req->getPost('mime');
        if ($original === '') return $this->failValidationErrors('Thiếu tên file');
        $id = bin2hex(random_bytes(8));
        $dir = WRITEPATH.'uploads'.DIRECTORY_SEPARATOR.'chunks'.DIRECTORY_SEPARATOR.$id;
        if (!is_dir($dir)) mkdir($dir,0775,true);
        file_put_contents($dir.DIRECTORY_SEPARATOR.'meta.json', json_encode(['name'=>$original,'mime'=>$mime,'created'=>time()]));
        return $this->respond(['upload_id'=>$id]);
    }

    public function chunkPut(): ResponseInterface
    {
        $req = service('request');
        $uploadId = (string)$req->getPost('upload_id');
        $index = (int)$req->getPost('index');
        $total = (int)$req->getPost('total');
        $file = $req->getFile('chunk');
        if ($uploadId===''||$total<1||!$file) return $this->failValidationErrors('Tham số không hợp lệ');
        $dir = WRITEPATH.'uploads'.DIRECTORY_SEPARATOR.'chunks'.DIRECTORY_SEPARATOR.$uploadId;
        if (!is_dir($dir)) return $this->failNotFound('ID upload không tồn tại');
        $meta = json_decode(@file_get_contents($dir.DIRECTORY_SEPARATOR.'meta.json'), true) ?: [];
        $file->move($dir, $index.'.part', true);
        $parts = glob($dir.DIRECTORY_SEPARATOR.'*.part');
        if (count($parts) >= $total) {
            $assembled = $dir.DIRECTORY_SEPARATOR.'assembled.bin';
            $out = fopen($assembled,'wb');
            for ($i=0;$i<$total;$i++) {
                $p = $dir.DIRECTORY_SEPARATOR.$i.'.part';
                if (!is_file($p)) { fclose($out); return $this->failServerError('Thiếu phần '.$i); }
                fwrite($out, file_get_contents($p));
            }
            fclose($out);
            try {
                $ownerId = service('auth')->user()['id'] ?? null;
                $record = service('media')->storeFromPath($assembled, $meta['name'] ?? ('upload-'.$uploadId), $ownerId, $meta['mime'] ?? null);
                $record['url'] = service('media')->url($record);
                foreach (glob($dir.DIRECTORY_SEPARATOR.'*.part') as $pp) @unlink($pp);
                @unlink($dir.DIRECTORY_SEPARATOR.'meta.json');
                @rmdir($dir);
                return $this->respondCreated(['complete'=>true,'media'=>$record]);
            } catch (\Throwable $e) {
                return $this->failServerError($e->getMessage());
            }
        }
        return $this->respond(['received'=>true,'index'=>$index]);
    }

    public function show($id = null): ResponseInterface
    {
        $model = new \App\Models\MediaModel();
        $row = $model->find($id);
        if (! $row) return $this->failNotFound('File không tồn tại');
        $currentUserId = service('auth')->user()['id'] ?? null;
        if ($currentUserId && (int)$row['owner_id'] !== (int)$currentUserId) {
            return $this->failForbidden('Không phải chủ sở hữu');
        }
        $row['url'] = $this->media->url($row);
        return $this->respond(['media'=>$row]);
    }

    public function list(): ResponseInterface
    {
        $model = new \App\Models\MediaModel();
        // For bearer ability route: restrict to current bearer user if present
        $request = service('request');
        $ownerId = $request->user['id'] ?? (service('auth')->user()['id'] ?? null);
        if ($ownerId) {
            $model->where('owner_id',$ownerId);
        }
        $req = service('request');
        $limit = (int)($req->getGet('limit') ?? 25); $limit = min(max($limit,1),100);
        $page = (int)($req->getGet('page') ?? 1); $page = max($page,1);
        $rows = $model->orderBy('id','desc')->paginate($limit,'default',$page);
        $pager = $model->pager;
        foreach ($rows as &$r) {
            $r['url'] = $this->media->url($r);
        }
        return $this->respond([
            'items'=>$rows,
            'pager'=>[
                'page'=>$page,
                'per_page'=>$limit,
                'total'=>$pager->getTotal(),
                'page_count'=>$pager->getPageCount(),
            ]
        ]);
    }

    public function thumb($id = null): ResponseInterface
    {
        $model = new \App\Models\MediaModel();
        $row = $model->find($id);
        if (!$row) return $this->failNotFound('File không tồn tại');
        $vars = $row['variants'] ? json_decode($row['variants'], true) : [];
        if (!isset($vars['thumb'])) return $this->failNotFound('Không có thumbnail');
        $filePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $vars['thumb'];
        if (!is_file($filePath)) return $this->failNotFound('File thumbnail không tồn tại');
        $etag = 'W/"'.sha1_file($filePath).'"';
        $lastMod = gmdate('D, d M Y H:i:s', filemtime($filePath)).' GMT';
        $req = $this->request;
        if ($req->getHeaderLine('If-None-Match') === $etag) {
            return $this->response->setStatusCode(304);
        }
        if ($ims = $req->getHeaderLine('If-Modified-Since')) {
            if (strtotime($ims) >= filemtime($filePath)) {
                return $this->response->setStatusCode(304);
            }
        }
        $this->response->setHeader('ETag',$etag)->setHeader('Last-Modified',$lastMod)->setHeader('Cache-Control','public, max-age=86400');
        if ($req->getMethod() === 'head') {
            return $this->response->setHeader('Content-Type','image/jpeg');
        }
        return $this->response->setHeader('Content-Type','image/jpeg')->setBody(file_get_contents($filePath));
    }

    public function variant($id = null, $name = null): ResponseInterface
    {
        $model = new \App\Models\MediaModel();
        $row = $model->find($id);
        if (!$row) return $this->failNotFound('File không tồn tại');
        $vars = $row['variants'] ? json_decode($row['variants'], true) : [];
        if (!$name || !isset($vars[$name])) return $this->failNotFound('Biến thể không tồn tại');
        $filePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $vars[$name];
        if (!is_file($filePath)) return $this->failNotFound('File biến thể không tồn tại');
        $etag = 'W/"'.sha1_file($filePath).'"';
        $lastMod = gmdate('D, d M Y H:i:s', filemtime($filePath)).' GMT';
        $req = $this->request;
        if ($req->getHeaderLine('If-None-Match') === $etag) {
            return $this->response->setStatusCode(304);
        }
        if ($ims = $req->getHeaderLine('If-Modified-Since')) {
            if (strtotime($ims) >= filemtime($filePath)) {
                return $this->response->setStatusCode(304);
            }
        }
        $this->response->setHeader('ETag',$etag)->setHeader('Last-Modified',$lastMod)->setHeader('Cache-Control','public, max-age=86400');
        if ($req->getMethod() === 'head') {
            return $this->response->setHeader('Content-Type','image/jpeg');
        }
        return $this->response->setHeader('Content-Type','image/jpeg')->setBody(file_get_contents($filePath));
    }

    public function delete($id = null): ResponseInterface
    {
        $model = new \App\Models\MediaModel();
        $row = $model->find($id);
        if (! $row) return $this->failNotFound('File không tồn tại');
        $userId = service('auth')->user()['id'] ?? null;
        $hasManage = service('permissions')->userHas($userId ?? 0,'media.manage');
        if (!$hasManage && (!$userId || (int)$row['owner_id'] !== (int)$userId)) {
            return $this->failForbidden('Không có quyền xóa file');
        }
        $model->delete($id); // soft delete
        audit_event('media.soft_delete',[ 'user_id'=>$userId,'id'=>$id ]);
        return $this->respond(['soft_deleted'=>true]);
    }

    public function restore($id = null): ResponseInterface
    {
        $model = new \App\Models\MediaModel();
        $row = $model->withDeleted()->find($id);
        if (! $row || $row['deleted_at'] === null) return $this->failNotFound('File chưa được xóa mềm');
        $userId = service('auth')->user()['id'] ?? null;
        $hasManage = service('permissions')->userHas($userId ?? 0,'media.manage');
        if (!$hasManage && (!$userId || (int)$row['owner_id'] !== (int)$userId)) {
            return $this->failForbidden('Không có quyền khôi phục file');
        }
        // restore by updating deleted_at null
        $model->update($id,['deleted_at'=>null]);
        audit_event('media.restore',[ 'user_id'=>$userId,'id'=>$id ]);
        return $this->respond(['restored'=>true]);
    }

    public function search(): ResponseInterface
    {
        $req = service('request');
        $query = (string)$req->getGet('q');
        if (empty($query)) {
            return $this->failValidationErrors('Tham số tìm kiếm "q" là bắt buộc');
        }
        
        $ownerId = service('auth')->user()['id'] ?? null;
        $limit = (int)($req->getGet('limit') ?? 25);
        
        $results = $this->media->search($query, $ownerId, $limit);
        
        foreach ($results as &$item) {
            $item['url'] = $this->media->url($item);
            $item['variants'] = $this->media->getVariants($item);
        }
        
        return $this->respond(['items' => $results]);
    }

    public function stats(): ResponseInterface
    {
        $userId = service('auth')->user()['id'] ?? null;
        $hasManage = service('permissions')->userHas($userId ?? 0, 'media.manage');
        
        if (!$hasManage) {
            return $this->failForbidden('No permission to view media statistics');
        }
        
        $stats = $this->media->getStats();
        
        return $this->respond($stats);
    }

    public function move($id = null): ResponseInterface
    {
        $req = service('request');
        $folder = (string)$req->getPost('folder');
        if (empty($folder)) {
            return $this->failValidationErrors('Folder parameter is required');
        }
        
        $userId = service('auth')->user()['id'] ?? null;
        $hasManage = service('permissions')->userHas($userId ?? 0, 'media.manage');
        
        if (!$hasManage) {
            return $this->failForbidden('No permission to move media');
        }
        
        $success = $this->media->move($id, $folder);
        
        if ($success) {
            audit_event('media.move', ['user_id' => $userId, 'id' => $id, 'folder' => $folder]);
            return $this->respond(['moved' => true]);
        }
        
        return $this->failServerError('Failed to move media');
    }

    public function hardDelete($id = null): ResponseInterface
    {
        $userId = service('auth')->user()['id'] ?? null;
        $hasManage = service('permissions')->userHas($userId ?? 0, 'media.manage');
        
        if (!$hasManage) {
            return $this->failForbidden('No permission to permanently delete media');
        }
        
        $success = $this->media->delete($id);
        
        if ($success) {
            audit_event('media.hard_delete', ['user_id' => $userId, 'id' => $id]);
            return $this->respond(['deleted' => true]);
        }
        
        return $this->failNotFound('Media not found');
    }

    public function bulkDelete(): ResponseInterface
    {
        $req = service('request');
        $ids = $req->getPost('ids');
        if (!is_array($ids) || empty($ids)) {
            return $this->failValidationErrors('IDs array is required');
        }
        
        $userId = service('auth')->user()['id'] ?? null;
        $hasManage = service('permissions')->userHas($userId ?? 0, 'media.manage');
        
        if (!$hasManage) {
            return $this->failForbidden('No permission to delete media');
        }
        
        $deleted = 0;
        $errors = 0;
        
        foreach ($ids as $id) {
            try {
                $this->media->delete($id);
                $deleted++;
            } catch (\Exception $e) {
                $errors++;
            }
        }
        
        audit_event('media.bulk_delete', ['user_id' => $userId, 'count' => $deleted]);
        
        return $this->respond([
            'deleted' => $deleted,
            'errors' => $errors
        ]);
    }

    public function download($id = null): ResponseInterface
    {
        $model = new \App\Models\MediaModel();
        $row = $model->find($id);
        
        if (!$row) {
            return $this->failNotFound();
        }
        
        $userId = service('auth')->user()['id'] ?? null;
        $currentUserId = service('auth')->user()['id'] ?? null;
        
        if ($currentUserId && (int)$row['owner_id'] !== (int)$currentUserId) {
            $hasManage = service('permissions')->userHas($userId ?? 0, 'media.manage');
            if (!$hasManage) {
                return $this->failForbidden('Not owner');
            }
        }
        
        $filePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $row['path'];
        
        if (!file_exists($filePath)) {
            return $this->failNotFound('File not found on disk');
        }
        
        return $this->response
            ->setHeader('Content-Type', $row['mime'])
            ->setHeader('Content-Disposition', 'attachment; filename="' . $row['original_name'] . '"')
            ->setBody(file_get_contents($filePath));
    }
}
