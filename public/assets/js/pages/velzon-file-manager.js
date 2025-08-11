/**
 * Velzon File Manager JavaScript
 * Handles file manager functionality with modern UI
 */

(function() {
    'use strict';

    // File manager initialization
    function initFileManager() {
        // File selection
        const fileCards = document.querySelectorAll('.file-card');
        const detailPanel = document.getElementById('detail-panel');
        const fileDetailContent = document.getElementById('file-detail-content');
        const closeDetailBtn = document.getElementById('close-detail');
        
        // Show file details
        function showFileDetail(fileId) {
            const fileCard = document.querySelector(`[data-id="${fileId}"]`);
            if (!fileCard) return;
            
            const fileName = fileCard.querySelector('.file-name').textContent;
            const fileUrl = fileCard.querySelector('.view-file-btn').href;
            const fileSize = fileCard.querySelector('.text-muted').textContent;
            const fileMime = fileCard.querySelector('.fs-12').textContent;
            const fileScan = fileCard.querySelector('.badge').textContent;
            
            fileDetailContent.innerHTML = `
                <div class="text-center mb-3">
                    <img src="${fileUrl}" alt="${fileName}" class="img-fluid rounded" style="max-height: 200px;">
                </div>
                <dl class="row mb-0">
                    <dt class="col-4">Name</dt>
                    <dd class="col-8">${fileName}</dd>
                    <dt class="col-4">Size</dt>
                    <dd class="col-8">${fileSize}</dd>
                    <dt class="col-4">Type</dt>
                    <dd class="col-8">${fileMime}</dd>
                    <dt class="col-4">Scan</dt>
                    <dd class="col-8"><span class="badge bg-success">${fileScan}</span></dd>
                </dl>
                <div class="mt-3 d-flex gap-2">
                    <a href="${fileUrl}" target="_blank" class="btn btn-primary btn-sm flex-grow-1">Open</a>
                    <button class="btn btn-danger btn-sm" onclick="deleteFile(${fileId})">Delete</button>
                </div>
            `;
            
            detailPanel.style.display = 'block';
        }
        
        // File card click
        fileCards.forEach(card => {
            card.addEventListener('click', (e) => {
                if (e.target.closest('.dropdown') || e.target.closest('.form-check')) return;
                const fileId = card.dataset.id;
                showFileDetail(fileId);
            });
        });
        
        // Close detail panel
        if (closeDetailBtn) {
            closeDetailBtn.addEventListener('click', () => {
                detailPanel.style.display = 'none';
            });
        }
        
        // Upload functionality
        const uploadBtn = document.getElementById('upload-btn');
        const uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
        const uploadFiles = document.getElementById('upload-files');
        const startUploadBtn = document.getElementById('start-upload');
        const uploadProgress = document.getElementById('upload-progress');
        const progressBar = uploadProgress?.querySelector('.progress-bar');
        
        if (uploadBtn) {
            uploadBtn.addEventListener('click', () => {
                uploadModal.show();
            });
        }
        
        if (startUploadBtn) {
            startUploadBtn.addEventListener('click', async () => {
                const files = uploadFiles.files;
                if (!files.length) return;
                
                uploadProgress.style.display = 'block';
                startUploadBtn.disabled = true;
                
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const formData = new FormData();
                    formData.append('file', file);
                    
                    try {
                        const response = await fetch('/media/upload', {
                            method: 'POST',
                            body: formData
                        });
                        
                        if (response.ok && progressBar) {
                            progressBar.style.width = `${((i + 1) / files.length) * 100}%`;
                        }
                    } catch (error) {
                        console.error('Upload failed:', error);
                    }
                }
                
                setTimeout(() => {
                    location.reload();
                }, 1000);
            });
        }
        
        // Delete file
        window.deleteFile = function(fileId) {
            if (!confirm('Are you sure you want to delete this file?')) return;
            
            fetch(`/media/item/${fileId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Delete failed:', error);
            });
        };
        
        // Copy URL
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('copy-url-btn')) {
                e.preventDefault();
                const url = e.target.dataset.url;
                navigator.clipboard.writeText(url).then(() => {
                    alert('URL copied to clipboard!');
                });
            }
        });
        
        // Search functionality
        const sidebarSearch = document.getElementById('sidebar-search');
        if (sidebarSearch) {
            sidebarSearch.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase();
                fileCards.forEach(card => {
                    const fileName = card.querySelector('.file-name').textContent.toLowerCase();
                    card.style.display = fileName.includes(query) ? 'block' : 'none';
                });
            });
        }
        
        // File type filter
        const fileTypeFilter = document.getElementById('file-type-filter');
        if (fileTypeFilter) {
            fileTypeFilter.addEventListener('change', (e) => {
                const selectedType = e.target.value;
                if (!selectedType) {
                    fileCards.forEach(card => card.style.display = 'block');
                    return;
                }
                
                fileCards.forEach(card => {
                    const fileMime = card.querySelector('.fs-12').textContent;
                    card.style.display = fileMime.includes(selectedType) ? 'block' : 'none';
                });
            });
        }
        
        // Bulk actions
        const selectAllCheckbox = document.getElementById('select-all-files');
        const fileCheckboxes = document.querySelectorAll('.file-checkbox');
        const bulkActions = document.getElementById('bulk-actions');
        
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', (e) => {
                fileCheckboxes.forEach(checkbox => {
                    checkbox.checked = e.target.checked;
                });
                updateBulkActions();
            });
        }
        
        fileCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });
        
        function updateBulkActions() {
            const checkedFiles = document.querySelectorAll('.file-checkbox:checked');
            if (checkedFiles.length > 0) {
                bulkActions.style.display = 'block';
            } else {
                bulkActions.style.display = 'none';
            }
        }
        
        // Bulk delete
        const bulkDeleteBtn = document.getElementById('bulk-delete');
        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', () => {
                const checkedFiles = document.querySelectorAll('.file-checkbox:checked');
                if (!checkedFiles.length) return;
                
                if (!confirm(`Are you sure you want to delete ${checkedFiles.length} files?`)) return;
                
                const fileIds = Array.from(checkedFiles).map(checkbox => checkbox.value);
                
                Promise.all(fileIds.map(id => 
                    fetch(`/media/item/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                )).then(() => {
                    location.reload();
                });
            });
        }
        
        // Initialize storage chart
        initStorageChart();
    }
    
    // Initialize storage donut chart
    function initStorageChart() {
        const chartElement = document.getElementById('storage-chart');
        if (!chartElement) return;
        
        // Get chart data from data attributes
        const documentSize = parseInt(chartElement.dataset.documentSize || 0);
        const mediaSize = parseInt(chartElement.dataset.mediaSize || 0);
        const projectSize = parseInt(chartElement.dataset.projectSize || 0);
        const otherSize = parseInt(chartElement.dataset.otherSize || 0);
        
        const totalSize = documentSize + mediaSize + projectSize + otherSize;
        
        if (totalSize === 0) {
            chartElement.innerHTML = '<div class="text-muted">No data available</div>';
            return;
        }
        
        // Calculate percentages
        const documentPercent = totalSize > 0 ? (documentSize / totalSize * 100).toFixed(1) : 0;
        const mediaPercent = totalSize > 0 ? (mediaSize / totalSize * 100).toFixed(1) : 0;
        const projectPercent = totalSize > 0 ? (projectSize / totalSize * 100).toFixed(1) : 0;
        const otherPercent = totalSize > 0 ? (otherSize / totalSize * 100).toFixed(1) : 0;
        
        // Create simple donut chart using SVG
        const chartHTML = `
            <div class="position-relative d-inline-block">
                <svg width="150" height="150" viewBox="0 0 150 150">
                    <circle cx="75" cy="75" r="60" fill="none" stroke="#e9ecef" stroke-width="12"/>
                    <circle cx="75" cy="75" r="60" fill="none" stroke="#556ee6" stroke-width="12" 
                            stroke-dasharray="${documentPercent * 3.77} ${(100 - documentPercent) * 3.77}" 
                            stroke-dashoffset="25" transform="rotate(-90 75 75)"/>
                    <circle cx="75" cy="75" r="60" fill="none" stroke="#34c38f" stroke-width="12" 
                            stroke-dasharray="${mediaPercent * 3.77} ${(100 - mediaPercent) * 3.77}" 
                            stroke-dashoffset="${25 - documentPercent * 3.77}" transform="rotate(-90 75 75)"/>
                    <circle cx="75" cy="75" r="60" fill="none" stroke="#f1b44c" stroke-width="12" 
                            stroke-dasharray="${projectPercent * 3.77} ${(100 - projectPercent) * 3.77}" 
                            stroke-dashoffset="${25 - (documentPercent + mediaPercent) * 3.77}" transform="rotate(-90 75 75)"/>
                    <circle cx="75" cy="75" r="60" fill="none" stroke="#50a5f1" stroke-width="12" 
                            stroke-dasharray="${otherPercent * 3.77} ${(100 - otherPercent) * 3.77}" 
                            stroke-dashoffset="${25 - (documentPercent + mediaPercent + projectPercent) * 3.77}" transform="rotate(-90 75 75)"/>
                </svg>
                <div class="position-absolute top-50 start-50 translate-middle text-center">
                    <h5 class="mb-0">${(totalSize / 1024 / 1024).toFixed(1)}</h5>
                    <small class="text-muted">GB Used</small>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fs-12">Documents</span>
                    <span class="fs-12 fw-semibold">${documentPercent}%</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="fs-12">Media</span>
                    <span class="fs-12 fw-semibold">${mediaPercent}%</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="fs-12">Projects</span>
                    <span class="fs-12 fw-semibold">${projectPercent}%</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="fs-12">Others</span>
                    <span class="fs-12 fw-semibold">${otherPercent}%</span>
                </div>
            </div>
        `;
        
        chartElement.innerHTML = chartHTML;
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFileManager);
    } else {
        initFileManager();
    }
    
})();
