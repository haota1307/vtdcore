<div class="page-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Permissions Management</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                            <i class="ri-add-line align-middle me-1"></i> Add New Permission
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
                                        <i class="ri-lock-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Total Permissions</p>
                                <h4 class="mb-0"><?= count($permissions) ?></h4>
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
                                        <i class="ri-shield-check-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Active Permissions</p>
                                <h4 class="mb-0"><?= count(array_filter($permissions, fn($p) => ($p['status'] ?? 'active') === 'active')) ?></h4>
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
                                        <i class="ri-folder-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Permission Groups</p>
                                <h4 class="mb-0"><?= count(array_unique(array_column($permissions, 'group'))) ?></h4>
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
                                        <i class="ri-shield-user-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Roles Using</p>
                                <h4 class="mb-0"><?= $rolesUsingPermissions ?? 0 ?></h4>
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
                                <input type="text" class="form-control" id="searchPermission" placeholder="Search permissions...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Group</label>
                                <select class="form-select" id="groupFilter">
                                    <option value="">All Groups</option>
                                    <?php foreach ($permissionGroups ?? [] as $group): ?>
                                    <option value="<?= esc($group) ?>"><?= esc($group) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-primary w-100" onclick="filterPermissions()">
                                    <i class="ri-search-line"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm">
                                <div>
                                    <h5 class="card-title mb-0">Permissions List</h5>
                                    <p class="text-muted mb-0">Manage system permissions and access controls</p>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="d-flex gap-1">
                                    <button class="btn btn-soft-info" type="button" onclick="exportPermissions()">
                                        <i class="ri-file-download-line align-bottom"></i> Export
                                    </button>
                                    <button class="btn btn-soft-warning" type="button" onclick="bulkAssign()">
                                        <i class="ri-shield-check-line align-bottom"></i> Bulk Assign
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-nowrap align-middle mb-0" id="permissionsTable">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                            </div>
                                        </th>
                                        <th scope="col">ID</th>
                                        <th scope="col">Permission Name</th>
                                        <th scope="col">Slug</th>
                                        <th scope="col">Group</th>
                                        <th scope="col">Roles</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Created</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($permissions)): foreach ($permissions as $permission): ?>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input permission-checkbox" type="checkbox" value="<?= $permission['id'] ?>">
                                            </div>
                                        </td>
                                        <td class="fw-medium"><?= esc($permission['id']) ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title bg-soft-warning text-warning rounded">
                                                            <i class="ri-lock-line"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0"><?= esc($permission['name']) ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><code><?= esc($permission['slug']) ?></code></td>
                                        <td>
                                            <span class="badge bg-soft-info text-info"><?= esc($permission['group'] ?? 'General') ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-success text-success"><?= $permission['role_count'] ?? 0 ?> roles</span>
                                        </td>
                                        <td>
                                            <?php $status = $permission['status'] ?? 'active'; ?>
                                            <span class="badge bg-<?= $status === 'active' ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($status) ?>
                                            </span>
                                        </td>
                                        <td><?= esc($permission['created_at'] ?? 'N/A') ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-soft-secondary btn-sm dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#" onclick="editPermission(<?= $permission['id'] ?>)">
                                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="viewRoles(<?= $permission['id'] ?>)">
                                                        <i class="ri-shield-user-line align-bottom me-2 text-muted"></i> View Roles
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="deletePermission(<?= $permission['id'] ?>)">
                                                        <i class="ri-delete-bin-fill align-bottom me-2"></i> Delete
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <div class="d-flex justify-content-center">
                                                <div class="text-center">
                                                    <div class="avatar-md mx-auto mb-3">
                                                        <div class="avatar-title bg-soft-warning text-warning rounded-circle fs-24">
                                                            <i class="ri-lock-line"></i>
                                                        </div>
                                                    </div>
                                                    <h5>No Permissions Found</h5>
                                                    <p class="text-muted">Get started by creating your first permission.</p>
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                                                        <i class="ri-add-line align-middle me-1"></i> Create First Permission
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Permission Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-labelledby="addPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPermissionModalLabel">Add New Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addPermissionForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="permissionName" class="form-label">Permission Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="permissionName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="permissionSlug" class="form-label">Permission Slug <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="permissionSlug" name="slug" required>
                        <div class="form-text">Unique identifier (e.g., users.view, admin.manage)</div>
                    </div>
                    <div class="mb-3">
                        <label for="permissionGroup" class="form-label">Group</label>
                        <select class="form-select" id="permissionGroup" name="group">
                            <option value="">General</option>
                            <option value="users">Users</option>
                            <option value="admin">Admin</option>
                            <option value="media">Media</option>
                            <option value="settings">Settings</option>
                            <option value="audit">Audit</option>
                            <option value="rbac">RBAC</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="permissionDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="permissionDescription" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Permission</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Assign Modal -->
