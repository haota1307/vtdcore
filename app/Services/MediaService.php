<?php
namespace App\Services;

use App\Models\MediaModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\I18n\Time;
use Config\App;

class MediaService
{
    private MediaModel $media;
    private string $uploadRoot;

    public function __construct()
    {
        $this->media = new MediaModel();
        $config = config(App::class);
        $this->uploadRoot = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR; // store in writable/uploads
        if (! is_dir($this->uploadRoot)) {
            mkdir($this->uploadRoot, 0775, true);
        }
    }

    public function store(UploadedFile $file, ?int $ownerId = null): array
    {
        if (! $file->isValid()) {
            throw new \RuntimeException($file->getErrorString());
        }
        // MIME whitelist from config
        $mediaCfg = config(\Config\Media::class);
        $allowedList = $mediaCfg->allowedMimes ?? [];
        $mimeCheck = $file->getMimeType();
        if (!in_array($mimeCheck, $allowedList, true)) {
            throw new \DomainException('unsupported_mime:'.$mimeCheck);
        }
        // Temporary scan file for viruses (ClamAV). If infected, reject.
        $scanner = service('virusScanner');
        $scanStatus = null;
        if (method_exists($scanner,'scanPathDetailed')) {
            $detail = $scanner->scanPathDetailed($file->getTempName());
            $scanStatus = $detail['reason'];
            $clean = $detail['clean'] ?? false;
            if ($detail['clean'] === null) {
                $clean = $scanner->scanPath($file->getTempName()); // fallback bool
            }
            if (!$clean) {
                if (function_exists('audit_event')) {
                    audit_event('media.upload.infected', [
                        'user_id' => $ownerId,
                        'filename' => $file->getClientName(),
                        'mime' => $mimeCheck,
                    ]);
                }
                throw new \DomainException('infected_file');
            }
            if (function_exists('audit_event')) {
                audit_event('media.upload.clean', [
                    'user_id' => $ownerId,
                    'filename' => $file->getClientName(),
                    'mime' => $mimeCheck,
                ]);
            }
        }
        $content = file_get_contents($file->getTempName());
        $hash = sha1($content);
        // Deduplicate (optional): if hash exists, return existing
        $existing = $this->media->where('hash',$hash)->first();
        if ($existing) {
            return $existing;
        }
        $ext = $file->getExtension();
        $subdir = date('Y/m');
        $targetDir = $this->uploadRoot . $subdir;
        if (! is_dir($targetDir)) { mkdir($targetDir, 0775, true); }
        $filename = substr($hash,0,10) . ($ext?'.'.$ext:'');
        $relativePath = $subdir . '/' . $filename;
        $file->move($targetDir, $filename, true);
        $mime = $file->getMimeType();
        [$width,$height] = $this->maybeImageDimensions($targetDir . DIRECTORY_SEPARATOR . $filename, $mime);
        $variants = null;
        // Generate image variants
        if ($width && $height && function_exists('imagecreatetruecolor')) {
            try {
                $base = substr($hash,0,10);
                $origPath = $targetDir . DIRECTORY_SEPARATOR . $filename;
                $thumbRel = $subdir . '/' . $base . '_thumb.jpg';
                $smallRel = $subdir . '/' . $base . '_small.jpg';
                $largeRel = $subdir . '/' . $base . '_large.jpg';
                $this->generateThumbnail($origPath, $targetDir . DIRECTORY_SEPARATOR . $base . '_thumb.jpg', 200, 200);
                $this->generateThumbnail($origPath, $targetDir . DIRECTORY_SEPARATOR . $base . '_small.jpg', 640, 640);
                $this->generateThumbnail($origPath, $targetDir . DIRECTORY_SEPARATOR . $base . '_large.jpg', 1280, 1280);
                $variants = json_encode([
                    'thumb'=>$thumbRel,
                    'small'=>$smallRel,
                    'large'=>$largeRel,
                ]);
            } catch (\Throwable $e) { /* ignore */ }
        }
        $record = [
            'disk'=>'local',
            'path'=>$relativePath,
            'original_name'=>$file->getClientName(),
            'mime'=>$mime,
            'size'=>$file->getSize(),
            'hash'=>$hash,
            'width'=>$width,
            'height'=>$height,
            'variants'=>$variants,
            'owner_id'=>$ownerId,
            'created_at'=>Time::now()->toDateTimeString(),
            'scan_status'=>$scanStatus,
        ];
        $id = $this->media->insert($record);
        $record['id'] = $id;
    if (function_exists('audit_event')) { audit_event('media.upload',[ 'user_id'=>$ownerId,'id'=>$id,'path'=>$relativePath,'scan_status'=>$scanStatus ]); }
        return $record;
    }

