<?php
namespace App\Controllers\Admin;

class MediaController extends AdminBaseController
{
    protected ?string $requiredPermission = 'admin.media.manage';

    public function index()
    {
        $req = service('request');
        $folder = trim((string)$req->getGet('folder'), '/');
        $mimeFilter = (string)$req->getGet('mime');
        $scanFilter = (string)$req->getGet('scan');
        $model = new \App\Models\MediaModel();
        
        // Apply filters
        if ($folder !== '') {
            $model->like('path', $folder . '/%', 'after');
        }
        if ($mimeFilter !== '') {
            if (str_contains($mimeFilter,'/')) {
                $model->where('mime', $mimeFilter);
            } else {
                $model->like('mime', $mimeFilter.'/%', 'after');
            }
        }
        if ($scanFilter !== '') {
            $model->where('scan_status', $scanFilter);
        }
        
        // Get paginated items
        $data = $this->paginate($model, 25);
        $items = $data['items'];
        
        // Add URLs to items
        foreach ($items as &$it) {
            if (isset($it['path'])) {
                $it['full_url'] = base_url('uploads/' . $it['path']);
                $it['folder'] = dirname($it['path']);
            }
        }
        
        // Get basic stats efficiently
        $mediaService = new \App\Services\MediaService();
        $stats = $mediaService->getStats();
        
        // Get folder list (limit to prevent memory issues)
        $folderList = $model->select('DISTINCT(path) as path')
                           ->where('path IS NOT NULL')
                           ->limit(1000)
                           ->findAll();
        $folders = [];
        foreach ($folderList as $item) {
            $folder = dirname($item['path']);
            if (!in_array($folder, $folders) && $folder !== '.' && $folder !== '') {
                $folders[] = $folder;
            }
        }
        sort($folders);
        
        // Get counts by type (use existing stats if available)
        $imageCount = $stats['by_mime']['image'] ?? 0;
        $documentCount = 0;
        foreach ($stats['by_mime'] as $mimeStat) {
            if (strpos($mimeStat['mime'], 'application/pdf') === 0 || 
                strpos($mimeStat['mime'], 'application/msword') === 0 ||
                strpos($mimeStat['mime'], 'application/vnd.openxmlformats-officedocument') === 0 ||
                strpos($mimeStat['mime'], 'text/') === 0) {
                $documentCount += $mimeStat['count'];
            }
        }
        
        return $this->render('files/velzon-manager', [
            'title' => 'Media Management',
            'items' => $items,
            'meta' => $data['pager'],
            'pagerObj' => $data['pagerObj'] ?? null,
            'currentFolder' => $folder,
            'folders' => $folders,
            'mimeFilter' => $mimeFilter,
            'scanFilter' => $scanFilter,
            'stats' => $stats,
            'imageCount' => $imageCount,
            'documentCount' => $documentCount,
        ]);
    }

    // Re-scan infected file (admin action)
    public function rescan($id)
    {
        $media = (new \App\Models\MediaModel())->find($id);
        if (!$media) return $this->failNotFound('File not found');
        if (!service('auth')->user() || !service('auth')->user()->can('admin.media.manage')) return $this->failForbidden('No permission');
        $scanner = service('virusScanner');
        $path = WRITEPATH.'uploads/'.$media['path'];
        $result = $scanner->scanFile($path);
        $status = $result ? 'clean' : 'infected';
        (new \App\Models\MediaModel())->update($id, ['scan_status'=>$status]);
        // Audit log (optional)
        if (class_exists('App\\Services\\AuditService')) {
            service('audit')->log('media.rescan', ['media_id'=>$id,'result'=>$status]);
        }
        return service('response')->setJSON(['id' => $id, 'scan_status' => $status]);
    }

    public function uploadForm()
    {
        if ($resp = $this->guard('admin.media.manage')) return $resp;
        
        return $this->render('media/upload', [
            'title' => 'Tải lên Media',
        ]);
    }
}
