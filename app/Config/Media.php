<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Media extends BaseConfig
{
    /**
     * Allowed MIME types for upload
     */
    public array $allowedMimes = [
        // Images
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml',
        'image/bmp',
        'image/tiff',
        
        // Documents
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
        
        // Archives
        'application/zip',
        'application/x-rar-compressed',
        'application/x-7z-compressed',
        'application/gzip',
        'application/x-tar',
        
        // Audio
        'audio/mpeg',
        'audio/wav',
        'audio/ogg',
        'audio/mp4',
        'audio/aac',
        'audio/flac',
        
        // Video
        'video/mp4',
        'video/webm',
        'video/ogg',
        'video/avi',
        'video/mov',
        'video/wmv',
        'video/flv',
        'video/mkv',
        
        // Other
        'application/json',
        'application/xml',
        'text/xml',
    ];

    /**
     * Maximum file size in bytes (default: 100MB)
     */
    public int $maxFileSize = 104857600;

    /**
     * Maximum upload size for chunked uploads (default: 1GB)
     */
    public int $maxChunkedSize = 1073741824;

    /**
     * Image processing settings
     */
    public array $imageSettings = [
        'thumb' => [
            'width' => 200,
            'height' => 200,
            'quality' => 82
        ],
        'small' => [
            'width' => 640,
            'height' => 640,
            'quality' => 85
        ],
        'large' => [
            'width' => 1280,
            'height' => 1280,
            'quality' => 90
        ]
    ];

    /**
     * Storage settings
     */
    public array $storage = [
        'disk' => 'local',
        'path' => 'uploads',
        'url_prefix' => '/uploads/'
    ];

    /**
     * Virus scanning settings
     */
    public array $virusScan = [
        'enabled' => true,
        'quarantine_infected' => true,
        'quarantine_path' => 'quarantine'
    ];

    /**
     * Retention settings for soft-deleted files (in days)
     */
    public int $retentionDays = 30;

    /**
     * Auto-cleanup settings
     */
    public array $cleanup = [
        'enabled' => true,
        'orphaned_files' => true,
        'duplicate_files' => true,
        'missing_thumbnails' => true
    ];
}
