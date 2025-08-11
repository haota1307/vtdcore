
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Media Management</h4>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-soft-primary text-primary rounded fs-3">
                                        <i class="ri-file-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Total Files</p>
                                <h4 class="mb-0"><?= number_format($stats['total']['files'] ?? 0) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-soft-success text-success rounded fs-3">
                                        <i class="ri-hard-drive-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Total Size</p>
                                <h4 class="mb-0"><?= format_file_size($stats['total']['size'] ?? 0) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-soft-warning text-warning rounded fs-3">
                                        <i class="ri-image-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Images</p>
                                <h4 class="mb-0"><?= number_format($imageCount ?? 0) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-soft-info text-info rounded fs-3">
                                        <i class="ri-file-text-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Documents</p>
                                <h4 class="mb-0"><?= number_format($documentCount ?? 0) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control" id="searchInput" placeholder="Search files...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">File Type</label>
                                <select class="form-select" id="mimeFilter">
                                    <option value="">All Types</option>
                                    <option value="image">Images</option>
                                    <option value="video">Videos</option>
                                    <option value="audio">Audio</option>
                                    <option value="document">Documents</option>
                                    <option value="archive">Archives</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Scan Status</label>
                                <select class="form-select" id="scanFilter">
                                    <option value="">All Status</option>
                                    <option value="clean">Clean</option>
                                    <option value="infected">Infected</option>
                                    <option value="not_scanned">Not Scanned</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Folder</label>
                                <select class="form-select" id="folderFilter">
                                    <option value="">All Folders</option>
                                    <?php foreach (array_slice($folders, 0, 20) as $folder): ?>
                                        <option value="<?= $folder ?>"><?= $folder ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary" id="uploadBtn">
                                        <i class="ri-upload-line me-1"></i> Upload
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" id="bulkDeleteBtn" style="display: none;">
                                        <i class="ri-delete-bin-line me-1"></i> Delete Selected
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Media Grid -->
        <div class="row" id="mediaGrid">
            <?php foreach (array_slice($items, 0, 25) as $item): ?>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 media-item" 
                     data-mime="<?= $item['mime'] ?>" 
                     data-scan="<?= $item['scan_status'] ?? 'not_scanned' ?>" 
                     data-folder="<?= $item['folder'] ?? '' ?>">
                    <div class="card">
                        <div class="card-body p-2">
                            <div class="text-center">
                                <?php if (strpos($item['mime'], 'image/') === 0): ?>
                                    <img src="<?= base_url('uploads/' . $item['path']) ?>" alt="<?= htmlspecialchars($item['original_name']) ?>" 
                                         class="img-fluid rounded" style="max-height: 80px; max-width: 100%;">
                                <?php else: ?>
                                    <div class="bg-light rounded p-2 mb-2">
                                        <i class="<?= get_file_icon($item['mime']) ?> fs-2 text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <h6 class="mb-1 text-truncate small" title="<?= htmlspecialchars($item['original_name']) ?>">
                                    <?= htmlspecialchars($item['original_name']) ?>
                                </h6>
                                <p class="text-muted small mb-1"><?= format_file_size($item['size']) ?></p>
                                
                                <?php 
                                $status = $item['scan_status'] ?? 'not_scanned';
                                $badgeClass = $status === 'clean' ? 'bg-success' : ($status === 'infected' ? 'bg-danger' : 'bg-light text-dark');
                                $badgeText = $status === 'clean' ? 'Clean' : ($status === 'infected' ? 'Infected' : 'Not Scanned');
                                ?>
                                <div class="mb-2">
                                    <span class="badge <?= $badgeClass ?>"><?= $badgeText ?></span>
                                </div>
                                
                                <div class="btn-group btn-group-sm w-100">
                                    <button type="button" class="btn btn-outline-primary btn-sm" 
                                            onclick="downloadFile(<?= $item['id'] ?>)">
                                        <i class="ri-download-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                            onclick="deleteFile(<?= $item['id'] ?>)">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-2">
                            <div class="form-check">
                                <input class="form-check-input media-checkbox" type="checkbox" value="<?= $item['id'] ?>">
                                <label class="form-check-label small">Select</label>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if (isset($meta) && $meta['total'] > $meta['per_page']): ?>
            <div class="row">
                <div class="col-12">
                    <nav aria-label="Media pagination">
                        <?= $pagerObj->links() ?>
                    </nav>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Files</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Select Files</label>
                        <input type="file" class="form-control" id="fileInput" multiple accept="*/*">
                        <div class="form-text">Maximum file size: 100MB per file</div>
                    </div>
                    <div id="uploadProgress" style="display: none;">
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                        <div id="uploadStatus" class="text-muted small"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="startUpload">Upload Files</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the selected files? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const mimeFilter = document.getElementById('mimeFilter');
    const scanFilter = document.getElementById('scanFilter');
    const folderFilter = document.getElementById('folderFilter');
    const mediaItems = document.querySelectorAll('.media-item');
    
    function filterMedia() {
        const searchTerm = searchInput.value.toLowerCase();
        const mimeType = mimeFilter.value;
        const scanStatus = scanFilter.value;
        const folder = folderFilter.value;
        
        mediaItems.forEach(item => {
            const name = item.querySelector('h6').textContent.toLowerCase();
            const mime = item.dataset.mime;
            const scan = item.dataset.scan;
            const itemFolder = item.dataset.folder;
            
            let show = true;
            
            if (searchTerm && !name.includes(searchTerm)) {
                show = false;
            }
            
            if (mimeType && !mime.startsWith(mimeType + '/')) {
                show = false;
            }
            
            if (scanStatus && scan !== scanStatus) {
                show = false;
            }
            
            if (folder && itemFolder !== folder) {
                show = false;
            }
            
            item.style.display = show ? 'block' : 'none';
        });
    }
    
    searchInput.addEventListener('input', filterMedia);
    mimeFilter.addEventListener('change', filterMedia);
    scanFilter.addEventListener('change', filterMedia);
    folderFilter.addEventListener('change', filterMedia);
    
    // Upload functionality
    const uploadBtn = document.getElementById('uploadBtn');
    const uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
    const startUploadBtn = document.getElementById('startUpload');
    const fileInput = document.getElementById('fileInput');
    const uploadProgress = document.getElementById('uploadProgress');
    const uploadStatus = document.getElementById('uploadStatus');
    
    uploadBtn.addEventListener('click', () => {
        uploadModal.show();
    });
    
    startUploadBtn.addEventListener('click', () => {
        const files = fileInput.files;
        if (files.length === 0) {
            alert('Please select files to upload');
            return;
        }
        
        uploadProgress.style.display = 'block';
        startUploadBtn.disabled = true;
        
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('file', files[i]);
        }
        
        fetch('/api/media/upload', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.media) {
                uploadStatus.textContent = 'Upload successful!';
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                uploadStatus.textContent = 'Upload failed';
            }
        })
        .catch(error => {
            uploadStatus.textContent = 'Upload failed: ' + error.message;
        })
        .finally(() => {
            startUploadBtn.disabled = false;
        });
    });
    
    // Delete functionality
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const checkboxes = document.querySelectorAll('.media-checkbox');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    
    function updateBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.media-checkbox:checked');
        bulkDeleteBtn.style.display = checkedBoxes.length > 0 ? 'inline-block' : 'none';
    }
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkDeleteButton);
    });
    
    bulkDeleteBtn.addEventListener('click', () => {
        deleteModal.show();
    });
    
    confirmDeleteBtn.addEventListener('click', () => {
        const checkedBoxes = document.querySelectorAll('.media-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        
        fetch('/api/media/bulk-delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(response => response.json())
        .then(data => {
            if (data.deleted > 0) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Delete failed:', error);
        });
        
        deleteModal.hide();
    });
});

function downloadFile(id) {
    window.open(`/api/media/item/${id}/download`, '_blank');
}

function deleteFile(id) {
    if (confirm('Are you sure you want to delete this file?')) {
        fetch(`/api/media/item/${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.soft_deleted) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Delete failed:', error);
        });
    }
}
</script>
<?= $this->endSection() ?>
