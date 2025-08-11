<div class="page-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Roles Management</h4>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Left Sidebar - Roles List -->
            <div class="col-lg-4 col-xl-3">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Roles</h5>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                <i class="ri-add-line align-middle"></i> Add Role
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush" id="rolesList">
                            <?php if (!empty($roles)): foreach ($roles as $role): ?>
                            <div class="list-group-item list-group-item-action role-item" data-role-id="<?= $role['id'] ?>" role="button">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-soft-primary text-primary rounded">
                                                    <i class="ri-shield-user-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?= esc($role['name']) ?></h6>
                                            <p class="text-muted mb-0 small"><?= esc($role['description'] ?? 'No description') ?></p>
                                            <div class="mt-1">
                                                <span class="badge bg-soft-<?= ($role['status'] ?? 'active') === 'active' ? 'success' : 'secondary' ?> text-<?= ($role['status'] ?? 'active') === 'active' ? 'success' : 'secondary' ?>">
                                                    <?= ucfirst($role['status'] ?? 'active') ?>
                                                </span>
                                                <span class="badge bg-soft-info text-info ms-1"><?= $role['user_count'] ?? 0 ?> users</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="dropdown">
                                            <button class="btn btn-link btn-sm text-muted p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#" onclick="editRole(<?= $role['id'] ?>)">
                                                    <i class="ri-pencil-fill align-bottom me-2"></i> Edit
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" onclick="toggleRoleStatus(<?= $role['id'] ?>, '<?= $role['status'] ?? 'active' ?>')">
                                                    <i class="ri-toggle-<?= ($role['status'] ?? 'active') === 'active' ? 'left' : 'right' ?>-fill align-bottom me-2"></i> 
                                                    <?= ($role['status'] ?? 'active') === 'active' ? 'Deactivate' : 'Activate' ?>
                                                </a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteRole(<?= $role['id'] ?>)">
                                                    <i class="ri-delete-bin-fill align-bottom me-2"></i> Delete
                                                </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; else: ?>
                            <div class="list-group-item text-center py-4">
                                <div class="avatar-md mx-auto mb-3">
                                    <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-24">
                                        <i class="ri-shield-user-line"></i>
                                    </div>
                                </div>
                                <h6>No Roles Found</h6>
                                <p class="text-muted small">Get started by creating your first role.</p>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                    <i class="ri-add-line align-middle me-1"></i> Create First Role
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content Area - Tabs -->
            <div class="col-lg-8 col-xl-9">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="roleTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                                    <i class="ri-information-line align-bottom me-1"></i> Info
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button" role="tab">
                                    <i class="ri-lock-line align-bottom me-1"></i> Permissions
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                                    <i class="ri-user-line align-bottom me-1"></i> Users
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="roleTabsContent">
                            <!-- Info Tab -->
                            <div class="tab-pane fade show active" id="info" role="tabpanel">
                                <div id="roleInfoContent" class="text-center py-5">
                                    <div class="avatar-md mx-auto mb-3">
                                        <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-24">
                                            <i class="ri-shield-user-line"></i>
                                        </div>
                                    </div>
                                    <h5>Select a Role</h5>
                                    <p class="text-muted">Choose a role from the left sidebar to view its details.</p>
                                </div>
                            </div>

                            <!-- Permissions Tab -->
                            <div class="tab-pane fade" id="permissions" role="tabpanel">
                                <div id="rolePermissionsContent" class="text-center py-5">
                                    <div class="avatar-md mx-auto mb-3">
                                        <div class="avatar-title bg-soft-warning text-warning rounded-circle fs-24">
                                            <i class="ri-lock-line"></i>
                                        </div>
                                    </div>
                                    <h5>Select a Role</h5>
                                    <p class="text-muted">Choose a role from the left sidebar to manage its permissions.</p>
                                </div>
                            </div>

                            <!-- Users Tab -->
                            <div class="tab-pane fade" id="users" role="tabpanel">
                                <div id="roleUsersContent" class="text-center py-5">
                                    <div class="avatar-md mx-auto mb-3">
                                        <div class="avatar-title bg-soft-info text-info rounded-circle fs-24">
                                            <i class="ri-user-line"></i>
                                        </div>
                                    </div>
                                    <h5>Select a Role</h5>
                                    <p class="text-muted">Choose a role from the left sidebar to manage its users.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoleModalLabel">Add New Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addRoleForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="roleName" class="form-label">Role Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="roleName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="roleSlug" class="form-label">Role Slug <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="roleSlug" name="slug" required>
                        <div class="form-text">Unique identifier for the role (e.g., admin, editor, user)</div>
                    </div>
                    <div class="mb-3">
                        <label for="roleDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="roleDescription" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRoleForm">
                <div class="modal-body">
                    <input type="hidden" id="editRoleId" name="id">
                    <div class="mb-3">
                        <label for="editRoleName" class="form-label">Role Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editRoleName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editRoleSlug" class="form-label">Role Slug <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editRoleSlug" name="slug" required>
                        <div class="form-text">Unique identifier for the role (e.g., admin, editor, user)</div>
                    </div>
                    <div class="mb-3">
                        <label for="editRoleDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editRoleDescription" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Custom styles for the new layout */
