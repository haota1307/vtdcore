<div class="page-content">
    <div class="container-fluid">
        <!-- Tiêu đề trang -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <h4 class="mb-sm-0 fw-bold">Nhật ký hoạt động</h4>
                        <p class="text-muted mb-0">Theo dõi và kiểm toán tất cả hoạt động trong hệ thống</p>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= admin_url() ?>">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhật ký</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê tổng quan -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-primary-subtle">
                                <i data-feather="file-text"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="stats-number mb-0"><?= isset($meta['total']) ? number_format($meta['total']) : '0' ?></h3>
                                <p class="stats-label mb-0">Tổng nhật ký</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-success-subtle">
                                <i data-feather="check-circle"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="stats-number mb-0"><?php 
                                    if (isset($logs)) {
                                        $today = date('Y-m-d');
                                        $todayLogs = array_filter($logs, function($log) use ($today) {
                                            return isset($log['created_at']) && strpos($log['created_at'], $today) === 0;
                                        });
                                        echo count($todayLogs);
                                    } else {
                                        echo '0';
                                    }
                                ?></h3>
                                <p class="stats-label mb-0">Hôm nay</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-info-subtle">
                                <i data-feather="users"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="stats-number mb-0"><?php 
                                    if (isset($logs)) {
                                        $uniqueUsers = array_unique(array_column($logs, 'user_id'));
                                        echo count(array_filter($uniqueUsers));
                                    } else {
                                        echo '0';
                                    }
                                ?></h3>
                                <p class="stats-label mb-0">Người dùng</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-warning-subtle">
                                <i data-feather="activity"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="stats-number mb-0"><?php 
                                    if (isset($logs)) {
                                        $actions = array_unique(array_column($logs, 'action'));
                                        echo count(array_filter($actions));
                                    } else {
                                        echo '0';
                                    }
                                ?></h3>
                                <p class="stats-label mb-0">Loại hoạt động</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bộ lọc và tìm kiếm -->
        <div class="row">
            <div class="col-12">
                <div class="card filter-card">
                    <div class="card-body">
                        <form method="GET" action="<?= admin_url('logs') ?>">
                            <div class="row g-3 align-items-center">
                                <div class="col-lg-4">
                                    <div class="search-box">
                                        <input type="text" class="form-control search-input" name="q" 
                                               value="<?= esc($q ?? '') ?>" 
                                               placeholder="Tìm kiếm theo hành động, nội dung...">
                                        <i data-feather="search" class="search-icon"></i>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <select class="form-select" name="user_id">
                                        <option value="">Tất cả người dùng</option>
                                        <?php if (isset($logs)): ?>
                                            <?php 
                                            $userIds = array_unique(array_filter(array_column($logs, 'user_id')));
                                            foreach ($userIds as $userId): 
                                            ?>
                                            <option value="<?= $userId ?>" <?= ($filter_user_id == $userId) ? 'selected' : '' ?>>
                                                User ID: <?= $userId ?>
                                            </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i data-feather="filter" class="icon-xs me-1"></i> Lọc
                                    </button>
                                </div>
                                <div class="col-lg-2">
                                    <a href="<?= admin_url('logs') ?>" class="btn btn-outline-secondary w-100">
                                        <i data-feather="refresh-cw" class="icon-xs me-1"></i> Đặt lại
                                    </a>
                                </div>
                                <div class="col-lg-1">
                                    <button type="button" class="btn btn-outline-success w-100" onclick="exportLogs()">
                                        <i data-feather="download" class="icon-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách nhật ký -->
        <div class="row">
            <div class="col-12">
                <div class="card logs-table-card">
                    <div class="card-header bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">
                                <i data-feather="list" class="icon-sm me-2 text-primary"></i>
                                Danh sách nhật ký hoạt động
                            </h5>
                            <div class="d-flex gap-2">
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                        <i data-feather="more-vertical" class="icon-xs"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" onclick="exportLogs()">
                                            <i data-feather="download" class="icon-xs me-2"></i> Xuất Excel
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="clearOldLogs()">
                                            <i data-feather="trash-2" class="icon-xs me-2"></i> Xóa log cũ
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="refreshLogs()">
                                            <i data-feather="refresh-cw" class="icon-xs me-2"></i> Làm mới
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 50px;">#</th>
                                        <th scope="col">Hành động</th>
                                        <th scope="col">Người dùng</th>
                                        <th scope="col">Nội dung</th>
                                        <th scope="col">IP Address</th>
                                        <th scope="col">Thời gian</th>
                                        <th scope="col" style="width: 80px;">Chi tiết</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($logs)): foreach ($logs as $index => $log): ?>
                                    <tr class="log-row">
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                <?= esc($log['id'] ?? ($index + 1)) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="action-icon me-2">
                                                    <?php 
                                                    $action = $log['action'] ?? '';
                                                    $iconClass = 'info';
                                                    $icon = 'activity';
                                                    
                                                    if (strpos($action, 'create') !== false || strpos($action, 'add') !== false) {
                                                        $iconClass = 'success';
                                                        $icon = 'plus-circle';
                                                    } elseif (strpos($action, 'delete') !== false || strpos($action, 'remove') !== false) {
                                                        $iconClass = 'danger';
                                                        $icon = 'trash-2';
                                                    } elseif (strpos($action, 'update') !== false || strpos($action, 'edit') !== false) {
                                                        $iconClass = 'warning';
                                                        $icon = 'edit';
                                                    } elseif (strpos($action, 'login') !== false || strpos($action, 'auth') !== false) {
                                                        $iconClass = 'primary';
                                                        $icon = 'log-in';
                                                    }
                                                    ?>
                                                    <i data-feather="<?= $icon ?>" class="icon-xs text-<?= $iconClass ?>"></i>
                                                </div>
                                                <div>
                                                    <span class="action-name fw-medium"><?= esc($action) ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (!empty($log['user_id'])): ?>
                                                <div class="user-info">
                                                    <span class="badge bg-info-subtle text-info">
                                                        User #<?= esc($log['user_id']) ?>
                                                    </span>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">System</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="context-content">
                                                <?php if (!empty($log['context'])): ?>
                                                    <?php 
                                                    $context = $log['context'];
                                                    if (is_string($context)) {
                                                        // Try to decode JSON
                                                        $decoded = json_decode($context, true);
                                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                            $context = $decoded;
                                                        }
                                                    }
                                                    
                                                    if (is_array($context)) {
                                                        $contextStr = '';
                                                        foreach ($context as $key => $value) {
                                                            if (is_scalar($value)) {
                                                                $contextStr .= "<strong>{$key}:</strong> " . esc($value) . "<br>";
                                                            }
                                                        }
                                                        echo $contextStr;
                                                    } else {
                                                        echo esc($context);
                                                    }
                                                    ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Không có chi tiết</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="text-muted">
                                            <?= esc($log['ip_address'] ?? 'N/A') ?>
                                        </td>
                                        <td class="text-muted">
                                            <?php if (!empty($log['created_at'])): ?>
                                                <?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?>
                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewLogDetail(<?= json_encode($log) ?>)" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                <i data-feather="eye" class="icon-xs"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="empty-state">
                                                <i data-feather="file-text" class="text-muted mb-3" style="width: 3rem; height: 3rem;"></i>
                                                <h6 class="text-muted">Không tìm thấy nhật ký nào</h6>
                                                <p class="text-muted mb-0">Thử thay đổi bộ lọc hoặc tìm kiếm</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Phân trang -->
                    <?php if (isset($meta) && $meta['total'] > 0): ?>
                    <div class="card-footer bg-white">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <div class="pagination-info">
                                    Hiển thị <span class="fw-semibold"><?= esc($meta['from'] ?? 1) ?></span> - 
                                    <span class="fw-semibold"><?= esc($meta['to'] ?? 0) ?></span> 
                                    trong tổng số <span class="fw-semibold"><?= esc($meta['total'] ?? 0) ?></span> kết quả
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <nav aria-label="Phân trang">
                                    <?php if (isset($pagerObj)): ?>
                                        <?= $pagerObj->links() ?>
                                    <?php else: ?>
                                    <ul class="pagination pagination-sm justify-content-end mb-0">
                                        <?php if (isset($meta['previous_page']) && $meta['previous_page']): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $meta['previous_page'] ?><?= !empty($q) ? '&q=' . urlencode($q) : '' ?><?= !empty($filter_user_id) ? '&user_id=' . $filter_user_id : '' ?>">
                                                <i data-feather="chevron-left" class="icon-xs"></i>
                                            </a>
                                        </li>
                                        <?php endif; ?>
                                        
                                        <?php 
                                        $currentPage = $meta['current_page'] ?? 1;
                                        $lastPage = $meta['last_page'] ?? 1;
                                        for ($i = max(1, $currentPage - 2); $i <= min($lastPage, $currentPage + 2); $i++): ?>
                                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?><?= !empty($q) ? '&q=' . urlencode($q) : '' ?><?= !empty($filter_user_id) ? '&user_id=' . $filter_user_id : '' ?>"><?= $i ?></a>
                                        </li>
                                        <?php endfor; ?>
                                        
                                        <?php if (isset($meta['next_page']) && $meta['next_page']): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $meta['next_page'] ?><?= !empty($q) ? '&q=' . urlencode($q) : '' ?><?= !empty($filter_user_id) ? '&user_id=' . $filter_user_id : '' ?>">
                                                <i data-feather="chevron-right" class="icon-xs"></i>
                                            </a>
                                        </li>
                                        <?php endif; ?>
                                    </ul>
                                    <?php endif; ?>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal chi tiết log -->
