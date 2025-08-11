<div class="page-content">
    <div class="container-fluid">
        <!-- Tiêu đề trang -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <h4 class="mb-sm-0 fw-bold">Tải lên Media</h4>
                        <p class="text-muted mb-0">Tải lên hình ảnh, video, tài liệu và các file khác</p>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= admin_url() ?>">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="<?= admin_url('media') ?>">Media</a></li>
                            <li class="breadcrumb-item active">Tải lên</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hướng dẫn nhanh -->
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i data-feather="info" class="text-info" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <h6 class="alert-heading mb-1">Hướng dẫn tải lên</h6>
                            <p class="mb-0">
                                • Kéo thả file vào khu vực bên dưới hoặc click để chọn file<br>
                                • Hỗ trợ nhiều file cùng lúc (tối đa 10 file)<br>
                                • Kích thước tối đa: <strong>100MB</strong> mỗi file<br>
                                • Định dạng hỗ trợ: Hình ảnh (JPG, PNG, GIF, SVG), Video (MP4, AVI, MOV), Tài liệu (PDF, DOC, XLS, PPT)
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Area -->
        <div class="row">
            <div class="col-12">
                <div class="card upload-card">
                    <div class="card-body">
                        <!-- Drop Zone -->
                        <div class="upload-dropzone" id="uploadDropzone">
                            <div class="upload-content">
                                <div class="upload-icon">
                                    <i data-feather="upload-cloud" style="width: 4rem; height: 4rem;"></i>
                                </div>
                                <h4 class="upload-title">Kéo thả file vào đây</h4>
                                <p class="upload-subtitle text-muted">hoặc click để chọn file từ máy tính</p>
                                <button type="button" class="btn btn-primary btn-lg" id="selectFilesBtn">
                                    <i data-feather="folder" class="me-2"></i>
                                    Chọn File
                                </button>
                                <input type="file" id="fileInput" multiple accept="*/*" style="display: none;">
                            </div>
                        </div>

                        <!-- File List -->
                        <div class="upload-files-list" id="filesList" style="display: none;">
                            <h5 class="mb-3">
                                <i data-feather="file" class="me-2"></i>
                                File đã chọn
                            </h5>
                            <div class="files-container" id="filesContainer">
                                <!-- Files will be populated here -->
                            </div>
                            <div class="upload-actions mt-4">
                                <button type="button" class="btn btn-success btn-lg me-3" id="startUploadBtn">
                                    <i data-feather="upload" class="me-2"></i>
                                    Bắt đầu tải lên
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-lg" id="clearAllBtn">
                                    <i data-feather="x" class="me-2"></i>
                                    Xóa tất cả
                                </button>
                            </div>
                        </div>

                        <!-- Upload Progress -->
                        <div class="upload-progress-section" id="progressSection" style="display: none;">
                            <h5 class="mb-3">
                                <i data-feather="activity" class="me-2"></i>
                                Tiến trình tải lên
                            </h5>
                            <div class="overall-progress mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="progress-label">Tổng tiến trình</span>
                                    <span class="progress-percent" id="overallPercent">0%</span>
                                </div>
                                <div class="progress progress-lg">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                         id="overallProgressBar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <div class="progress-info mt-2">
                                    <small class="text-muted" id="progressInfo">Chuẩn bị tải lên...</small>
                                </div>
                            </div>
                            <div class="individual-progress" id="individualProgress">
                                <!-- Individual file progress will be populated here -->
                            </div>
                        </div>

                        <!-- Upload Results -->
                        <div class="upload-results" id="resultsSection" style="display: none;">
                            <h5 class="mb-3">
                                <i data-feather="check-circle" class="me-2 text-success"></i>
                                Kết quả tải lên
                            </h5>
                            <div class="results-container" id="resultsContainer">
                                <!-- Results will be populated here -->
                            </div>
                            <div class="results-actions mt-4">
                                <a href="<?= admin_url('media') ?>" class="btn btn-primary">
                                    <i data-feather="folder" class="me-2"></i>
                                    Xem tất cả Media
                                </a>
                                <button type="button" class="btn btn-outline-secondary ms-2" id="uploadMoreBtn">
                                    <i data-feather="plus" class="me-2"></i>
                                    Tải lên thêm
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Tips -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card tips-card">
                    <div class="card-header bg-white">
                        <h6 class="card-title mb-0">
                            <i data-feather="lightbulb" class="me-2 text-warning"></i>
                            Mẹo tối ưu
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="tips-list mb-0">
                            <li>Nén hình ảnh để giảm dung lượng và tăng tốc độ tải</li>
                            <li>Đặt tên file có ý nghĩa để dễ quản lý</li>
                            <li>Sử dụng định dạng WebP cho hình ảnh web hiện đại</li>
                            <li>Tránh tải lên file có kích thước quá lớn</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card formats-card">
                    <div class="card-header bg-white">
                        <h6 class="card-title mb-0">
                            <i data-feather="file-text" class="me-2 text-info"></i>
                            Định dạng hỗ trợ
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="formats-grid">
                            <div class="format-group">
                                <strong>Hình ảnh:</strong>
                                <span class="format-tags">
                                    <span class="badge bg-primary-subtle text-primary">JPG</span>
                                    <span class="badge bg-primary-subtle text-primary">PNG</span>
                                    <span class="badge bg-primary-subtle text-primary">GIF</span>
                                    <span class="badge bg-primary-subtle text-primary">SVG</span>
                                    <span class="badge bg-primary-subtle text-primary">WebP</span>
                                </span>
                            </div>
                            <div class="format-group">
                                <strong>Video:</strong>
                                <span class="format-tags">
                                    <span class="badge bg-success-subtle text-success">MP4</span>
                                    <span class="badge bg-success-subtle text-success">AVI</span>
                                    <span class="badge bg-success-subtle text-success">MOV</span>
                                    <span class="badge bg-success-subtle text-success">WebM</span>
                                </span>
                            </div>
                            <div class="format-group">
                                <strong>Tài liệu:</strong>
                                <span class="format-tags">
                                    <span class="badge bg-warning-subtle text-warning">PDF</span>
                                    <span class="badge bg-warning-subtle text-warning">DOC</span>
                                    <span class="badge bg-warning-subtle text-warning">XLS</span>
                                    <span class="badge bg-warning-subtle text-warning">PPT</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Upload Card */
