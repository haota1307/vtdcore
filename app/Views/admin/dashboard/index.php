

<div class="page-content">
    <div class="container-fluid">
<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Users
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats['total_users']) ?></div>
                    </div>
                    <div class="col-auto">
                        <i data-feather="users" class="text-gray-300" style="width: 2rem; height: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Orders
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats['total_orders']) ?></div>
                    </div>
                    <div class="col-auto">
                        <i data-feather="shopping-bag" class="text-gray-300" style="width: 2rem; height: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Revenue
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">$<?= number_format($stats['total_revenue'], 2) ?></div>
                    </div>
                    <div class="col-auto">
                        <i data-feather="dollar-sign" class="text-gray-300" style="width: 2rem; height: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Active Sessions
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats['active_sessions']) ?></div>
                    </div>
                    <div class="col-auto">
                        <i data-feather="activity" class="text-gray-300" style="width: 2rem; height: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Recent Activities -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                <a href="<?= admin_url('logs') ?>" class="btn btn-sm btn-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($recent_activities)): ?>
                    <div class="timeline">
                        <?php foreach ($recent_activities as $activity): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-<?= $activity['color'] ?>">
                                    <i data-feather="<?= $activity['icon'] ?>"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title"><?= esc($activity['message']) ?></h6>
                                    <p class="timeline-text text-muted">
                                        <i data-feather="clock" class="me-1"></i>
                                        <?= format_datetime($activity['time']) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i data-feather="inbox" class="text-muted" style="width: 3rem; height: 3rem;"></i>
                        <p class="text-muted mt-2">No recent activities</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <?php foreach ($quick_actions as $action): ?>
                    <a href="<?= $action['url'] ?>" class="btn btn-outline-<?= $action['color'] ?> w-100 mb-2">
                        <i data-feather="<?= $action['icon'] ?>" class="me-2"></i>
                        <?= $action['title'] ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- System Information -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">System Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>PHP Version:</strong></td>
                                <td><?= $system_info['php_version'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>CodeIgniter Version:</strong></td>
                                <td><?= $system_info['codeigniter_version'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Framework Version:</strong></td>
                                <td><?= $system_info['framework_version'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Server Software:</strong></td>
                                <td><?= $system_info['server_software'] ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Memory Usage:</strong></td>
                                <td><?= $system_info['memory_usage'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Peak Memory:</strong></td>
                                <td><?= $system_info['peak_memory'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Disk Free Space:</strong></td>
                                <td><?= $system_info['disk_free_space'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>System Uptime:</strong></td>
                                <td><?= $system_info['uptime'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: -2.5rem;
    top: 0.25rem;
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.75rem;
}

.timeline-content {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.375rem;
    border-left: 4px solid #dee2e6;
}

.timeline-title {
    margin: 0;
    font-size: 0.875rem;
    font-weight: 600;
}

.timeline-text {
    margin: 0.5rem 0 0 0;
    font-size: 0.75rem;
}

.bg-success {
    background-color: #28a745 !important;
}

.bg-info {
    background-color: #17a2b8 !important;
}

.bg-warning {
    background-color: #ffc107 !important;
}

.bg-danger {
    background-color: #dc3545 !important;
}
</style>