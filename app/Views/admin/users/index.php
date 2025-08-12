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
                                                <div class="dropdown d-inline-block action-dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle action-dropdown-toggle" type="button" id="dropdownMenuButton-<?= (int)$u['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false" onclick="openDropdown(<?= (int)$u['id'] ?>)">
                                                        <i data-feather="more-horizontal" class="icon-xs"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu" aria-labelledby="dropdownMenuButton-<?= (int)$u['id'] ?>">
                                                        <li><a class="dropdown-item" href="#" onclick="toggleStatus(<?= (int)$u['id'] ?>); return false;">
                                                            <i data-feather="<?= ($u['status'] ?? 'active') === 'active' ? 'user-x' : 'user-check' ?>" class="icon-xs me-2"></i>
                                                            <?= ($u['status'] ?? 'active') === 'active' ? 'Vô hiệu hóa' : 'Kích hoạt' ?>
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="resetPassword(<?= (int)$u['id'] ?>); return false;">
                                                            <i data-feather="key" class="icon-xs me-2"></i> Đặt lại mật khẩu
                                                        </a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteUser(<?= (int)$u['id'] ?>); return false;">
                                                            <i data-feather="trash-2" class="icon-xs me-2"></i> Xóa tài khoản
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



        <!-- View User Modal -->
        <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewUserModalLabel">Chi tiết người dùng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2"><strong>ID:</strong> <span id="viewUserId">-</span></div>
                        <div class="mb-2"><strong>Tên đăng nhập:</strong> <span id="viewUsername">-</span></div>
                        <div class="mb-2"><strong>Email:</strong> <span id="viewEmail">-</span></div>
                        <div class="mb-2"><strong>Trạng thái:</strong> <span id="viewStatus" class="badge bg-secondary-subtle text-secondary">-</span></div>
                        <div class="mb-2"><strong>Tạo lúc:</strong> <span id="viewCreatedAt">-</span></div>
                        <div class="mb-0"><strong>Đăng nhập gần nhất:</strong> <span id="viewLastLogin">-</span></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Chỉnh sửa người dùng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editUserForm">
                        <input type="hidden" id="editUserId" name="id">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editUsername" name="username" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="editEmail" name="email" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mật khẩu (để trống nếu không đổi)</label>
                                    <input type="password" class="form-control" id="editPassword" name="password" placeholder="Để trống để giữ nguyên">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Trạng thái</label>
                                    <select class="form-select" id="editStatus" name="status">
                                        <option value="active">Hoạt động</option>
                                        <option value="disabled">Không hoạt động</option>
                                        <option value="inactive">Không hoạt động</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary" id="editSubmitBtn">Cập nhật</button>
                        </div>
                    </form>
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
    color: #6c757d;
    width: 18px;
    height: 18px;
    display: block;
    pointer-events: none;
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

/* Dropdown styling */
.action-dropdown {
    position: relative;
}

.action-dropdown-toggle {
    cursor: pointer;
}

.action-dropdown-menu {
    position: absolute;
    z-index: 1000;
    display: none;
    min-width: 10rem;
    padding: 0.5rem 0;
    text-align: left;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 0.25rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
}

.action-dropdown-menu.show {
    display: block;
}

/* Hiệu ứng quay cho icon loading */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.spinner {
    animation: spin 1s linear infinite;
}

/* Icons */
.icon-xs {
    width: 16px;
    height: 16px;
    vertical-align: middle;
}

