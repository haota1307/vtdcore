<div class="page-content">
    <div class="container-fluid">
        <!-- Tiêu đề trang -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <h4 class="mb-sm-0 fw-bold">Quản lý người dùng</h4>
                        <p class="text-muted mb-0">Quản lý tài khoản và quyền truy cập của người dùng</p>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= admin_url() ?>">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Người dùng</li>
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
                                <i data-feather="users"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="stats-number mb-0"><?= isset($users) ? count($users) : '0' ?></h3>
                                <p class="stats-label mb-0">Tổng người dùng</p>
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
                                <i data-feather="user-check"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="stats-number mb-0"><?php 
                                    if (isset($users)) {
                                        $active = array_filter($users, fn($u) => ($u['status'] ?? 'active') === 'active');
                                        echo count($active);
                                    } else {
                                        echo '0';
                                    }
                                ?></h3>
                                <p class="stats-label mb-0">Đang hoạt động</p>
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
                                <i data-feather="user-x"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="stats-number mb-0"><?php 
                                    if (isset($users)) {
                                        $inactive = array_filter($users, fn($u) => ($u['status'] ?? 'active') !== 'active');
                                        echo count($inactive);
                                    } else {
                                        echo '0';
                                    }
                                ?></h3>
                                <p class="stats-label mb-0">Không hoạt động</p>
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
                                <i data-feather="calendar"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="stats-number mb-0"><?php 
                                    if (isset($users)) {
                                        $today = date('Y-m-d');
                                        $newToday = array_filter($users, function($u) use ($today) {
                                            return isset($u['created_at']) && strpos($u['created_at'], $today) === 0;
                                        });
                                        echo count($newToday);
                                    } else {
                                        echo '0';
                                    }
                                ?></h3>
                                <p class="stats-label mb-0">Mới hôm nay</p>
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
                        <div class="row g-3 align-items-center">
                            <div class="col-lg-5">
                                <div class="search-box">
                                    <input type="text" class="form-control search-input" id="searchUsers" placeholder="Tìm kiếm theo tên, email...">
                                    <i data-feather="search" class="search-icon"></i>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <select class="form-select" id="statusFilter">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="active">Đang hoạt động</option>
                                    <option value="inactive">Không hoạt động</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-outline-secondary w-100" id="resetFilters">
                                    <i data-feather="refresh-cw" class="icon-xs me-1"></i> Đặt lại
                                </button>
                            </div>
                            <div class="col-lg-2">
                                <a href="<?= admin_url('users/create') ?>" class="btn btn-primary w-100">
                                    <i data-feather="user-plus" class="icon-xs me-1"></i> Thêm mới
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                                            </div>
                                        </div>

        <!-- Danh sách người dùng -->
        <div class="row">
            <div class="col-12">
                <div class="card users-table-card">
                    <div class="card-header bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">
                                <i data-feather="users" class="icon-sm me-2 text-primary"></i>
                                Danh sách người dùng
                            </h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm" id="exportUsers">
                                    <i data-feather="download" class="icon-xs me-1"></i> Xuất Excel
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                        <i data-feather="more-vertical" class="icon-xs"></i>
                                                </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#"><i data-feather="eye" class="icon-xs me-2"></i> Xem chi tiết</a></li>
                                        <li><a class="dropdown-item" href="#"><i data-feather="edit" class="icon-xs me-2"></i> Chỉnh sửa hàng loạt</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i data-feather="trash-2" class="icon-xs me-2"></i> Xóa đã chọn</a></li>
                                    </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    <div class="card-body p-0">
                                    <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="usersTable">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 50px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                            </div>
                                        </th>
                                        <th scope="col">Người dùng</th>
                                                    <th scope="col">Email</th>
                                        <th scope="col">ID</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Đăng nhập cuối</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col" style="width: 120px;">Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($users)): foreach ($users as $u): ?>
                                    <tr class="user-row">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input user-checkbox" type="checkbox" value="<?= (int)$u['id'] ?>">
                                            </div>
                                        </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                <div class="avatar-container me-3">
                                                    <div class="avatar avatar-sm">
                                                        <img src="<?= base_url('assets/images/users/avatar-1.jpg') ?>" alt="" class="avatar-img">
                                                        <span class="avatar-status <?= ($u['status'] ?? 'active') === 'active' ? 'bg-success' : 'bg-secondary' ?>"></span>
                                                    </div>
                                                            </div>
                                                <div>
                                                    <h6 class="user-name mb-0"><?= esc($u['username'] ?? 'N/A') ?></h6>
                                                    <p class="user-id text-muted mb-0">ID: <?= esc($u['id']) ?></p>
                                                            </div>
                                                        </div>
                                                    </td>
                                        <td class="user-email"><?= esc($u['email'] ?? 'N/A') ?></td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                #<?= esc($u['id']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge status-badge <?= ($u['status'] ?? 'active') === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?>">
                                                <i data-feather="<?= ($u['status'] ?? 'active') === 'active' ? 'check-circle' : 'x-circle' ?>" class="icon-xs me-1"></i>
                                                <?= ($u['status'] ?? 'active') === 'active' ? 'Hoạt động' : 'Không hoạt động' ?>
                                                        </span>
                                                    </td>
                                        <td class="text-muted">
                                            <?php if (!empty($u['last_login_at'])): ?>
                                                <?= date('d/m/Y H:i', strtotime($u['last_login_at'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Chưa đăng nhập</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted">
                                            <?php if (!empty($u['created_at'])): ?>
                                                <?= date('d/m/Y H:i', strtotime($u['created_at'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-outline-primary" onclick="viewUser(<?= (int)$u['id'] ?>)" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                    <i data-feather="eye" class="icon-xs"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" onclick="editUser(<?= (int)$u['id'] ?>)" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                    <i data-feather="edit" class="icon-xs"></i>
                                                </button>
                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i data-feather="more-horizontal" class="icon-xs"></i>
                                                    </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="#" onclick="toggleStatus(<?= (int)$u['id'] ?>)">
                                                            <i data-feather="<?= ($u['status'] ?? 'active') === 'active' ? 'user-x' : 'user-check' ?>" class="icon-xs me-2"></i>
                                                            <?= ($u['status'] ?? 'active') === 'active' ? 'Vô hiệu hóa' : 'Kích hoạt' ?>
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="resetPassword(<?= (int)$u['id'] ?>)">
                                                            <i data-feather="key" class="icon-xs me-2"></i> Đặt lại mật khẩu
                                                        </a></li>
                                                                <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteUser(<?= (int)$u['id'] ?>)">
                                                            <i data-feather="trash-2" class="icon-xs me-2"></i> Xóa
                                                        </a></li>
                                                            </ul>
                                                </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; else: ?>
                                                <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="empty-state">
                                                <i data-feather="users" class="text-muted mb-3" style="width: 3rem; height: 3rem;"></i>
                                                <h6 class="text-muted">Không tìm thấy người dùng nào</h6>
                                                <p class="text-muted mb-0">Hãy thêm người dùng mới để bắt đầu</p>
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
                                    <ul class="pagination pagination-sm justify-content-end mb-0">
                                                    <?php if (isset($meta['previous_page']) && $meta['previous_page']): ?>
                                                    <li class="page-item">
                                            <a class="page-link" href="?page=<?= $meta['previous_page'] ?>">
                                                <i data-feather="chevron-left" class="icon-xs"></i>
                                            </a>
                                                    </li>
                                                    <?php endif; ?>
                                        
                                        <?php 
                                        $currentPage = $meta['current_page'] ?? 1;
                                        $lastPage = $meta['last_page'] ?? 1;
                                        for ($i = max(1, $currentPage - 2); $i <= min($lastPage, $currentPage + 2); $i++): ?>
                                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                        <?php endfor; ?>
                                                    
                                                    <?php if (isset($meta['next_page']) && $meta['next_page']): ?>
                                                    <li class="page-item">
                                            <a class="page-link" href="?page=<?= $meta['next_page'] ?>">
                                                <i data-feather="chevron-right" class="icon-xs"></i>
                                            </a>
                                                    </li>
                                                    <?php endif; ?>
                                                </ul>
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

/* Bảng người dùng */
.users-table-card {
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

.user-row:hover {
    background-color: #f8f9fc;
}

/* Avatar */
.avatar-container {
    position: relative;
}

.avatar {
    width: 40px;
    height: 40px;
    position: relative;
    display: inline-block;
}

.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.avatar-status {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
}

.user-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: #495057;
}

.user-id {
    font-size: 0.75rem;
}

/* Badge trạng thái */
.status-badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    display: inline-flex;
    align-items: center;
}

.bg-success-subtle {
    background-color: rgba(28, 200, 138, 0.15);
    color: #1cc88a;
}

.bg-secondary-subtle {
    background-color: rgba(133, 135, 150, 0.15);
    color: #858796;
}

.bg-info-subtle {
    background-color: rgba(54, 185, 204, 0.15);
    color: #36b9cc;
}

/* Nút hành động */
.action-buttons {
    display: flex;
    gap: 0.25rem;
    align-items: center;
}

.action-buttons .btn {
    padding: 0.375rem 0.5rem;
    border-radius: 6px;
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

.modal-header {
    border-bottom: 1px solid #e3e6f0;
    padding: 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    border-top: 1px solid #e3e6f0;
    padding: 1.5rem;
}

/* Form controls */
.form-control:focus,
.form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.form-check-input:checked {
    background-color: #4e73df;
    border-color: #4e73df;
}

/* Responsive */
@media (max-width: 768px) {
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .filter-card .row > div {
        margin-bottom: 0.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.125rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
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

    // Tìm kiếm người dùng
    const searchInput = document.getElementById('searchUsers');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const tableRows = document.querySelectorAll('.user-row');
            
            tableRows.forEach(row => {
                const userName = row.querySelector('.user-name').textContent.toLowerCase();
                const userEmail = row.querySelector('.user-email').textContent.toLowerCase();
                const isVisible = userName.includes(searchTerm) || userEmail.includes(searchTerm);
                row.style.display = isVisible ? '' : 'none';
            });
        });
    }

    // Lọc theo trạng thái
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function(e) {
            const filterValue = e.target.value;
            const tableRows = document.querySelectorAll('.user-row');
            
            tableRows.forEach(row => {
                if (!filterValue) {
                    row.style.display = '';
                    return;
                }
                
                const statusBadge = row.querySelector('.status-badge');
                const isActive = statusBadge.classList.contains('text-success');
                const shouldShow = (filterValue === 'active' && isActive) || 
                                 (filterValue === 'inactive' && !isActive);
                row.style.display = shouldShow ? '' : 'none';
            });
        });
    }

    // Đặt lại bộ lọc
    const resetFilters = document.getElementById('resetFilters');
    if (resetFilters) {
        resetFilters.addEventListener('click', function() {
            document.getElementById('searchUsers').value = '';
            document.getElementById('statusFilter').value = '';
            
            const tableRows = document.querySelectorAll('.user-row');
            tableRows.forEach(row => row.style.display = '');
        });
    }

    // Chọn tất cả checkbox
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function(e) {
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = e.target.checked;
            });
        });
    }


});