.upload-card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 12px;
}

/* Drop Zone */
.upload-dropzone {
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    padding: 3rem 2rem;
    text-align: center;
    background-color: #f8f9fc;
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-dropzone:hover,
.upload-dropzone.drag-over {
    border-color: #4e73df;
    background-color: rgba(78, 115, 223, 0.05);
}

.upload-dropzone.drag-over {
    border-style: solid;
    transform: scale(1.02);
}

.upload-content {
    pointer-events: none;
}

.upload-icon {
    color: #6c757d;
    margin-bottom: 1rem;
}

.upload-dropzone:hover .upload-icon,
.upload-dropzone.drag-over .upload-icon {
    color: #4e73df;
}

.upload-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.upload-subtitle {
    font-size: 1rem;
    margin-bottom: 2rem;
}

/* File List */
.files-container {
    max-height: 400px;
    overflow-y: auto;
}

.file-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    margin-bottom: 0.75rem;
    background-color: #fff;
    transition: all 0.2s ease;
}

.file-item:hover {
    border-color: #4e73df;
    box-shadow: 0 0.125rem 0.25rem rgba(78, 115, 223, 0.15);
}

.file-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.25rem;
}

.file-icon.image {
    background-color: rgba(28, 200, 138, 0.15);
    color: #1cc88a;
}

.file-icon.video {
    background-color: rgba(231, 74, 59, 0.15);
    color: #e74a3b;
}

.file-icon.document {
    background-color: rgba(246, 194, 62, 0.15);
    color: #f6c23e;
}

.file-icon.other {
    background-color: rgba(133, 135, 150, 0.15);
    color: #858796;
}

.file-info {
    flex: 1;
    min-width: 0;
}

