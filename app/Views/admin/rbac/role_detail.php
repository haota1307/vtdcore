<div class="page-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Role Details: <?= esc($role['name']) ?></h4>
                    <div class="page-title-right">
                        <a href="<?= admin_url('roles') ?>" class="btn btn-secondary">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Back to Roles
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Information -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Role Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="120">ID:</th>
                                        <td><?= esc($role['id']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Name:</th>
                                        <td><strong><?= esc($role['name']) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Slug:</th>
                                        <td><code><?= esc($role['slug']) ?></code></td>
                                    </tr>
                                    <tr>
                                        <th>Description:</th>
                                        <td><?= esc($role['description'] ?? 'No description') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Created:</th>
                                        <td><?= esc($role['created_at'] ?? 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Updated:</th>
                                        <td><?= esc($role['updated_at'] ?? 'Never') ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="card border">
                                            <div class="card-body text-center">
                                                <div class="avatar-sm mx-auto mb-3">
                                                    <div class="avatar-title bg-soft-primary text-primary rounded fs-3">
                                                        <i class="ri-lock-line"></i>
                                                    </div>
                                                </div>
                                                <h4 class="mb-1"><?= count($permissions) ?></h4>
                                                <p class="text-muted mb-0">Permissions</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card border">
                                            <div class="card-body text-center">
                                                <div class="avatar-sm mx-auto mb-3">
                                                    <div class="avatar-title bg-soft-success text-success rounded fs-3">
                                                        <i class="ri-user-line"></i>
                                                    </div>
                                                </div>
                                                <h4 class="mb-1"><?= count($users) ?></h4>
                                                <p class="text-muted mb-0">Users</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?= admin_url('roles/' . $role['id'] . '/permissions') ?>" class="btn btn-outline-primary">
                                <i class="ri-lock-line align-middle me-1"></i> Manage Permissions
                            </a>
                            <a href="<?= admin_url('roles/' . $role['id'] . '/users') ?>" class="btn btn-outline-success">
                                <i class="ri-user-line align-middle me-1"></i> Manage Users
                            </a>
                            <button type="button" class="btn btn-outline-warning" onclick="editRole(<?= $role['id'] ?>)">
                                <i class="ri-pencil-line align-middle me-1"></i> Edit Role
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="deleteRole(<?= $role['id'] ?>)">
                                <i class="ri-delete-bin-line align-middle me-1"></i> Delete Role
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Overview -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Permissions (<?= count($permissions) ?>)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($permissions)): ?>
                        <div class="row">
                            <?php 
                            $groups = [];
                            foreach ($permissions as $permission) {
                                $groups[$permission['group']][] = $permission;
                            }
                            ?>
                            
                            <?php foreach ($groups as $groupName => $groupPermissions): ?>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 text-capitalize">
                                            <i class="ri-folder-line me-2"></i>
                                            <?= esc($groupName) ?>
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <?php foreach ($groupPermissions as $permission): ?>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="ri-check-line text-success me-2"></i>
                                            <div>
                                                <strong><?= esc($permission['name']) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= esc($permission['slug']) ?></small>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <div class="avatar-md mx-auto mb-3">
                                <div class="avatar-title bg-soft-warning text-warning rounded-circle fs-24">
                                    <i class="ri-lock-line"></i>
                                </div>
                            </div>
                            <h5>No Permissions Assigned</h5>
                            <p class="text-muted">This role doesn't have any permissions assigned yet.</p>
                            <a href="<?= admin_url('roles/' . $role['id'] . '/permissions') ?>" class="btn btn-primary">
                                <i class="ri-lock-line align-middle me-1"></i> Assign Permissions
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Overview -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Users with this Role (<?= count($users) ?>)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($users)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-nowrap align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Username</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Created</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td class="fw-medium"><?= esc($user['id']) ?></td>
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
                                                    <h6 class="mb-0"><?= esc($user['username']) ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td><?= esc($user['created_at'] ?? 'N/A') ?></td>
                                        <td>
                                            <a href="<?= admin_url('users/' . $user['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="ri-eye-line"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <div class="avatar-md mx-auto mb-3">
                                <div class="avatar-title bg-soft-info text-info rounded-circle fs-24">
                                    <i class="ri-user-line"></i>
                                </div>
                            </div>
                            <h5>No Users Assigned</h5>
                            <p class="text-muted">No users have been assigned to this role yet.</p>
                            <a href="<?= admin_url('roles/' . $role['id'] . '/users') ?>" class="btn btn-primary">
                                <i class="ri-user-add-line align-middle me-1"></i> Assign Users
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
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

<script>
// Auto-generate slug from name (edit form)
document.getElementById('editRoleName').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
    document.getElementById('editRoleSlug').value = slug;
});

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
                window.location.href = '<?= admin_url('roles') ?>';
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