.role-item {
    cursor: pointer;
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
}

.role-item:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.05);
    border-left-color: var(--bs-primary);
}

.role-item.active {
    background-color: rgba(var(--bs-primary-rgb), 0.1);
    border-left-color: var(--bs-primary);
}

.role-item .dropdown {
    opacity: 0;
    transition: opacity 0.2s ease;
}

.role-item:hover .dropdown,
.role-item.active .dropdown {
    opacity: 1;
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: var(--bs-gray-600);
    font-weight: 500;
}

.nav-tabs .nav-link:hover {
    border-bottom-color: var(--bs-gray-300);
    color: var(--bs-gray-700);
}

.nav-tabs .nav-link.active {
    border-bottom-color: var(--bs-primary);
    color: var(--bs-primary);
    background: none;
}

.tab-content {
    min-height: 400px;
}

.permission-group-toggle {
    margin-right: 0.5rem;
}

.permission-group-toggle:indeterminate {
    background-color: var(--bs-warning);
    border-color: var(--bs-warning);
}

.card-header[data-bs-toggle="collapse"] {
    cursor: pointer;
}

.card-header[data-bs-toggle="collapse"]:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.05);
}

.card-header[data-bs-toggle="collapse"] i {
    transition: transform 0.2s ease;
}

.card-header[data-bs-toggle="collapse"][aria-expanded="false"] i {
    transform: rotate(-90deg);
}

.user-checkbox:checked {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
}

.bulk-actions {
    background-color: var(--bs-light);
    border-radius: 0.375rem;
    padding: 0.75rem;
    margin-bottom: 1rem;
}

@media (max-width: 991.98px) {
    .col-lg-4.col-xl-3 {
        margin-bottom: 1rem;
    }
}
</style>

<script>
let currentRoleId = null;

// Auto-generate slug from name
document.getElementById('roleName').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
    document.getElementById('roleSlug').value = slug;
});

// Auto-generate slug from name (edit form)
document.getElementById('editRoleName').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
    document.getElementById('editRoleSlug').value = slug;
});

// Handle role item click
document.addEventListener('click', function(e) {
    const roleItem = e.target.closest('.role-item');
    if (roleItem) {
        const roleId = roleItem.dataset.roleId;
        if (roleId) {
            selectRole(roleId);
        }
    }
});

// Select role and load data
async function selectRole(roleId) {
    currentRoleId = roleId;
    
    // Update active state
    document.querySelectorAll('.role-item').forEach(item => {
        item.classList.remove('active');
    });
    document.querySelector(`[data-role-id="${roleId}"]`).classList.add('active');
    
    // Load role data
    await loadRoleInfo(roleId);
    await loadRolePermissions(roleId);
    await loadRoleUsers(roleId);
}

