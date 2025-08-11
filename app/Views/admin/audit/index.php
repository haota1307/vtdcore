<div class="page-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Audit Logs</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-info" onclick="exportLogs()">
                            <i class="ri-download-line align-middle me-1"></i> Export Logs
                        </button>
                    </div>
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
                                        <i class="ri-file-list-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Total Logs</p>
                                <h4 class="mb-0"><?= $meta['total'] ?? 0 ?></h4>
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
                                        <i class="ri-user-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Active Users</p>
                                <h4 class="mb-0"><?= $activeUsers ?? 0 ?></h4>
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
                                        <i class="ri-alert-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Failed Logins</p>
                                <h4 class="mb-0"><?= $failedLogins ?? 0 ?></h4>
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
                                        <i class="ri-time-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Today's Activity</p>
                                <h4 class="mb-0"><?= $todayActivity ?? 0 ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control" id="searchLogs" placeholder="Search logs..." value="<?= esc($q ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">User</label>
                                <select class="form-select" id="userFilter">
                                    <option value="">All Users</option>
                                    <?php foreach ($users ?? [] as $user): ?>
                                    <option value="<?= $user['id'] ?>" <?= ($filter_user_id ?? '') == $user['id'] ? 'selected' : '' ?>>
                                        <?= esc($user['username']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Action Type</label>
                                <select class="form-select" id="actionFilter">
                                    <option value="">All Actions</option>
                                    <option value="login">Login</option>
                                    <option value="logout">Logout</option>
                                    <option value="create">Create</option>
                                    <option value="update">Update</option>
                                    <option value="delete">Delete</option>
                                    <option value="upload">Upload</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-primary w-100" onclick="filterLogs()">
                                    <i class="ri-search-line"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit Logs Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm">
                                <div>
                                    <h5 class="card-title mb-0">Audit Logs</h5>
                                    <p class="text-muted mb-0">System activity and security logs</p>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="d-flex gap-1">
                                    <button class="btn btn-soft-danger" type="button" onclick="clearLogs()">
                                        <i class="ri-delete-bin-line align-bottom"></i> Clear Logs
                                    </button>
                                    <button class="btn btn-soft-info" type="button" onclick="refreshLogs()">
                                        <i class="ri-refresh-line align-bottom"></i> Refresh
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-nowrap align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">User</th>
                                        <th scope="col">Action</th>
                                        <th scope="col">Details</th>
                                        <th scope="col">IP Address</th>
                                        <th scope="col">User Agent</th>
                                        <th scope="col">Timestamp</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($logs)): foreach ($logs as $log): ?>
                                    <tr>
                                        <td class="fw-medium"><?= esc($log['id']) ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title bg-soft-primary text-primary rounded">
                                                            <i class="ri-user-line"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0"><?= esc($log['username'] ?? 'System') ?></h6>
                                                    <small class="text-muted"><?= esc($log['email'] ?? '') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php 
                                            $action = $log['action'];
                                            $actionClass = 'secondary';
                                            if (str_contains($action, 'login')) $actionClass = 'success';
                                            elseif (str_contains($action, 'logout')) $actionClass = 'warning';
                                            elseif (str_contains($action, 'delete')) $actionClass = 'danger';
                                            elseif (str_contains($action, 'create')) $actionClass = 'primary';
                                            elseif (str_contains($action, 'update')) $actionClass = 'info';
                                            ?>
                                            <span class="badge bg-<?= $actionClass ?>"><?= esc($action) ?></span>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="<?= esc($log['context'] ?? '') ?>">
                                                <?= esc($log['context'] ?? 'No details') ?>
                                            </div>
                                        </td>
                                        <td>
                                            <code class="small"><?= esc($log['ip_address'] ?? 'N/A') ?></code>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 150px;" title="<?= esc($log['user_agent'] ?? '') ?>">
                                                <?= esc($log['user_agent'] ?? 'N/A') ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium"><?= date('M d, Y', strtotime($log['created_at'])) ?></span>
                                                <small class="text-muted"><?= date('H:i:s', strtotime($log['created_at'])) ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-soft-secondary btn-sm dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#" onclick="viewLogDetails(<?= $log['id'] ?>)">
                                                        <i class="ri-eye-line align-bottom me-2 text-muted"></i> View Details
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="exportLog(<?= $log['id'] ?>)">
                                                        <i class="ri-download-line align-bottom me-2 text-muted"></i> Export
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteLog(<?= $log['id'] ?>)">
                                                        <i class="ri-delete-bin-line align-bottom me-2"></i> Delete
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <div class="d-flex justify-content-center">
                                                <div class="text-center">
                                                    <div class="avatar-md mx-auto mb-3">
                                                        <div class="avatar-title bg-soft-info text-info rounded-circle fs-24">
                                                            <i class="ri-file-list-line"></i>
                                                        </div>
                                                    </div>
                                                    <h5>No Audit Logs Found</h5>
                                                    <p class="text-muted">No activity has been logged yet.</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if (isset($pagerObj) && $pagerObj): ?>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datatable_info" role="status" aria-live="polite">
                                    Showing <?= $meta['page'] * $meta['per_page'] - $meta['per_page'] + 1 ?> to <?= min($meta['page'] * $meta['per_page'], $meta['total']) ?> of <?= $meta['total'] ?> entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="datatable_paginate">
                                    <?= $pagerObj->links() ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1" aria-labelledby="logDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logDetailsModalLabel">Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="logDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="exportLogDetails()">Export Details</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentLogId = null;

function filterLogs() {
    const search = document.getElementById('searchLogs').value;
    const user = document.getElementById('userFilter').value;
    const action = document.getElementById('actionFilter').value;
    
    const params = new URLSearchParams();
    if (search) params.append('q', search);
    if (user) params.append('user_id', user);
    if (action) params.append('action', action);
    
    window.location.href = '/admin/audit?' + params.toString();
}

function exportLogs() {
    const search = document.getElementById('searchLogs').value;
    const user = document.getElementById('userFilter').value;
    const action = document.getElementById('actionFilter').value;
    
    const params = new URLSearchParams();
    if (search) params.append('q', search);
    if (user) params.append('user_id', user);
    if (action) params.append('action', action);
    params.append('export', '1');
    
    window.location.href = '/admin/audit/export?' + params.toString();
}

function refreshLogs() {
    location.reload();
}

function clearLogs() {
    if (confirm('Are you sure you want to clear all audit logs? This action cannot be undone.')) {
        fetch('/admin/audit/clear', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Logs cleared successfully!');
                location.reload();
            } else {
                alert('Failed to clear logs: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while clearing logs');
        });
    }
}