.icon-sm {
    width: 18px;
    height: 18px;
    vertical-align: middle;
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

<!-- Ensure required JS libraries are available -->
<script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/feather-icons/feather.min.js') ?>"></script>

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
    
    // Xử lý nút Xuất Excel
    const exportBtn = document.getElementById('exportUsers');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            // Hiển thị thông báo đang xử lý
            showNotification('Đang chuẩn bị file Excel...', 'info');
            
            // Thêm hiệu ứng loading cho nút
            const originalHtml = exportBtn.innerHTML;
            exportBtn.disabled = true;
            exportBtn.innerHTML = '<i data-feather="loader" class="icon-xs me-1 spinner"></i> Đang xử lý...';
            if (typeof feather !== 'undefined') feather.replace();
            
            // Thêm hiệu ứng quay cho icon
            const spinner = exportBtn.querySelector('.spinner');
            if (spinner) {
                spinner.style.animation = 'spin 1s linear infinite';
            }
            
            // Tạo một form ẩn để submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= admin_url('users/export-excel') ?>';
            
            // Thêm các filter hiện tại vào form nếu cần
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter && statusFilter.value) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'status';
                input.value = statusFilter.value;
                form.appendChild(input);
            }
            
            const searchValue = document.getElementById('searchUsers');
            if (searchValue && searchValue.value) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'search';
                input.value = searchValue.value;
                form.appendChild(input);
            }
            
            // Thêm CSRF token nếu cần
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = csrfToken.getAttribute('name');
                input.value = csrfToken.getAttribute('content');
                form.appendChild(input);
            }
            
            // Thêm form vào body và submit
            document.body.appendChild(form);
            
            try {
                form.submit();
                
                // Khôi phục nút sau 2 giây
                setTimeout(() => {
                    exportBtn.disabled = false;
                    exportBtn.innerHTML = originalHtml;
                    if (typeof feather !== 'undefined') feather.replace();
                }, 2000);
                
                showNotification('File Excel đã được tạo và đang tải xuống', 'success');
            } catch (error) {
                console.error('Lỗi khi xuất Excel:', error);
                showNotification('Có lỗi xảy ra khi xuất Excel', 'error');
                
                // Khôi phục nút ngay lập tức nếu có lỗi
                exportBtn.disabled = false;
                exportBtn.innerHTML = originalHtml;
                if (typeof feather !== 'undefined') feather.replace();
            }
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

    // Khởi tạo và đảm bảo dropdown hoạt động
    function initializeDropdowns() {
        // Kiểm tra xem Bootstrap có sẵn không
        const hasBootstrap = typeof bootstrap !== 'undefined' && bootstrap.Dropdown;
        
        // Xóa tất cả các event listener cũ (nếu có)
        if (hasBootstrap) {
            document.querySelectorAll('.action-dropdown-toggle').forEach(btn => {
                const oldInstance = bootstrap.Dropdown.getInstance(btn);
                if (oldInstance) {
                    oldInstance.dispose();
                }
            });
        }
        
        // Thêm một phương thức trực tiếp để mở dropdown (dự phòng)
        window.openDropdown = function(id) {
            const dropdownToggle = document.getElementById('dropdownMenuButton-' + id);
            if (!dropdownToggle) return;
            
            if (hasBootstrap) {
                const dropdownInstance = new bootstrap.Dropdown(dropdownToggle);
                dropdownInstance.show();
            } else {
                // Đóng tất cả dropdown khác
                document.querySelectorAll('.action-dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
                
                // Mở dropdown hiện tại
                const menu = dropdownToggle.nextElementSibling || 
                             dropdownToggle.closest('.action-dropdown').querySelector('.action-dropdown-menu');
                if (menu) {
                    menu.classList.add('show');
                    refreshFeatherIcons();
                }
            }
        };
        
        // Khởi tạo lại tất cả dropdown
        if (hasBootstrap) {
            document.querySelectorAll('.action-dropdown-toggle').forEach(btn => {
                new bootstrap.Dropdown(btn);
            });
            
            // Đăng ký sự kiện khi dropdown được hiển thị để cập nhật icon
            document.querySelectorAll('.action-dropdown').forEach(dropdown => {
                dropdown.addEventListener('shown.bs.dropdown', function() {
                    refreshFeatherIcons();
                });
            });
        } else {
            // Fallback với vanilla JS nếu không có Bootstrap
            // Xóa event listener cũ nếu có
            const oldClickHandler = window.dropdownClickHandler;
            if (oldClickHandler) {
                document.removeEventListener('click', oldClickHandler);
            }
            
            // Tạo và gắn event listener mới
            window.dropdownClickHandler = function(e) {
                // Xử lý click trên toggle button hoặc các phần tử con của nó (như icon)
                const toggle = e.target.closest('.action-buttons .dropdown-toggle');
                if (!toggle) {
                    // Đóng tất cả dropdown khi click ra ngoài
                    document.querySelectorAll('.action-buttons .dropdown-menu.show').forEach(menu => {
                        menu.classList.remove('show');
                    });
                    return;
                }
                
                e.preventDefault();
                e.stopPropagation();
                
                // Đóng tất cả dropdown khác
                document.querySelectorAll('.action-buttons .dropdown-menu.show').forEach(menu => {
                    if (menu !== toggle.nextElementSibling && menu !== toggle.parentElement.querySelector('.dropdown-menu')) {
                        menu.classList.remove('show');
                    }
                });
                
                // Toggle dropdown hiện tại
                const menu = toggle.nextElementSibling || toggle.parentElement.querySelector('.dropdown-menu');
                if (menu) {
                    menu.classList.toggle('show');
                    if (menu.classList.contains('show')) {
                        refreshFeatherIcons();
                    }
                }
            };
            
            document.addEventListener('click', window.dropdownClickHandler);
        }
    }
    
    // Hàm cập nhật Feather icons
    function refreshFeatherIcons() {
        if (typeof feather !== 'undefined') {
            setTimeout(() => {
                feather.replace({ 'stroke-width': 1.5 });
            }, 10);
        }
    }
    
    // Khởi tạo dropdown khi trang đã tải xong
    initializeDropdowns();

});