    public function url(array $media): string
    {
        // Basic local URL mapping via front controller path; adjust if using CDN
        return '/uploads/' . $media['path'];
    }

    /**
     * Store file already on disk (assembled chunk upload)
     */
    public function storeFromPath(string $filePath, string $originalName, ?int $ownerId, ?string $forcedMime = null): array
    {
        if (!is_file($filePath)) throw new \RuntimeException('file_missing');
        $mediaCfg = config(\Config\Media::class);
        $allowedList = $mediaCfg->allowedMimes ?? [];
        $mime = $forcedMime ?: mime_content_type($filePath);
        if (!in_array($mime, $allowedList, true)) throw new \DomainException('unsupported_mime:'.$mime);
        $content = file_get_contents($filePath);
        $hash = sha1($content);
        $existing = $this->media->where('hash',$hash)->first();
        if ($existing) { return $existing; }
        $subdir = date('Y/m');
        $targetDir = $this->uploadRoot . $subdir;
        if (!is_dir($targetDir)) mkdir($targetDir,0775,true);
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $filename = substr($hash,0,10) . ($ext?'.'.$ext:'');
        $relativePath = $subdir . '/' . $filename;
        rename($filePath, $targetDir . DIRECTORY_SEPARATOR . $filename);
        [$width,$height] = $this->maybeImageDimensions($targetDir . DIRECTORY_SEPARATOR . $filename, $mime);
        $record = [
            'disk'=>'local','path'=>$relativePath,'original_name'=>$originalName,'mime'=>$mime,'size'=>filesize($targetDir . DIRECTORY_SEPARATOR . $filename),'hash'=>$hash,'width'=>$width,'height'=>$height,'variants'=>null,'owner_id'=>$ownerId,'created_at'=>Time::now()->toDateTimeString(),'scan_status'=>null
        ];
        $id = $this->media->insert($record); $record['id']=$id;
        if (function_exists('audit_event')) audit_event('media.upload.chunked',['user_id'=>$ownerId,'id'=>$id]);
        return $record;
    }

    private function maybeImageDimensions(string $path, string $mime): array
    {
        if (str_starts_with($mime, 'image/')) {
            $info = @getimagesize($path);
            if ($info) { return [$info[0], $info[1]]; }
        }
        return [null,null];
    }

    private function generateThumbnail(string $src, string $dest, int $maxW, int $maxH): void
    {
        $info = @getimagesize($src);
        if (! $info) return;
        [$w,$h,$type] = $info;
        $ratio = min($maxW/$w, $maxH/$h);
        $newW = (int)($w*$ratio); $newH = (int)($h*$ratio);
        $srcImg = match($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($src),
            IMAGETYPE_PNG => imagecreatefrompng($src),
            IMAGETYPE_GIF => imagecreatefromgif($src),
            default => null,
        };
        if (! $srcImg) return;
        $dst = imagecreatetruecolor($newW,$newH);
        imagecopyresampled($dst,$srcImg,0,0,0,0,$newW,$newH,$w,$h);
        imagejpeg($dst,$dest,82);
        imagedestroy($dst); imagedestroy($srcImg);
    }

    /**
     * Get media statistics
     */
    public function getStats(): array
    {
        $totalStats = $this->media->select('COUNT(*) as count, SUM(size) as total_size')
                                 ->first();
        
        $mimeStats = $this->media->select('mime, COUNT(*) as count, SUM(size) as size')
                                ->groupBy('mime')
                                ->orderBy('size', 'DESC')
                                ->findAll();
        
        $scanStats = $this->media->select('scan_status, COUNT(*) as count')
                                ->groupBy('scan_status')
                                ->findAll();
        
        return [
            'total' => [
                'files' => (int)($totalStats['count'] ?? 0),
                'size' => (int)($totalStats['total_size'] ?? 0)
            ],
            'by_mime' => $mimeStats,
            'by_scan_status' => $scanStats
        ];
    }