.file-name {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.25rem;
    word-break: break-word;
}

.file-details {
    font-size: 0.875rem;
    color: #6c757d;
}

.file-actions {
    display: flex;
    gap: 0.5rem;
}

.file-progress {
    margin-top: 0.5rem;
}

.file-progress .progress {
    height: 4px;
}

/* Progress Section */
.progress-lg {
    height: 12px;
}

.progress-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.individual-file-progress {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border: 1px solid #e3e6f0;
    border-radius: 6px;
    margin-bottom: 0.5rem;
}

.individual-file-progress .file-name {
    flex: 1;
    margin-right: 1rem;
    font-size: 0.875rem;
}

.individual-file-progress .progress {
    width: 200px;
    height: 6px;
    margin-right: 1rem;
}

.individual-file-progress .status {
    min-width: 60px;
    font-size: 0.8rem;
}

/* Results Section */
.result-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    margin-bottom: 0.75rem;
    background-color: #fff;
}

.result-item.success {
    border-color: #1cc88a;
    background-color: rgba(28, 200, 138, 0.05);
}

.result-item.error {
    border-color: #e74a3b;
    background-color: rgba(231, 74, 59, 0.05);
}

.result-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.result-icon.success {
    background-color: rgba(28, 200, 138, 0.15);
    color: #1cc88a;
}

.result-icon.error {
    background-color: rgba(231, 74, 59, 0.15);
    color: #e74a3b;
}

/* Tips and Formats Cards */
.tips-card,
.formats-card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 12px;
    height: 100%;
}

.tips-list {
    list-style: none;
    padding: 0;
}

.tips-list li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f1f1f1;
    position: relative;
    padding-left: 1.5rem;
}

.tips-list li:last-child {
    border-bottom: none;
}

.tips-list li::before {
    content: '•';
    color: #4e73df;
    font-weight: bold;
    position: absolute;
    left: 0;
}

.formats-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.format-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.format-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
}

.format-tags .badge {
    font-size: 0.75rem;
}

