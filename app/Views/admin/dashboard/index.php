<div class="page-content">
    <div class="container-fluid">
        <!-- Thống kê tổng quan -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-content">
                            <div class="stat-icon bg-primary-subtle">
                                <i data-feather="users"></i>
                            </div>
                            <div class="stat-details">
                                <h3 class="stat-value"><?= number_format($stats['total_users']) ?></h3>
                                <p class="stat-label">Tổng người dùng</p>
                            </div>
                        </div>
                        <div class="progress stat-progress">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-content">
                            <div class="stat-icon bg-success-subtle">
                                <i data-feather="shopping-bag"></i>
                            </div>
                            <div class="stat-details">
                                <h3 class="stat-value"><?= number_format($stats['total_orders']) ?></h3>
                                <p class="stat-label">Tổng đơn hàng</p>
                            </div>
                        </div>
                        <div class="progress stat-progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-content">
                            <div class="stat-icon bg-info-subtle">
                                <i data-feather="dollar-sign"></i>
                            </div>
                            <div class="stat-details">
                                <h3 class="stat-value">$<?= number_format($stats['total_revenue'], 2) ?></h3>
                                <p class="stat-label">Tổng doanh thu</p>
                            </div>
                        </div>
                        <div class="progress stat-progress">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-content">
                            <div class="stat-icon bg-warning-subtle">
                                <i data-feather="activity"></i>
                            </div>
                            <div class="stat-details">
                                <h3 class="stat-value"><?= number_format($stats['active_sessions']) ?></h3>
                                <p class="stat-label">Phiên hoạt động</p>
                            </div>
                        </div>
                        <div class="progress stat-progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nội dung chính -->
        <div class="row">
            <!-- Hoạt động gần đây -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">
                                <i data-feather="activity" class="icon-sm me-2 text-primary"></i>
                                Hoạt động gần đây
                            </h5>
                            <a href="<?= admin_url('logs') ?>" class="btn btn-sm btn-primary">
                                <i data-feather="list" class="icon-xs me-1"></i> Xem tất cả
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($recent_activities)): ?>
                            <div class="activity-timeline p-3">
                                <?php foreach ($recent_activities as $activity): ?>
                                    <div class="activity-item">
                                        <div class="activity-dot bg-<?= $activity['color'] ?>">
                                            <i data-feather="<?= $activity['icon'] ?>"></i>
                                        </div>
                                        <div class="activity-content">
                                            <div class="d-flex align-items-center mb-1">
                                                <h6 class="mb-0 me-auto"><?= esc($activity['message']) ?></h6>
                                                <span class="badge bg-light text-dark rounded-pill">
                                                    <i data-feather="clock" class="icon-xs me-1"></i>
                                                    <?= format_datetime($activity['time']) ?>
                                                </span>
                                            </div>
                                            <div class="activity-info text-muted">
                                                <?= isset($activity['details']) ? esc($activity['details']) : '' ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <i data-feather="inbox" class="text-muted mb-3" style="width: 3rem; height: 3rem;"></i>
                                    <p class="text-muted">Không có hoạt động gần đây</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Thao tác nhanh -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i data-feather="zap" class="icon-sm me-2 text-primary"></i>
                            Thao tác nhanh
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions">
                            <?php foreach ($quick_actions as $action): ?>
                                <a href="<?= $action['url'] ?>" class="quick-action-btn btn-<?= $action['color'] ?>">
                                    <div class="quick-action-icon">
                                        <i data-feather="<?= $action['icon'] ?>"></i>
                                    </div>
                                    <div class="quick-action-text">
                                        <?= $action['title'] ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin hệ thống -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i data-feather="server" class="icon-sm me-2 text-primary"></i>
                            Thông tin hệ thống
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="system-info-group">
                                    <div class="system-info-item">
                                        <div class="system-info-label">Phiên bản PHP</div>
                                        <div class="system-info-value">
                                            <span class="badge bg-primary"><?= $system_info['php_version'] ?></span>
                                        </div>
                                    </div>
                                    <div class="system-info-item">
                                        <div class="system-info-label">Phiên bản CodeIgniter</div>
                                        <div class="system-info-value">
                                            <span class="badge bg-success"><?= $system_info['codeigniter_version'] ?></span>
                                        </div>
                                    </div>
                                    <div class="system-info-item">
                                        <div class="system-info-label">Phiên bản Framework</div>
                                        <div class="system-info-value">
                                            <span class="badge bg-info"><?= $system_info['framework_version'] ?></span>
                                        </div>
                                    </div>
                                    <div class="system-info-item">
                                        <div class="system-info-label">Phần mềm máy chủ</div>
                                        <div class="system-info-value">
                                            <span class="badge bg-secondary"><?= $system_info['server_software'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="system-info-group">
                                    <div class="system-info-item">
                                        <div class="system-info-label">Bộ nhớ sử dụng</div>
                                        <div class="system-info-value">
                                            <div class="progress" style="height: 8px; width: 100px;">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="ms-2"><?= $system_info['memory_usage'] ?></span>
                                        </div>
                                    </div>
                                    <div class="system-info-item">
                                        <div class="system-info-label">Bộ nhớ tối đa</div>
                                        <div class="system-info-value">
                                            <div class="progress" style="height: 8px; width: 100px;">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="ms-2"><?= $system_info['peak_memory'] ?></span>
                                        </div>
                                    </div>
                                    <div class="system-info-item">
                                        <div class="system-info-label">Dung lượng trống</div>
                                        <div class="system-info-value">
                                            <div class="progress" style="height: 8px; width: 100px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="ms-2"><?= $system_info['disk_free_space'] ?></span>
                                        </div>
                                    </div>
                                    <div class="system-info-item">
                                        <div class="system-info-label">Thời gian hoạt động</div>
                                        <div class="system-info-value">
                                            <i data-feather="clock" class="icon-xs text-info me-1"></i>
                                            <?= $system_info['uptime'] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Card thống kê */
.stat-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.stat-content {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.stat-icon svg {
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

.bg-info-subtle {
    background-color: rgba(54, 185, 204, 0.15);
    color: #36b9cc;
}

.bg-warning-subtle {
    background-color: rgba(246, 194, 62, 0.15);
    color: #f6c23e;
}

.stat-details {
    flex: 1;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    line-height: 1.2;
}

.stat-label {
    color: #6c757d;
    font-size: 0.875rem;
    margin: 0;
}

.stat-progress {
    height: 6px;
    border-radius: 3px;
    background-color: #f1f1f1;
}

/* Hoạt động gần đây */
.activity-timeline {
    position: relative;
}

.activity-item {
    position: relative;
    padding-left: 40px;
    padding-bottom: 20px;
    border-left: 1px dashed #dee2e6;
    margin-left: 20px;
}

.activity-item:last-child {
    padding-bottom: 0;
    border-left: none;
}

.activity-dot {
    position: absolute;
    left: -10px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.activity-dot svg {
    width: 12px;
    height: 12px;
}

.activity-content {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
    border-left: 3px solid #dee2e6;
}

.bg-success {
    background-color: #1cc88a !important;
}

.bg-info {
    background-color: #36b9cc !important;
}

.bg-warning {
    background-color: #f6c23e !important;
}

.bg-danger {
    background-color: #e74a3b !important;
}

/* Thao tác nhanh */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 15px 10px;
    border-radius: 10px;
    text-decoration: none;
    transition: transform 0.2s, box-shadow 0.2s;
}

.quick-action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
}

.quick-action-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
}

.quick-action-icon svg {
    width: 20px;
    height: 20px;
}

.quick-action-text {
    font-size: 0.875rem;
    font-weight: 500;
    text-align: center;
}

.btn-primary {
    background-color: rgba(78, 115, 223, 0.1);
    color: #4e73df;
    border: none;
}

.btn-success {
    background-color: rgba(28, 200, 138, 0.1);
    color: #1cc88a;
    border: none;
}

.btn-info {
    background-color: rgba(54, 185, 204, 0.1);
    color: #36b9cc;
    border: none;
}

.btn-warning {
    background-color: rgba(246, 194, 62, 0.1);
    color: #f6c23e;
    border: none;
}

.btn-danger {
    background-color: rgba(231, 74, 59, 0.1);
    color: #e74a3b;
    border: none;
}

.btn-secondary {
    background-color: rgba(133, 135, 150, 0.1);
    color: #858796;
    border: none;
}

/* Thông tin hệ thống */
.system-info-group {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.system-info-item {
    display: flex;
    align-items: center;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 8px;
}

.system-info-label {
    min-width: 180px;
    font-weight: 500;
    color: #495057;
}

.system-info-value {
    display: flex;
    align-items: center;
}

.icon-xs {
    width: 16px;
    height: 16px;
}

.icon-sm {
    width: 18px;
    height: 18px;
}

.empty-state {
    padding: 30px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,.05);
}

.card-title {
    color: #495057;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 768px) {
    .quick-actions {
        grid-template-columns: 1fr;
    }
    
    .system-info-item {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .system-info-label {
        margin-bottom: 5px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace({ 'stroke-width': 1.5 });
    }
    
    // Animation cho các card thống kê
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>