<div class="modal fade" id="bulkAssignModal" tabindex="-1" aria-labelledby="bulkAssignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkAssignModalLabel">Bulk Assign Permissions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulkAssignForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Selected Permissions</label>
                        <div id="selectedPermissionsList" class="border rounded p-3 bg-light">
                            <p class="text-muted mb-0">No permissions selected</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="targetRole" class="form-label">Assign to Role</label>
                        <select class="form-select" id="targetRole" name="role_id" required>
                            <option value="">Select a role...</option>
                            <?php foreach ($roles ?? [] as $role): ?>
                            <option value="<?= $role['id'] ?>"><?= esc($role['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Permissions</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-generate slug from name
document.getElementById('permissionName').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9]+/g, '.')
        .replace(/^\.+|\.+$/g, '');
    document.getElementById('permissionSlug').value = slug;
});

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.permission-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectedPermissions();
});

// Update selected permissions when checkboxes change
document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedPermissions);
});

function updateSelectedPermissions() {
    const selected = document.querySelectorAll('.permission-checkbox:checked');
    const list = document.getElementById('selectedPermissionsList');
    
    if (selected.length === 0) {
        list.innerHTML = '<p class="text-muted mb-0">No permissions selected</p>';
    } else {
        let html = '<div class="d-flex flex-wrap gap-1">';
        selected.forEach(checkbox => {
            const row = checkbox.closest('tr');
            const name = row.querySelector('h6').textContent;
            html += `<span class="badge bg-soft-primary">${name}</span>`;
        });
        html += '</div>';
        list.innerHTML = html;
    }
}

// Handle form submission
document.getElementById('addPermissionForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    try {
        const response = await fetch('/admin/permissions/create', {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            location.reload();
        } else {
            alert('Failed to create permission');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred');
    }
});

function filterPermissions() {
    const search = document.getElementById('searchPermission').value.toLowerCase();
    const group = document.getElementById('groupFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    const rows = document.querySelectorAll('#permissionsTable tbody tr');
    
    rows.forEach(row => {
        const name = row.querySelector('h6')?.textContent.toLowerCase() || '';
        const slug = row.querySelector('code')?.textContent.toLowerCase() || '';
        const groupCell = row.querySelector('.badge')?.textContent || '';
        const statusCell = row.querySelectorAll('.badge')[1]?.textContent.toLowerCase() || '';
        
        const matchesSearch = name.includes(search) || slug.includes(search);
        const matchesGroup = !group || groupCell.includes(group);
        const matchesStatus = !status || statusCell.includes(status);
        
        row.style.display = (matchesSearch && matchesGroup && matchesStatus) ? '' : 'none';
    });
}

function exportPermissions() {
    // TODO: Implement export functionality
    alert('Export functionality will be implemented');
}

function bulkAssign() {
    const selected = document.querySelectorAll('.permission-checkbox:checked');
    if (selected.length === 0) {
        alert('Please select permissions to assign');
        return;
    }
    
    updateSelectedPermissions();
    new bootstrap.Modal(document.getElementById('bulkAssignModal')).show();
}

function editPermission(permissionId) {
    // TODO: Implement edit functionality
    alert('Edit permission ' + permissionId);
}

function viewRoles(permissionId) {
    // TODO: Implement roles view
    window.location.href = `/admin/permissions/${permissionId}/roles`;
}

function deletePermission(permissionId) {
    if (confirm('Are you sure you want to delete this permission?')) {
        // TODO: Implement delete functionality
        alert('Delete permission ' + permissionId);
    }
}
</script>