// Xem chi tiết người dùng
async function viewUser(id) {
    try {
        const response = await fetch(`<?= admin_url('users') ?>/${id}`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        })
        if (!response.ok) throw new Error('Không thể tải chi tiết người dùng')
        const { user } = await response.json()

        // Bind data to modal
        document.getElementById('viewUserId').textContent = user.id ?? '-'
        document.getElementById('viewUsername').textContent = user.username ?? '-'
        document.getElementById('viewEmail').textContent = user.email ?? '-'
        const statusEl = document.getElementById('viewStatus')
        const isActive = (user.status ?? 'active') === 'active'
        statusEl.textContent = isActive ? 'Hoạt động' : 'Không hoạt động'
        statusEl.className = 'badge ' + (isActive ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary')
        document.getElementById('viewCreatedAt').textContent = user.created_at ?? '-'
        document.getElementById('viewLastLogin').textContent = user.last_login_at ?? 'Chưa đăng nhập'

        const modal = new bootstrap.Modal(document.getElementById('viewUserModal'))
        modal.show()
    } catch (error) {
        console.error(error)
        showNotification('Không thể tải chi tiết người dùng', 'error')
    }
}

// Chỉnh sửa người dùng
async function editUser(id) {
    try {
        const response = await fetch(`<?= admin_url('users') ?>/${id}`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        })
        if (!response.ok) throw new Error('Không thể tải dữ liệu người dùng')
        const { user } = await response.json()

        // Bind to form
        document.getElementById('editUserId').value = user.id
        document.getElementById('editUsername').value = user.username ?? ''
        document.getElementById('editEmail').value = user.email ?? ''
        document.getElementById('editStatus').value = user.status ?? 'active'
        document.getElementById('editPassword').value = ''

        const modal = new bootstrap.Modal(document.getElementById('editUserModal'))
        modal.show()
    } catch (error) {
        console.error(error)
        showNotification('Không thể tải dữ liệu người dùng', 'error')
    }
}

// Toggle trạng thái người dùng
async function toggleStatus(id) {
    if (!confirm('Bạn có chắc chắn muốn thay đổi trạng thái của người dùng này?')) return;
    
    try {
        // Hiển thị trạng thái đang xử lý
        showNotification('Đang cập nhật trạng thái...', 'info');
        
        const response = await fetch(`<?= admin_url('users') ?>/${id}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        });
        
        if (!response.ok) {
            throw new Error('Không thể thay đổi trạng thái');
        }
        
        const result = await response.json();
        const newStatus = result.status === 'active' ? 'Hoạt động' : 'Không hoạt động';
        
        showNotification(`Cập nhật trạng thái thành công: ${newStatus}`, 'success');
        setTimeout(() => location.reload(), 1000);
    } catch (error) {
        console.error('Error:', error);
        showNotification('Không thể thay đổi trạng thái: ' + (error.message || 'Lỗi không xác định'), 'error');
    }
}

// Đặt lại mật khẩu
async function resetPassword(id) {
    if (!confirm('Bạn có chắc chắn muốn đặt lại mật khẩu cho người dùng này?')) return;
    
    try {
        // Hiển thị trạng thái đang xử lý
        showNotification('Đang đặt lại mật khẩu...', 'info');
        
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
        
        // Tạo modal hiển thị mật khẩu mới
        const modalHtml = `
            <div class="modal fade" id="passwordResetModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Mật khẩu mới</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-success">
                                <p class="mb-0">Đặt lại mật khẩu thành công!</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mật khẩu mới:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="${result.new_password}" id="newPasswordField" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('${result.new_password}')">
                                        <i data-feather="copy" class="icon-xs"></i> Sao chép
                                    </button>
                                </div>
                                <small class="text-muted mt-1 d-block">Vui lòng lưu lại mật khẩu này hoặc thông báo cho người dùng.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Thêm modal vào body nếu chưa tồn tại
        if (!document.getElementById('passwordResetModal')) {
            const modalContainer = document.createElement('div');
            modalContainer.innerHTML = modalHtml;
            document.body.appendChild(modalContainer);
        } else {
            document.getElementById('passwordResetModal').outerHTML = modalHtml;
        }
        
        // Hiển thị modal
        const resetModal = new bootstrap.Modal(document.getElementById('passwordResetModal'));
        resetModal.show();
        
        // Cập nhật Feather icons trong modal
        setTimeout(() => {
            if (typeof feather !== 'undefined') {
                feather.replace({ 'stroke-width': 1.5 });
            }
        }, 10);
        
        showNotification('Đặt lại mật khẩu thành công', 'success');
    } catch (error) {
        console.error('Error:', error);
        showNotification('Không thể đặt lại mật khẩu: ' + (error.message || 'Lỗi không xác định'), 'error');
    }
}

// Hàm sao chép vào clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text)
        .then(() => {
            showNotification('Đã sao chép mật khẩu vào clipboard', 'success');
        })
        .catch(err => {
            console.error('Không thể sao chép: ', err);
            showNotification('Không thể sao chép mật khẩu', 'error');
        });
}