<div class="modal fade" id="logDetailModal" tabindex="-1" aria-labelledby="logDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logDetailModalLabel">
                    <i data-feather="file-text" class="icon-sm me-2"></i>
                    Chi tiết nhật ký
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="logDetailContent">
                <!-- Content will be populated by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Card thống kê */
.stats-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: transform 0.2s, box-shadow 0.2s;
}

.stats-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.stats-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stats-icon svg {
    width: 24px;
    height: 24px;
    color: currentColor;
}

.bg-primary-subtle {
    background-color: rgba(78, 115, 223, 0.15);
    color: #4e73df;
}

.bg-success-subtle {
    background-color: rgba(28, 200, 138, 0.15);
    color: #1cc88a;
}

.bg-warning-subtle {
    background-color: rgba(246, 194, 62, 0.15);
    color: #f6c23e;
}

.bg-info-subtle {
    background-color: rgba(54, 185, 204, 0.15);
    color: #36b9cc;
}

.stats-number {
    font-size: 1.75rem;
    font-weight: 600;
    color: #495057;
}

.stats-label {
    color: #6c757d;
    font-size: 0.875rem;
}

/* Bộ lọc */
.filter-card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 12px;
}

.search-box {
    position: relative;
}

.search-input {
    padding-left: 40px;
    border-radius: 8px;
    border: 1px solid #e3e6f0;
}

