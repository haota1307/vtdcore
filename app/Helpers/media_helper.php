<?php

if (!function_exists('media_url')) {
    /**
     * Generate media URL
     */
    function media_url($path): string
    {
        if (empty($path)) return '';
        return base_url('uploads/' . ltrim($path, '/'));
    }
}

if (!function_exists('media_thumb_url')) {
    /**
     * Generate media thumbnail URL
     */
    function media_thumb_url($media): string
    {
        if (empty($media) || empty($media['path'])) return '';
        
        if (empty($media['variants'])) {
            return media_url($media['path']);
        }
        
        $variants = json_decode($media['variants'], true);
        if (!$variants || !isset($variants['thumb'])) {
            return media_url($media['path']);
        }
        
        return media_url($variants['thumb']);
    }
}

if (!function_exists('format_file_size')) {
    /**
     * Format file size in human readable format
     */
    function format_file_size($bytes, $precision = 2): string
    {
        if ($bytes <= 0) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

if (!function_exists('get_file_icon')) {
    /**
     * Get file type icon based on MIME type
     */
    function get_file_icon($mime): string
    {
        if (empty($mime)) return 'ri-file-line';
        
        $icons = [
            'image/' => 'ri-image-2-line',
            'video/' => 'ri-file-video-line',
            'audio/' => 'ri-file-music-line',
            'application/pdf' => 'ri-file-pdf-line',
            'application/msword' => 'ri-file-word-line',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'ri-file-word-line',
            'application/vnd.ms-excel' => 'ri-file-excel-line',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'ri-file-excel-line',
            'application/vnd.ms-powerpoint' => 'ri-file-ppt-line',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'ri-file-ppt-line',
            'text/' => 'ri-file-text-line',
            'application/zip' => 'ri-file-zip-line',
            'application/x-rar-compressed' => 'ri-file-zip-line',
            'application/x-7z-compressed' => 'ri-file-zip-line',
            'application/gzip' => 'ri-file-zip-line',
            'application/x-tar' => 'ri-file-zip-line',
        ];
        
        foreach ($icons as $pattern => $icon) {
            if (strpos($mime, $pattern) === 0) {
                return $icon;
            }
        }
        
        return 'ri-file-line';
    }
}

if (!function_exists('is_image')) {
    /**
     * Check if file is an image
     */
    function is_image($mime): bool
    {
        return !empty($mime) && strpos($mime, 'image/') === 0;
    }
}

if (!function_exists('is_video')) {
    /**
     * Check if file is a video
     */
    function is_video($mime): bool
    {
        return !empty($mime) && strpos($mime, 'video/') === 0;
    }
}

if (!function_exists('is_audio')) {
    /**
     * Check if file is an audio file
     */
    function is_audio($mime): bool
    {
        return !empty($mime) && strpos($mime, 'audio/') === 0;
    }
}

if (!function_exists('is_document')) {
    /**
     * Check if file is a document
     */
    function is_document($mime): bool
    {
        if (empty($mime)) return false;
        
        $documentMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
            'text/csv',
            'text/html',
            'application/json',
            'application/xml',
            'text/xml'
        ];
        
        return in_array($mime, $documentMimes);
    }
}

if (!function_exists('is_archive')) {
    /**
     * Check if file is an archive
     */
    function is_archive($mime): bool
    {
        if (empty($mime)) return false;
        
        $archiveMimes = [
            'application/zip',
            'application/x-rar-compressed',
            'application/x-7z-compressed',
            'application/gzip',
            'application/x-tar'
        ];
        
        return in_array($mime, $archiveMimes);
    }
}

if (!function_exists('get_scan_status_badge')) {
    /**
     * Get scan status badge HTML
     */
    function get_scan_status_badge($status): string
    {
        $badges = [
            'clean' => '<span class="badge bg-success">Clean</span>',
            'infected' => '<span class="badge bg-danger">Infected</span>',
            'scanning' => '<span class="badge bg-warning">Scanning</span>',
            'error' => '<span class="badge bg-secondary">Error</span>',
            'not_scanned' => '<span class="badge bg-light text-dark">Not Scanned</span>'
        ];
        
        return $badges[$status] ?? $badges['not_scanned'];
    }
}