function viewLogDetails(logId) {
    currentLogId = logId;
    
    fetch(`/admin/audit/${logId}/details`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const content = document.getElementById('logDetailsContent');
            content.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Basic Information</h6>
                        <table class="table table-sm">
                            <tr><td><strong>ID:</strong></td><td>${data.log.id}</td></tr>
                            <tr><td><strong>User:</strong></td><td>${data.log.username || 'System'}</td></tr>
                            <tr><td><strong>Action:</strong></td><td><span class="badge bg-primary">${data.log.action}</span></td></tr>
                            <tr><td><strong>IP Address:</strong></td><td><code>${data.log.ip_address || 'N/A'}</code></td></tr>
                            <tr><td><strong>Timestamp:</strong></td><td>${data.log.created_at}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Context Details</h6>
                        <div class="border rounded p-3 bg-light">
                            <pre class="mb-0">${data.log.context || 'No details available'}</pre>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>User Agent</h6>
                        <div class="border rounded p-3 bg-light">
                            <code class="small">${data.log.user_agent || 'N/A'}</code>
                        </div>
                    </div>
                </div>
            `;
            
            new bootstrap.Modal(document.getElementById('logDetailsModal')).show();
        } else {
            alert('Failed to load log details: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while loading log details');
    });
}

function exportLog(logId) {
    window.location.href = `/admin/audit/${logId}/export`;
}

function exportLogDetails() {
    if (currentLogId) {
        window.location.href = `/admin/audit/${currentLogId}/export`;
    }
}

function deleteLog(logId) {
    if (confirm('Are you sure you want to delete this log entry?')) {
        fetch(`/admin/audit/${logId}/delete`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Log deleted successfully!');
                location.reload();
            } else {
                alert('Failed to delete log: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting log');
        });
    }
}

// Auto-refresh every 30 seconds
setInterval(function() {
    // Only auto-refresh if no filters are applied
    const search = document.getElementById('searchLogs').value;
    const user = document.getElementById('userFilter').value;
    const action = document.getElementById('actionFilter').value;
    
    if (!search && !user && !action) {
        // Refresh silently
        fetch('/admin/audit?ajax=1')
        .then(response => response.text())
        .then(html => {
            // Update only the table body
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTableBody = doc.querySelector('tbody');
            const currentTableBody = document.querySelector('tbody');
            if (newTableBody && currentTableBody) {
                currentTableBody.innerHTML = newTableBody.innerHTML;
            }
        })
        .catch(error => {
            console.error('Auto-refresh error:', error);
        });
    }
}, 30000);
</script>