// Xem chi tiết người dùng
function viewUser(id) {
    showNotification('Tính năng xem chi tiết đang được phát triển', 'info');
}

// Chỉnh sửa người dùng
function editUser(id) {
    showNotification('Tính năng chỉnh sửa đang được phát triển', 'info');
}

// Toggle trạng thái người dùng
async function toggleStatus(id) {
    if (!confirm('Bạn có chắc chắn muốn thay đổi trạng thái của người dùng này?')) return;
    
    try {
        const response = await fetch(`<?= admin_url('users') ?>/${id}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        });
        
        if (!response.ok) {
            throw new Error('Không thể thay đổi trạng thái');
        }
        
        showNotification('Cập nhật trạng thái thành công', 'success');
        setTimeout(() => location.reload(), 1000);
    } catch (error) {
        console.error('Error:', error);
        showNotification('Không thể thay đổi trạng thái', 'error');
    }
}

// Đặt lại mật khẩu
async function resetPassword(id) {
    if (!confirm('Bạn có chắc chắn muốn đặt lại mật khẩu cho người dùng này?')) return;
    
    try {
        const response = await fetch(`<?= admin_url('users') ?>/${id}/reset-password`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        });
        
        if (!response.ok) {
            throw new Error('Không thể đặt lại mật khẩu');
        }
        
        const result = await response.json();
        showNotification(`Đặt lại mật khẩu thành công. Mật khẩu mới: ${result.new_password}`, 'success');
    } catch (error) {
        console.error('Error:', error);
        showNotification('Không thể đặt lại mật khẩu', 'error');
    }
}

// Xóa người dùng
function deleteUser(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa người dùng này? Hành động này không thể hoàn tác.')) return;
    
    showNotification('Tính năng xóa người dùng đang được phát triển', 'info');
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