// Xóa người dùng
async function deleteUser(id) {
    // Hiển thị modal xác nhận xóa
    const confirmModal = `
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Xác nhận xóa</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <i data-feather="alert-triangle" style="width: 50px; height: 50px; color: #dc3545;"></i>
                        </div>
                        <p class="mb-1">Bạn có chắc chắn muốn xóa người dùng này?</p>
                        <p class="text-danger mb-0"><strong>Lưu ý:</strong> Hành động này không thể hoàn tác!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                            <i data-feather="trash-2" class="icon-xs me-1"></i> Xóa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Thêm modal vào body nếu chưa tồn tại
    if (!document.getElementById('deleteConfirmModal')) {
        const modalContainer = document.createElement('div');
        modalContainer.innerHTML = confirmModal;
        document.body.appendChild(modalContainer);
    } else {
        document.getElementById('deleteConfirmModal').outerHTML = confirmModal;
    }
    
    // Hiển thị modal
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    modal.show();
    
    // Cập nhật Feather icons trong modal
    setTimeout(() => {
        if (typeof feather !== 'undefined') {
            feather.replace({ 'stroke-width': 1.5 });
        }
    }, 10);
    
    // Xử lý sự kiện khi người dùng xác nhận xóa
    document.getElementById('confirmDeleteBtn').onclick = async function() {
        try {
            // Đóng modal xác nhận
            modal.hide();
            
            // Hiển thị trạng thái đang xử lý
            showNotification('Đang xóa người dùng...', 'info');
            
            const response = await fetch(`<?= admin_url('users') ?>/${id}`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' }
            });
            
            if (!response.ok) {
                const err = await response.json().catch(() => ({}));
                throw new Error(err.error || 'Không thể xóa người dùng');
            }
            
            showNotification('Xóa tài khoản thành công', 'success');
            setTimeout(() => location.reload(), 1000);
        } catch (error) {
            console.error(error);
            showNotification(error.message || 'Không thể xóa người dùng', 'error');
        }
    };
}

// Submit form chỉnh sửa người dùng
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editUserForm')
    if (!form) return
    form.addEventListener('submit', async function(e) {
        e.preventDefault()
        const userId = document.getElementById('editUserId').value
        const payload = {
            username: document.getElementById('editUsername').value.trim(),
            email: document.getElementById('editEmail').value.trim(),
            status: document.getElementById('editStatus').value,
        }
        const password = document.getElementById('editPassword').value
        if (password && password.length > 0) payload.password = password

        const submitBtn = document.getElementById('editSubmitBtn')
        const originalHtml = submitBtn.innerHTML
        submitBtn.disabled = true
        submitBtn.innerHTML = '<i data-feather="loader" class="icon-xs me-1 spinner"></i> Đang lưu...'
        if (typeof feather !== 'undefined') feather.replace()

        try {
            const response = await fetch(`<?= admin_url('users') ?>/${userId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            if (!response.ok) {
                const err = await response.json().catch(() => ({}))
                throw new Error(err.error || 'Cập nhật thất bại')
            }
            showNotification('Cập nhật người dùng thành công', 'success')
            const modalEl = document.getElementById('editUserModal')
            const modal = bootstrap.Modal.getInstance(modalEl)
            if (modal) modal.hide()
            setTimeout(() => location.reload(), 1000)
        } catch (error) {
            console.error(error)
            showNotification(error.message || 'Cập nhật thất bại', 'error')
        } finally {
            submitBtn.disabled = false
            submitBtn.innerHTML = originalHtml
            if (typeof feather !== 'undefined') feather.replace()
        }
    })
})

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