// Load role info
async function loadRoleInfo(roleId) {
    try {
        const response = await fetch(`/admin/roles/${roleId}/data`, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        const role = await response.json();
        
        if (response.ok) {
            const infoContent = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Role Name</label>
                            <p class="form-control-plaintext">${role.name}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Slug</label>
                            <p class="form-control-plaintext"><code>${role.slug}</code></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <p class="form-control-plaintext">${role.description || 'No description'}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <div class="d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="roleStatusToggle" 
                                           ${(role.status || 'active') === 'active' ? 'checked' : ''}
                                           onchange="toggleRoleStatus(${roleId}, this.checked ? 'active' : 'inactive')">
                                    <label class="form-check-label" for="roleStatusToggle">
                                        ${(role.status || 'active') === 'active' ? 'Active' : 'Inactive'}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Created At</label>
                            <p class="form-control-plaintext">${role.created_at || 'N/A'}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Users Count</label>
                            <p class="form-control-plaintext">${role.user_count || 0} users</p>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('roleInfoContent').innerHTML = infoContent;
        }
    } catch (error) {
        console.error('Error loading role info:', error);
    }
}

// Load role permissions
async function loadRolePermissions(roleId) {
    try {
        const response = await fetch(`/admin/roles/${roleId}/permissions`, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            renderPermissionsContent(data);
        } else {
            console.error('Error loading permissions:', response.status);
            document.getElementById('rolePermissionsContent').innerHTML = '<div class="alert alert-danger">Failed to load permissions</div>';
        }
    } catch (error) {
        console.error('Error loading role permissions:', error);
        document.getElementById('rolePermissionsContent').innerHTML = '<div class="alert alert-danger">Error loading permissions</div>';
    }
}

// Render permissions content
function renderPermissionsContent(data) {
    const { role, groupedPermissions, rolePermissionIds } = data;
    let html = '<div class="permissions-container">';
    
    Object.keys(groupedPermissions).forEach(groupName => {
        const permissions = groupedPermissions[groupName];
        const groupId = groupName.toLowerCase().replace(/\s+/g, '-');
        const checkedCount = permissions.filter(p => rolePermissionIds.includes(p.id)).length;
        const isIndeterminate = checkedCount > 0 && checkedCount < permissions.length;
        const isAllChecked = checkedCount === permissions.length;
        
        html += `
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center" data-bs-toggle="collapse" data-bs-target="#group-${groupId}">
                    <input type="checkbox" class="form-check-input permission-group-toggle me-2" 
                           data-group-id="${groupId}" 
                           ${isAllChecked ? 'checked' : ''}
                           ${isIndeterminate ? 'indeterminate' : ''}>
                    <span class="fw-semibold">${groupName}</span>
                    <span class="badge bg-secondary ms-auto">${permissions.length}</span>
                    <i class="fas fa-chevron-down ms-2"></i>
                </div>
                <div class="collapse show" id="group-${groupId}">
                    <div class="card-body">
                        <div class="row">
        `;
        
        permissions.forEach(permission => {
            const isChecked = rolePermissionIds.includes(permission.id);
            html += `
                <div class="col-md-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input permission-checkbox" type="checkbox" 
                               id="perm-${permission.id}" value="${permission.id}" 
                               data-group="${groupId}" ${isChecked ? 'checked' : ''}>
                        <label class="form-check-label" for="perm-${permission.id}">
                            ${permission.name}
                        </label>
                    </div>
                </div>
            `;
        });
        
        html += `
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    document.getElementById('rolePermissionsContent').innerHTML = html;
    initializePermissionsHandlers();
}

// Load role users
async function loadRoleUsers(roleId) {
    try {
        const response = await fetch(`/admin/roles/${roleId}/users`, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            renderUsersContent(data);
        } else {
            console.error('Error loading users:', response.status);
            document.getElementById('roleUsersContent').innerHTML = '<div class="alert alert-danger">Failed to load users</div>';
        }
    } catch (error) {
        console.error('Error loading role users:', error);
        document.getElementById('roleUsersContent').innerHTML = '<div class="alert alert-danger">Error loading users</div>';
    }
}

// Render users content
function renderUsersContent(data) {
    const { role, users } = data;
    let html = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">Users in ${role.name} (${users.length})</h6>
            <div>
                <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="exportUsers(${role.id})">
                    <i class="fas fa-download"></i> Export
                </button>
                <button type="button" class="btn btn-sm btn-primary" onclick="showImportUsersModal(${role.id})">
                    <i class="fas fa-upload"></i> Import
                </button>
            </div>
        </div>
    `;
    
    if (users.length > 0) {
        html += `
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="form-check-input" id="selectAllUsers" onchange="toggleSelectAllUsers(this)">
                            </th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        users.forEach(user => {
            html += `
                <tr>
                    <td>
                        <input type="checkbox" class="form-check-input user-checkbox" value="${user.id}">
                    </td>
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <td>${user.created_at}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeUser(${role.id}, ${user.id})">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
                <button type="button" class="btn btn-danger" id="bulkRemoveBtn" onclick="bulkRemoveUsers(${role.id})" disabled>
                    <i class="fas fa-trash"></i> Remove Selected
                </button>
            </div>
        `;
    } else {
        html += '<div class="alert alert-info">No users assigned to this role.</div>';
    }
    
    document.getElementById('roleUsersContent').innerHTML = html;
    initializeUsersHandlers();
}

// Initialize permissions handlers
function initializePermissionsHandlers() {
    // Group toggle functionality
    document.querySelectorAll('.permission-group-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const groupId = this.dataset.groupId;
            const isChecked = this.checked;
            
            // Toggle all permissions in the group
            document.querySelectorAll(`[data-group="${groupId}"]`).forEach(permission => {
                permission.checked = isChecked;
            });
        });
    });
    
    // Individual permission change
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const groupId = this.dataset.groupId;
            const groupCheckboxes = document.querySelectorAll(`[data-group="${groupId}"]`);
            const groupToggle = document.querySelector(`[data-group-id="${groupId}"]`);
            
            // Update group toggle state
            const checkedCount = Array.from(groupCheckboxes).filter(cb => cb.checked).length;
            groupToggle.checked = checkedCount === groupCheckboxes.length;
            groupToggle.indeterminate = checkedCount > 0 && checkedCount < groupCheckboxes.length;
        });
    });
}

// Initialize users handlers
function initializeUsersHandlers() {
    // Bulk select functionality
    const bulkSelect = document.getElementById('bulkSelect');
    if (bulkSelect) {
        bulkSelect.addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                checkbox.checked = isChecked;
            });
        });
    }
    
    // Individual user checkbox change
    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            const bulkSelect = document.getElementById('bulkSelect');
            
            if (bulkSelect) {
                const checkedCount = Array.from(userCheckboxes).filter(cb => cb.checked).length;
                bulkSelect.checked = checkedCount === userCheckboxes.length;
                bulkSelect.indeterminate = checkedCount > 0 && checkedCount < userCheckboxes.length;
            }
        });
    });
}

// Toggle role status
async function toggleRoleStatus(roleId, status) {
    try {
        const response = await fetch(`/admin/roles/${roleId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: status })
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            // Update the role item in the sidebar
            const roleItem = document.querySelector(`[data-role-id="${roleId}"]`);
            if (roleItem) {
                const statusBadge = roleItem.querySelector('.badge');
                if (statusBadge) {
                    statusBadge.className = `badge bg-soft-${status === 'active' ? 'success' : 'secondary'} text-${status === 'active' ? 'success' : 'secondary'}`;
                    statusBadge.textContent = status === 'active' ? 'Active' : 'Inactive';
                }
            }
            
            // Update the toggle button label
            const toggleLabel = document.querySelector('#roleStatusToggle + label');
            if (toggleLabel) {
                toggleLabel.textContent = status === 'active' ? 'Active' : 'Inactive';
            }
        } else {
            alert(result.error || 'Failed to update role status');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred');
    }
}

// Handle edit form submission
document.getElementById('editRoleForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const roleId = document.getElementById('editRoleId').value;
    const formData = new FormData(this);
    
    try {
        const response = await fetch(`/admin/roles/${roleId}`, {
            method: 'PUT',
            body: formData
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            location.reload();
        } else {
            alert(result.error || 'Failed to update role');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred');
    }
});

// Handle form submission
document.getElementById('addRoleForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    try {
        const response = await fetch('/admin/roles', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            location.reload();
        } else {
            alert(result.error || 'Failed to create role');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred');
    }
});

async function editRole(roleId) {
    try {
        const response = await fetch(`/admin/roles/${roleId}/data`);
        const role = await response.json();
        
        if (response.ok) {
            // Populate edit modal
            document.getElementById('editRoleId').value = role.id;
            document.getElementById('editRoleName').value = role.name;
            document.getElementById('editRoleSlug').value = role.slug;
            document.getElementById('editRoleDescription').value = role.description || '';
            
            // Show edit modal
            new bootstrap.Modal(document.getElementById('editRoleModal')).show();
        } else {
            alert('Failed to load role data');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred');
    }
}

async function deleteRole(roleId) {
    if (confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
        try {
            const response = await fetch(`/admin/roles/${roleId}`, {
                method: 'DELETE'
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                location.reload();
            } else {
                alert(result.error || 'Failed to delete role');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred');
        }
    }
}
</script>
