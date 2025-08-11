<div class="page-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Role Permissions: <?= esc($role['name']) ?></h4>
                    <div class="page-title-right">
                        <a href="<?= admin_url('roles') ?>" class="btn btn-secondary">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Back to Roles
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Info Card -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Role Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="120">Name:</th>
                                        <td><?= esc($role['name']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Slug:</th>
                                        <td><code><?= esc($role['slug']) ?></code></td>
                                    </tr>
                                    <tr>
                                        <th>Description:</th>
                                        <td><?= esc($role['description'] ?? 'No description') ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <i class="ri-information-line"></i>
                                    <strong>Note:</strong> Select the permissions you want to assign to this role. 
                                    Users with this role will have access to the selected permissions.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Management -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Manage Permissions</h5>
                    </div>
                    <div class="card-body">
                        <form id="permissionsForm">
                            <div class="row">
                                <?php if (isset($groupedPermissions) && !empty($groupedPermissions)): ?>
                                    <?php foreach ($groupedPermissions as $groupName => $groupPermissions): ?>
                                    <div class="col-12 mb-4">
                                        <div class="card border">
                                            <div class="card-header bg-light d-flex align-items-center justify-content-between" 
                                                 data-bs-toggle="collapse" 
                                                 data-bs-target="#group_<?= md5($groupName) ?>" 
                                                 role="button" 
                                                 aria-expanded="true">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-check me-3">
                                                        <input class="form-check-input permission-group-toggle" 
                                                               type="checkbox" 
                                                               id="group_toggle_<?= md5($groupName) ?>"
                                                               data-group-id="<?= md5($groupName) ?>"
                                                               <?= count(array_intersect(array_column($groupPermissions, 'id'), $rolePermissionIds)) === count($groupPermissions) ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="group_toggle_<?= md5($groupName) ?>">
                                                            <strong class="text-capitalize"><?= esc($groupName) ?></strong>
                                                        </label>
                                                    </div>
                                                    <span class="badge bg-primary"><?= count($groupPermissions) ?> permissions</span>
                                                </div>
                                                <i class="ri-arrow-down-s-line"></i>
                                            </div>
                                            <div class="collapse show" id="group_<?= md5($groupName) ?>">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <?php foreach ($groupPermissions as $permission): ?>
                                                        <div class="col-md-6 col-lg-4 mb-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input permission-checkbox" 
                                                                       type="checkbox" 
                                                                       name="permissions[]" 
                                                                       value="<?= $permission['id'] ?>" 
                                                                       id="perm_<?= $permission['id'] ?>"
                                                                       data-group="<?= md5($groupName) ?>"
                                                                       <?= in_array($permission['id'], $rolePermissionIds) ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="perm_<?= $permission['id'] ?>">
                                                                    <div class="fw-medium"><?= esc($permission['name']) ?></div>
                                                                    <small class="text-muted"><?= esc($permission['slug']) ?></small>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-12">
                                        <div class="text-center py-4">
                                            <div class="avatar-md mx-auto mb-3">
                                                <div class="avatar-title bg-soft-warning text-warning rounded-circle fs-24">
                                                    <i class="ri-lock-line"></i>
                                                </div>
                                            </div>
                                            <h5>No Permissions Found</h5>
                                            <p class="text-muted">There are no permissions available to assign to this role.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (isset($groupedPermissions) && !empty($groupedPermissions)): ?>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAll()">
                                                <i class="ri-check-double-line"></i> Select All
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deselectAll()">
                                                <i class="ri-close-line"></i> Deselect All
                                            </button>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ri-save-line"></i> Save Permissions
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Group toggle functionality
document.querySelectorAll('.permission-group-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const groupId = this.dataset.groupId;
        const isChecked = this.checked;
        
        // Toggle all permissions in the group
        document.querySelectorAll(`[data-group="${groupId}"]`).forEach(permission => {
            permission.checked = isChecked;
        });
        
        // Update form modified flag
        formModified = true;
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
        
        // Update form modified flag
        formModified = true;
    });
});

function selectAll() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
        checkbox.checked = true;
    });
    
    // Update group toggles
    document.querySelectorAll('.permission-group-toggle').forEach(toggle => {
        toggle.checked = true;
        toggle.indeterminate = false;
    });
    
    formModified = true;
}

function deselectAll() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Update group toggles
    document.querySelectorAll('.permission-group-toggle').forEach(toggle => {
        toggle.checked = false;
        toggle.indeterminate = false;
    });
    
    formModified = true;
}

// Handle form submission
document.getElementById('permissionsForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    try {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ri-loader-4-line"></i> Saving...';
        
        const response = await fetch('/admin/roles/<?= $role['id'] ?? 'current' ?>/permissions', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            // Show success message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.innerHTML = `
                <i class="ri-check-line"></i>
                <strong>Success!</strong> Permissions have been updated successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('form'));
            
            // Auto-hide after 3 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
            
            // Reset form modified flag
            formModified = false;
        } else {
            alert(result.error || 'Failed to update permissions');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while saving permissions');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Add confirmation before leaving page if form is modified
let formModified = false;

window.addEventListener('beforeunload', function(e) {
    if (formModified) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>
