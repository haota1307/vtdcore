# Enhanced Media Management System

## Overview

The enhanced media management system provides comprehensive file upload, storage, and management capabilities with advanced features including virus scanning, thumbnail generation, and bulk operations.

## Features

### Core Functionality
- **File Upload**: Support for single and chunked uploads up to 1GB
- **Virus Scanning**: Integration with ClamAV for file security
- **Thumbnail Generation**: Automatic thumbnail creation for images
- **File Organization**: Folder-based organization system
- **Search & Filter**: Advanced search and filtering capabilities
- **Bulk Operations**: Bulk delete and management features

### Security Features
- **MIME Type Validation**: Whitelist-based file type validation
- **Virus Scanning**: Real-time virus scanning with ClamAV
- **Access Control**: Role-based permissions for media management
- **Audit Logging**: Comprehensive audit trail for all operations

### File Processing
- **Image Processing**: Automatic thumbnail generation (thumb, small, large)
- **File Deduplication**: SHA1-based duplicate detection
- **Metadata Extraction**: Automatic extraction of image dimensions
- **Variant Management**: Multiple size variants for images

## Configuration

### Media Configuration (`app/Config/Media.php`)

```php
// Allowed MIME types
public array $allowedMimes = [
    'image/jpeg', 'image/png', 'image/gif', 'image/webp',
    'application/pdf', 'application/msword',
    'video/mp4', 'audio/mpeg',
    // ... more types
];

// File size limits
public int $maxFileSize = 104857600; // 100MB
public int $maxChunkedSize = 1073741824; // 1GB

// Image processing settings
public array $imageSettings = [
    'thumb' => ['width' => 200, 'height' => 200, 'quality' => 82],
    'small' => ['width' => 640, 'height' => 640, 'quality' => 85],
    'large' => ['width' => 1280, 'height' => 1280, 'quality' => 90]
];
```

## API Endpoints

### Upload Endpoints
- `POST /media/upload` - Single file upload
- `POST /media/chunk/init` - Initialize chunked upload
- `POST /media/chunk/put` - Upload chunk

### Management Endpoints
- `GET /media/list` - List media files
- `GET /media/item/{id}` - Get media details
- `DELETE /media/item/{id}` - Delete media (soft delete)
- `POST /media/item/{id}/restore` - Restore deleted media
- `GET /media/search` - Search media files
- `GET /media/stats` - Get media statistics

### Advanced Endpoints
- `POST /media/item/{id}/move` - Move media to different folder
- `DELETE /media/item/{id}/permanent` - Permanently delete media
- `POST /media/bulk-delete` - Bulk delete media
- `GET /media/item/{id}/download` - Download media file

### Thumbnail Endpoints
- `GET /media/item/{id}/thumb` - Get thumbnail
- `GET /media/item/{id}/variant/{name}` - Get specific variant

## CLI Commands

### Media Management Commands

```bash
# Show media statistics
php spark media:stats

# Rescan all media for viruses
php spark media:rescan [limit] [offset]

# Clean up orphaned files and duplicates
php spark media:cleanup [--dry-run]

# Generate missing thumbnails
php spark media:generate-thumbnails [--dry-run]

# Purge soft-deleted files older than X days
php spark media:purge [days]
```

### Command Examples

```bash
# Show detailed statistics
php spark media:stats

# Rescan 50 files starting from offset 100
php spark media:rescan 50 100

# Dry run cleanup to see what would be deleted
php spark media:cleanup --dry-run

# Generate thumbnails for existing images
php spark media:generate-thumbnails

# Purge files deleted more than 30 days ago
php spark media:purge 30
```

## Database Schema

### Media Table Structure

```sql
CREATE TABLE media (
    id INT PRIMARY KEY AUTO_INCREMENT,
    disk VARCHAR(50) DEFAULT 'local',
    path VARCHAR(500) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    mime VARCHAR(100) NOT NULL,
    size BIGINT NOT NULL,
    hash VARCHAR(40) NOT NULL,
    width INT NULL,
    height INT NULL,
    variants JSON NULL,
    owner_id INT NULL,
    created_at DATETIME NOT NULL,
    deleted_at DATETIME NULL,
    scan_status VARCHAR(20) NULL,
    INDEX idx_owner (owner_id),
    INDEX idx_hash (hash),
    INDEX idx_path (path),
    INDEX idx_deleted (deleted_at)
);
```