.search-input:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    width: 18px;
    height: 18px;
}

/* Bảng logs */
.logs-table-card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 12px;
}

.table th {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    font-weight: 600;
    color: #5a5c69;
    font-size: 0.875rem;
}

.table td {
    border-bottom: 1px solid #e3e6f0;
    vertical-align: middle;
}

.log-row:hover {
    background-color: #f8f9fc;
}

.action-name {
    font-size: 0.875rem;
    color: #495057;
}

.context-content {
    font-size: 0.8rem;
    max-width: 300px;
    word-break: break-word;
}

/* Icons */
.icon-xs {
    width: 16px;
    height: 16px;
}

.icon-sm {
    width: 18px;
    height: 18px;
}

/* Empty state */
.empty-state {
    padding: 2rem;
    text-align: center;
}

/* Phân trang */
.pagination-info {
    color: #6c757d;
    font-size: 0.875rem;
}

.page-link {
    border-radius: 6px;
    border: 1px solid #e3e6f0;
    color: #5a5c69;
    margin: 0 2px;
}

.page-link:hover {
    background-color: #f8f9fc;
    border-color: #4e73df;
    color: #4e73df;
}

.page-item.active .page-link {
    background-color: #4e73df;
    border-color: #4e73df;
}

/* Modal */
.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
}

/* Form controls */
.form-control:focus,
.form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

/* Responsive */
@media (max-width: 768px) {
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .filter-card .row > div {
        margin-bottom: 0.5rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .context-content {
        max-width: 200px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace({ 'stroke-width': 1.5 });
    }

    // Khởi tạo tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Xem chi tiết log
function viewLogDetail(log) {
    const modal = new bootstrap.Modal(document.getElementById('logDetailModal'));
    const content = document.getElementById('logDetailContent');
    
    let contextHtml = '';
    if (log.context) {
        try {
            const context = typeof log.context === 'string' ? JSON.parse(log.context) : log.context;
            contextHtml = '<pre class="bg-light p-3 rounded">' + JSON.stringify(context, null, 2) + '</pre>';
        } catch (e) {
            contextHtml = '<div class="bg-light p-3 rounded">' + (log.context || 'Không có dữ liệu') + '</div>';
        }
    } else {
        contextHtml = '<div class="text-muted">Không có dữ liệu chi tiết</div>';
    }
    
    content.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h6>Thông tin cơ bản</h6>
                <table class="table table-sm">
                    <tr><td><strong>ID:</strong></td><td>${log.id || 'N/A'}</td></tr>
                    <tr><td><strong>Hành động:</strong></td><td>${log.action || 'N/A'}</td></tr>
                    <tr><td><strong>Người dùng:</strong></td><td>${log.user_id ? 'User #' + log.user_id : 'System'}</td></tr>
                    <tr><td><strong>IP Address:</strong></td><td>${log.ip_address || 'N/A'}</td></tr>
                    <tr><td><strong>Thời gian:</strong></td><td>${log.created_at ? new Date(log.created_at).toLocaleString('vi-VN') : 'N/A'}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Chi tiết ngữ cảnh</h6>
                ${contextHtml}
            </div>
        </div>
    `;
    
    modal.show();
}

// Xuất logs
function exportLogs() {
    showNotification('Tính năng xuất logs đang được phát triển', 'info');
}

// Xóa logs cũ
function clearOldLogs() {
    if (!confirm('Bạn có chắc chắn muốn xóa các logs cũ? Hành động này không thể hoàn tác.')) return;
    showNotification('Tính năng xóa logs cũ đang được phát triển', 'info');
}

// Làm mới logs
function refreshLogs() {
    window.location.reload();
}

// Hiển thị thông báo
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
</script>