/* Responsive */
@media (max-width: 768px) {
    .upload-dropzone {
        padding: 2rem 1rem;
    }
    
    .file-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .file-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .individual-file-progress {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
    }
    
    .individual-file-progress .progress {
        width: 100%;
    }
    
    .formats-grid {
        gap: 0.75rem;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.upload-files-list,
.upload-progress-section,
.upload-results {
    animation: fadeInUp 0.3s ease-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace({ 'stroke-width': 1.5 });
    }

    // Elements
    const dropzone = document.getElementById('uploadDropzone');
    const fileInput = document.getElementById('fileInput');
    const selectFilesBtn = document.getElementById('selectFilesBtn');
    const filesList = document.getElementById('filesList');
    const filesContainer = document.getElementById('filesContainer');
    const startUploadBtn = document.getElementById('startUploadBtn');
    const clearAllBtn = document.getElementById('clearAllBtn');
    const progressSection = document.getElementById('progressSection');
    const resultsSection = document.getElementById('resultsSection');
    const uploadMoreBtn = document.getElementById('uploadMoreBtn');

    // State
    let selectedFiles = [];
    let uploadedFiles = [];

    // File size limit (100MB)
    const MAX_FILE_SIZE = 100 * 1024 * 1024;
    const MAX_FILES = 10;

    // Event Listeners
    selectFilesBtn.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', handleFileSelect);
    startUploadBtn.addEventListener('click', startUpload);
    clearAllBtn.addEventListener('click', clearAllFiles);
    uploadMoreBtn.addEventListener('click', resetUploadForm);

    // Drag and Drop
    dropzone.addEventListener('dragover', handleDragOver);
    dropzone.addEventListener('dragleave', handleDragLeave);
    dropzone.addEventListener('drop', handleDrop);
    dropzone.addEventListener('click', () => fileInput.click());

    function handleDragOver(e) {
        e.preventDefault();
        dropzone.classList.add('drag-over');
    }

    function handleDragLeave(e) {
        e.preventDefault();
        dropzone.classList.remove('drag-over');
    }

    function handleDrop(e) {
        e.preventDefault();
        dropzone.classList.remove('drag-over');
        const files = Array.from(e.dataTransfer.files);
        addFiles(files);
    }

    function handleFileSelect(e) {
        const files = Array.from(e.target.files);
        addFiles(files);
        // Reset input
        e.target.value = '';
    }

    function addFiles(files) {
        // Filter valid files
        const validFiles = files.filter(file => {
            if (file.size > MAX_FILE_SIZE) {
                showNotification(`File "${file.name}" quá lớn (tối đa 100MB)`, 'error');
                return false;
            }
            if (selectedFiles.length >= MAX_FILES) {
                showNotification(`Chỉ được chọn tối đa ${MAX_FILES} file`, 'error');
                return false;
            }
            return true;
        });

        // Add to selected files
        validFiles.forEach(file => {
            if (selectedFiles.length < MAX_FILES) {
                selectedFiles.push({
                    file: file,
                    id: Date.now() + Math.random(),
                    status: 'pending'
                });
            }
        });

        updateFilesList();
    }

    function updateFilesList() {
        if (selectedFiles.length === 0) {
            filesList.style.display = 'none';
            return;
        }

        filesList.style.display = 'block';
        filesContainer.innerHTML = '';

        selectedFiles.forEach((fileObj, index) => {
            const fileElement = createFileElement(fileObj, index);
            filesContainer.appendChild(fileElement);
        });

        // Update Feather icons
        if (typeof feather !== 'undefined') {
            feather.replace({ 'stroke-width': 1.5 });
        }
    }

    function createFileElement(fileObj, index) {
        const file = fileObj.file;
        const div = document.createElement('div');
        div.className = 'file-item';
        div.dataset.index = index;

        const fileType = getFileType(file.type);
        const fileIcon = getFileIcon(fileType);

        div.innerHTML = `
            <div class="file-icon ${fileType}">
                <i data-feather="${fileIcon}"></i>
            </div>
            <div class="file-info">
                <div class="file-name">${escapeHtml(file.name)}</div>
                <div class="file-details">
                    ${formatFileSize(file.size)} • ${file.type || 'Unknown type'}
                </div>
                <div class="file-progress" style="display: none;">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
            <div class="file-actions">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${index})">
                    <i data-feather="x" class="icon-xs"></i>
                </button>
            </div>
        `;

        return div;
    }

    function getFileType(mimeType) {
        if (mimeType.startsWith('image/')) return 'image';
        if (mimeType.startsWith('video/')) return 'video';
        if (mimeType.includes('pdf') || mimeType.includes('document') || 
            mimeType.includes('spreadsheet') || mimeType.includes('presentation') ||
            mimeType.startsWith('text/')) return 'document';
        return 'other';
    }

    function getFileIcon(fileType) {
        const icons = {
            'image': 'image',
            'video': 'video',
            'document': 'file-text',
            'other': 'file'
        };
        return icons[fileType] || 'file';
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Global function for removing files
    window.removeFile = function(index) {
        selectedFiles.splice(index, 1);
        updateFilesList();
    };

    function clearAllFiles() {
        selectedFiles = [];
        updateFilesList();
    }

    function resetUploadForm() {
        selectedFiles = [];
        uploadedFiles = [];
        updateFilesList();
        progressSection.style.display = 'none';
        resultsSection.style.display = 'none';
    }

    async function startUpload() {
        if (selectedFiles.length === 0) {
            showNotification('Vui lòng chọn ít nhất một file', 'error');
            return;
        }

        // Show progress section
        progressSection.style.display = 'block';
        startUploadBtn.disabled = true;
        clearAllBtn.disabled = true;

        // Initialize progress
        const overallProgressBar = document.getElementById('overallProgressBar');
        const overallPercent = document.getElementById('overallPercent');
        const progressInfo = document.getElementById('progressInfo');
        const individualProgress = document.getElementById('individualProgress');

        overallProgressBar.style.width = '0%';
        overallPercent.textContent = '0%';
        progressInfo.textContent = 'Bắt đầu tải lên...';
        individualProgress.innerHTML = '';

        // Create individual progress items
        selectedFiles.forEach((fileObj, index) => {
            const progressItem = document.createElement('div');
            progressItem.className = 'individual-file-progress';
            progressItem.innerHTML = `
                <div class="file-name">${escapeHtml(fileObj.file.name)}</div>
                <div class="progress">
                    <div class="progress-bar" id="progress-${index}" role="progressbar" style="width: 0%"></div>
                </div>
                <div class="status" id="status-${index}">Chờ...</div>
            `;
            individualProgress.appendChild(progressItem);
        });

        // Upload files
        let completed = 0;
        const results = [];

        for (let i = 0; i < selectedFiles.length; i++) {
            const fileObj = selectedFiles[i];
            const progressBar = document.getElementById(`progress-${i}`);
            const statusEl = document.getElementById(`status-${i}`);

            try {
                statusEl.textContent = 'Đang tải...';
                statusEl.className = 'status text-info';
                
                const formData = new FormData();
                formData.append('file', fileObj.file);

                const response = await fetch('/media/upload', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.media) {
                    progressBar.style.width = '100%';
                    progressBar.className = 'progress-bar bg-success';
                    statusEl.textContent = 'Hoàn thành';
                    statusEl.className = 'status text-success';
                    
                    results.push({
                        success: true,
                        file: fileObj.file,
                        media: result.media
                    });
                } else {
                    throw new Error(result.error || 'Upload failed');
                }
            } catch (error) {
                progressBar.style.width = '100%';
                progressBar.className = 'progress-bar bg-danger';
                statusEl.textContent = 'Lỗi';
                statusEl.className = 'status text-danger';
                
                results.push({
                    success: false,
                    file: fileObj.file,
                    error: error.message
                });
            }

            completed++;
            const overallProgress = (completed / selectedFiles.length) * 100;
            overallProgressBar.style.width = overallProgress + '%';
            overallPercent.textContent = Math.round(overallProgress) + '%';
            progressInfo.textContent = `Đã hoàn thành ${completed}/${selectedFiles.length} file`;
        }

        // Show results
        setTimeout(() => {
            showResults(results);
        }, 1000);
    }

    function showResults(results) {
        resultsSection.style.display = 'block';
        const resultsContainer = document.getElementById('resultsContainer');
        resultsContainer.innerHTML = '';

        results.forEach(result => {
            const resultItem = document.createElement('div');
            resultItem.className = `result-item ${result.success ? 'success' : 'error'}`;

            if (result.success) {
                resultItem.innerHTML = `
                    <div class="result-icon success">
                        <i data-feather="check"></i>
                    </div>
                    <div class="result-info">
                        <div class="result-name fw-medium">${escapeHtml(result.file.name)}</div>
                        <div class="result-details text-muted">
                            Tải lên thành công • ${formatFileSize(result.file.size)}
                        </div>
                    </div>
                `;
            } else {
                resultItem.innerHTML = `
                    <div class="result-icon error">
                        <i data-feather="x"></i>
                    </div>
                    <div class="result-info">
                        <div class="result-name fw-medium">${escapeHtml(result.file.name)}</div>
                        <div class="result-details text-muted">
                            Lỗi: ${escapeHtml(result.error)}
                        </div>
                    </div>
                `;
            }

            resultsContainer.appendChild(resultItem);
        });

        // Update Feather icons
        if (typeof feather !== 'undefined') {
            feather.replace({ 'stroke-width': 1.5 });
        }

        // Show success notification
        const successCount = results.filter(r => r.success).length;
        const errorCount = results.filter(r => !r.success).length;
        
        if (successCount > 0) {
            showNotification(`Đã tải lên thành công ${successCount} file`, 'success');
        }
        if (errorCount > 0) {
            showNotification(`${errorCount} file tải lên thất bại`, 'error');
        }
    }

    function showNotification(message, type = 'info') {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 'alert-info';
        
        const alert = document.createElement('div');
        alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alert);
        
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }
});
</script>