## Helper Functions

### Media URL Helpers

```php
// Generate media URL
media_url($path)

// Generate thumbnail URL
media_thumb_url($media)

// Generate variant URL
media_variant_url($media, $variant)
```

### File Type Helpers

```php
// Check file types
is_image($mime)
is_video($mime)
is_audio($mime)
is_document($mime)
is_archive($mime)

// Get file icon
get_file_icon($mime)

// Format file size
format_file_size($bytes)
```

### Display Helpers

```php
// Get media preview HTML
get_media_preview($media)

// Get scan status badge
get_scan_status_badge($status)
```

## Service Methods

### MediaService Class

```php
// Core methods
$mediaService->store($file, $ownerId)
$mediaService->url($media)
$mediaService->delete($id)
$mediaService->move($id, $folder)

// Statistics and search
$mediaService->getStats()
$mediaService->search($query, $ownerId, $limit)
$mediaService->getByFolder($folder, $ownerId, $limit)

// Variants and thumbnails
$mediaService->getVariants($media)
$mediaService->generateMissingThumbnails()
```

## Security Considerations

### File Upload Security
- MIME type validation against whitelist
- File size limits enforced
- Virus scanning with ClamAV integration
- SHA1 hash-based deduplication
- Secure file naming and storage

### Access Control
- Role-based permissions (`media.manage`, `admin.media.manage`)
- Owner-based access control
- Bearer token authentication support
- Audit logging for all operations

### Virus Scanning
- Real-time scanning during upload
- Quarantine support for infected files
- Rescan capabilities for existing files
- Detailed scan status tracking

## Performance Optimizations

### File Storage
- Organized by year/month folders
- Hash-based file naming for deduplication
- Efficient thumbnail generation
- CDN-ready URL structure

### Database Optimization
- Indexed queries for common operations
- Soft delete for data recovery
- Efficient pagination support
- Optimized search queries

## Usage Examples

### Upload File via API

```javascript
const formData = new FormData();
formData.append('file', fileInput.files[0]);

fetch('/media/upload', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => {
    console.log('Uploaded:', data.media);
});
```

### Search Media Files

```javascript
fetch('/media/search?q=document&limit=10')
.then(response => response.json())
.then(data => {
    console.log('Search results:', data.items);
});
```

### Get Media Statistics

```javascript
fetch('/media/stats')
.then(response => response.json())
.then(data => {
    console.log('Total files:', data.total.files);
    console.log('Total size:', data.total.size);
});
```

## Error Handling

### Common Error Responses

```json
{
    "error": "validation_errors",
    "messages": ["Missing file field 'file'"]
}

{
    "error": "unsupported_mime",
    "message": "File type not allowed"
}

{
    "error": "infected_file",
    "message": "File contains malware"
}
```

## Monitoring and Maintenance

### Regular Maintenance Tasks
1. Run `media:cleanup` weekly to remove orphaned files
2. Run `media:rescan` monthly to update virus scan status
3. Run `media:generate-thumbnails` after system updates
4. Monitor storage usage with `media:stats`

### Performance Monitoring
- Track upload success rates
- Monitor virus scan performance
- Monitor storage growth patterns
- Track thumbnail generation success rates

## Troubleshooting

### Common Issues

1. **Upload Fails**: Check file size limits and MIME type restrictions
2. **Thumbnails Not Generated**: Verify GD extension is installed
3. **Virus Scan Fails**: Check ClamAV service is running
4. **Permission Denied**: Verify file permissions on upload directory

### Debug Commands

```bash
# Check media statistics
php spark media:stats

# Test virus scanner
php spark media:rescan 1

# Verify file integrity
php spark media:cleanup --dry-run
```

## Future Enhancements

### Planned Features
- Cloud storage integration (AWS S3, Google Cloud Storage)
- Advanced image processing (watermarks, filters)
- Video thumbnail generation
- File versioning system
- Advanced search with OCR
- Batch processing capabilities
- WebP conversion for better compression
- Metadata extraction for more file types