    /**
     * Search media files
     */
    public function search(string $query, ?int $ownerId = null, int $limit = 25): array
    {
        $model = $this->media;
        
        if ($ownerId) {
            $model->where('owner_id', $ownerId);
        }
        
        $model->groupStart()
              ->like('original_name', $query)
              ->orLike('path', $query)
              ->groupEnd();
        
        return $model->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get media by folder
     */
    public function getByFolder(string $folder, ?int $ownerId = null, int $limit = 25): array
    {
        $model = $this->media;
        
        if ($ownerId) {
            $model->where('owner_id', $ownerId);
        }
        
        $model->like('path', $folder . '/%', 'after');
        
        return $model->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get media variants URLs
     */
    public function getVariants(array $media): array
    {
        if (empty($media['variants'])) {
            return [];
        }
        
        $variants = json_decode($media['variants'], true);
        $result = [];
        
        foreach ($variants as $type => $path) {
            $result[$type] = '/uploads/' . $path;
        }
        
        return $result;
    }

    /**
     * Delete media and all its variants
     */
    public function delete(int $id): bool
    {
        $media = $this->media->find($id);
        if (!$media) {
            return false;
        }
        
        // Delete main file
        $filePath = $this->uploadRoot . $media['path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Delete variants
        if (!empty($media['variants'])) {
            $variants = json_decode($media['variants'], true);
            foreach ($variants as $path) {
                $variantPath = $this->uploadRoot . $path;
                if (file_exists($variantPath)) {
                    unlink($variantPath);
                }
            }
        }
        
        // Delete from database
        return $this->media->delete($id);
    }

    /**
     * Move media to different folder
     */
    public function move(int $id, string $newFolder): bool
    {
        $media = $this->media->find($id);
        if (!$media) {
            return false;
        }
        
        $oldPath = $this->uploadRoot . $media['path'];
        $newPath = $this->uploadRoot . $newFolder . '/' . basename($media['path']);
        
        if (!file_exists($oldPath)) {
            return false;
        }
        
        // Create new directory if it doesn't exist
        $newDir = dirname($newPath);
        if (!is_dir($newDir)) {
            mkdir($newDir, 0775, true);
        }
        
        // Move file
        if (rename($oldPath, $newPath)) {
            // Update database
            $newRelativePath = $newFolder . '/' . basename($media['path']);
            $this->media->update($id, ['path' => $newRelativePath]);
            
            // Move variants if they exist
            if (!empty($media['variants'])) {
                $variants = json_decode($media['variants'], true);
                $newVariants = [];
                
                foreach ($variants as $type => $path) {
                    $oldVariantPath = $this->uploadRoot . $path;
                    $newVariantPath = $this->uploadRoot . $newFolder . '/' . basename($path);
                    
                    if (file_exists($oldVariantPath)) {
                        rename($oldVariantPath, $newVariantPath);
                        $newVariants[$type] = $newFolder . '/' . basename($path);
                    }
                }
                
                if (!empty($newVariants)) {
                    $this->media->update($id, ['variants' => json_encode($newVariants)]);
                }
            }
            
            return true;
        }
        
        return false;
    }

    /**
     * Generate missing thumbnails for existing images
     */
    public function generateMissingThumbnails(): array
    {
        $images = $this->media->where('mime LIKE', 'image/%')
                              ->where('variants IS NULL')
                              ->where('width IS NOT NULL')
                              ->where('height IS NOT NULL')
                              ->findAll();
        
        $generated = 0;
        $errors = 0;
        
        foreach ($images as $image) {
            try {
                $filePath = $this->uploadRoot . $image['path'];
                if (!file_exists($filePath)) {
                    continue;
                }
                
                $subdir = dirname($image['path']);
                $base = substr($image['hash'], 0, 10);
                
                $thumbRel = $subdir . '/' . $base . '_thumb.jpg';
                $smallRel = $subdir . '/' . $base . '_small.jpg';
                $largeRel = $subdir . '/' . $base . '_large.jpg';
                
                $targetDir = $this->uploadRoot . $subdir;
                
                $this->generateThumbnail($filePath, $targetDir . DIRECTORY_SEPARATOR . $base . '_thumb.jpg', 200, 200);
                $this->generateThumbnail($filePath, $targetDir . DIRECTORY_SEPARATOR . $base . '_small.jpg', 640, 640);
                $this->generateThumbnail($filePath, $targetDir . DIRECTORY_SEPARATOR . $base . '_large.jpg', 1280, 1280);
                
                $variants = json_encode([
                    'thumb' => $thumbRel,
                    'small' => $smallRel,
                    'large' => $largeRel,
                ]);
                
                $this->media->update($image['id'], ['variants' => $variants]);
                $generated++;
                
            } catch (\Exception $e) {
                $errors++;
            }
        }
        
        return [
            'generated' => $generated,
            'errors' => $errors
        ];
    }
}
