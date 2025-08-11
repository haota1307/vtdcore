<div class="page-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Role Users: <?= esc($role['name']) ?></h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignUserModal">
                            <i class="ri-user-add-line align-middle me-1"></i> Assign User
                        </button>
                        <a href="<?= admin_url('roles') ?>" class="btn btn-secondary ms-2">
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
                                    <strong>Note:</strong> This page shows all users who have been assigned this role. 
                                    You can assign new users or remove existing ones from this role.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Users with this Role (<?= count($users) ?>)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($users)): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="checkbox" id="bulkSelect">
                                    <label class="form-check-label" for="bulkSelect">
                                        <strong>Select All</strong>
                                    </label>
                                </div>
                                <button type="button" class="btn btn-outline-danger btn-sm" id="bulkRemoveBtn" disabled>
                                    <i class="ri-delete-bin-line"></i> Remove Selected
                                </button>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="/admin/roles/<?= $role['id'] ?? 'current' ?>/export-users" class="btn btn-outline-info btn-sm">
                                    <i class="ri-download-line"></i> Export Users
                                </a>
                                <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#importUsersModal">
                                    <i class="ri-upload-line"></i> Import Users
                                </button>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-nowrap align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col" width="50">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="headerCheckbox">
                                            </div>
                                        </th>
                                        <th scope="col">ID</th>
                                        <th scope="col">Username</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Created</th>
                                        <th scope="col">Last Login</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input user-checkbox" type="checkbox" value="<?= $user['id'] ?>">
                                            </div>
                                        </td>
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
                                        <td><?= esc($user['last_login_at'] ?? 'Never') ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="removeUser(<?= $role['id'] ?? 'current' ?>, <?= $user['id'] ?>, '<?= esc($user['username']) ?>')">
                                                <i class="ri-user-unfollow-line"></i> Remove
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <div class="avatar-md mx-auto mb-3">
                                <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-24">
                                    <i class="ri-user-line"></i>
                                </div>
                            </div>
                            <h5>No Users Assigned</h5>
                            <p class="text-muted">No users have been assigned to this role yet.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignUserModal">
                                <i class="ri-user-add-line align-middle me-1"></i> Assign First User
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign User Modal -->
<div class="modal fade" id="assignUserModal" tabindex="-1" aria-labelledby="assignUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignUserModalLabel">Assign User to Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignUserForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="userSearch" class="form-label">Search Users</label>
                        <input type="text" class="form-control" id="userSearch" placeholder="Type username or email to search...">
                        <div class="form-text">Start typing to search for users</div>
                    </div>
                    <div id="searchResults" class="mb-3" style="display: none;">
                        <label class="form-label">Select User</label>
                        <div id="usersList" class="list-group">
                            <!-- Search results will be populated here -->
                        </div>
                    </div>
                    <div id="selectedUser" class="mb-3" style="display: none;">
                        <label class="form-label">Selected User</label>
                        <div class="alert alert-info">
                            <i class="ri-user-line"></i>
                            <span id="selectedUserName"></span>
                            <input type="hidden" id="selectedUserId" name="user_id">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="assignUserBtn" disabled>Assign User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Users Modal -->
<div class="modal fade" id="importUsersModal" tabindex="-1" aria-labelledby="importUsersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importUsersModalLabel">Import Users to Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="importUsersForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="importUserSearch" class="form-label">Search Users to Import</label>
                        <input type="text" class="form-control" id="importUserSearch" placeholder="Type username or email to search...">
                        <div class="form-text">Search for users to assign to this role</div>
                    </div>
                    <div id="importSearchResults" class="mb-3" style="display: none;">
                        <label class="form-label">Select Users</label>
                        <div id="importUsersList" class="list-group">
                            <!-- Search results will be populated here -->
                        </div>
                    </div>
                    <div id="selectedImportUsers" class="mb-3" style="display: none;">
                        <label class="form-label">Selected Users</label>
                        <div id="selectedUsersList" class="list-group">
                            <!-- Selected users will be shown here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="importUsersBtn" disabled>Import Users</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let searchTimeout;
let importSearchTimeout;
let selectedImportUsers = [];

// Bulk select functionality
document.getElementById('bulkSelect').addEventListener('change', function() {
    const isChecked = this.checked;
    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
        checkbox.checked = isChecked;
    });
    updateBulkRemoveButton();
});

document.getElementById('headerCheckbox').addEventListener('change', function() {
    const isChecked = this.checked;
    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
        checkbox.checked = isChecked;
    });
    document.getElementById('bulkSelect').checked = isChecked;
    updateBulkRemoveButton();
});

// Individual user checkbox change
document.querySelectorAll('.user-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        updateBulkRemoveButton();
        updateHeaderCheckbox();
    });
});

function updateBulkRemoveButton() {
    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
    const bulkRemoveBtn = document.getElementById('bulkRemoveBtn');
    bulkRemoveBtn.disabled = checkedBoxes.length === 0;
    bulkRemoveBtn.innerHTML = `<i class="ri-delete-bin-line"></i> Remove Selected (${checkedBoxes.length})`;
}

function updateHeaderCheckbox() {
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const headerCheckbox = document.getElementById('headerCheckbox');
    const bulkSelect = document.getElementById('bulkSelect');
    
    const checkedCount = Array.from(userCheckboxes).filter(cb => cb.checked).length;
    headerCheckbox.checked = checkedCount === userCheckboxes.length;
    headerCheckbox.indeterminate = checkedCount > 0 && checkedCount < userCheckboxes.length;
    
    bulkSelect.checked = headerCheckbox.checked;
    bulkSelect.indeterminate = headerCheckbox.indeterminate;
}

// Bulk remove functionality
document.getElementById('bulkRemoveBtn').addEventListener('click', async function() {
    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
    const userIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (userIds.length === 0) {
        alert('Please select users to remove');
        return;
    }
    
    if (confirm(`Are you sure you want to remove ${userIds.length} user(s) from this role?`)) {
        try {
            const response = await fetch('/admin/roles/<?= $role['id'] ?? 'current' ?>/bulk-remove-users', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ user_ids: userIds })
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                location.reload();
            } else {
                alert(result.error || 'Failed to remove users from role');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while removing users');
        }
    }
});

// Handle user search
document.getElementById('userSearch').addEventListener('input', function() {
    const searchTerm = this.value.trim();
    const searchResults = document.getElementById('searchResults');
    const usersList = document.getElementById('usersList');
    
    clearTimeout(searchTimeout);
    
    if (searchTerm.length < 2) {
        searchResults.style.display = 'none';
        return;
    }
    
    searchTimeout = setTimeout(async () => {
        try {
            const response = await fetch(`/admin/roles/search-users?search=${encodeURIComponent(searchTerm)}&role_id=<?= $role['id'] ?? 'current' ?>`);
            const users = await response.json();
            
            if (users.length > 0) {
                usersList.innerHTML = users.map(user => `
                    <button type="button" class="list-group-item list-group-item-action" 
                            onclick="selectUser(${user.id}, '${user.username}')">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-2">
                                <div class="avatar-xs">
                                    <div class="avatar-title bg-soft-primary text-primary rounded">
                                        <i class="ri-user-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">${user.username}</h6>
                                <small class="text-muted">${user.email}</small>
                            </div>
                        </div>
                    </button>
                `).join('');
                searchResults.style.display = 'block';
            } else {
                usersList.innerHTML = '<div class="list-group-item text-muted">No users found</div>';
                searchResults.style.display = 'block';
            }
        } catch (error) {
            console.error('Error:', error);
            usersList.innerHTML = '<div class="list-group-item text-danger">Error searching users</div>';
            searchResults.style.display = 'block';
        }
    }, 300);
});

// Handle import user search
document.getElementById('importUserSearch').addEventListener('input', function() {
    const searchTerm = this.value.trim();
    const searchResults = document.getElementById('importSearchResults');
    const usersList = document.getElementById('importUsersList');
    
    clearTimeout(importSearchTimeout);
    
    if (searchTerm.length < 2) {
        searchResults.style.display = 'none';
        return;
    }
    
    importSearchTimeout = setTimeout(async () => {
        try {
            const response = await fetch(`/admin/roles/search-users?search=${encodeURIComponent(searchTerm)}&role_id=<?= $role['id'] ?? 'current' ?>`);
            const users = await response.json();
            
            if (users.length > 0) {
                usersList.innerHTML = users.map(user => `
                    <button type="button" class="list-group-item list-group-item-action" 
                            onclick="selectImportUser(${user.id}, '${user.username}', '${user.email}')">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-2">
                                <div class="avatar-xs">
                                    <div class="avatar-title bg-soft-primary text-primary rounded">
                                        <i class="ri-user-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">${user.username}</h6>
                                <small class="text-muted">${user.email}</small>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="ri-add-line"></i>
                            </div>
                        </div>
                    </button>
                `).join('');
                searchResults.style.display = 'block';
            } else {
                usersList.innerHTML = '<div class="list-group-item text-muted">No users found</div>';
                searchResults.style.display = 'block';
            }
        } catch (error) {
            console.error('Error:', error);
            usersList.innerHTML = '<div class="list-group-item text-danger">Error searching users</div>';
            searchResults.style.display = 'block';
        }
    }, 300);
});

function selectUser(userId, username) {
    document.getElementById('selectedUserId').value = userId;
    document.getElementById('selectedUserName').textContent = username;
    document.getElementById('selectedUser').style.display = 'block';
    document.getElementById('searchResults').style.display = 'none';
    document.getElementById('userSearch').value = username;
    document.getElementById('assignUserBtn').disabled = false;
}

function selectImportUser(userId, username, email) {
    // Check if user is already selected
    if (selectedImportUsers.find(u => u.id === userId)) {
        return;
    }
    
    selectedImportUsers.push({ id: userId, username: username, email: email });
    updateSelectedImportUsers();
    document.getElementById('importUserSearch').value = '';
    document.getElementById('importSearchResults').style.display = 'none';
}

function removeImportUser(userId) {
    selectedImportUsers = selectedImportUsers.filter(u => u.id !== userId);
    updateSelectedImportUsers();
}

function updateSelectedImportUsers() {
    const selectedUsersList = document.getElementById('selectedUsersList');
    const importUsersBtn = document.getElementById('importUsersBtn');
    
    if (selectedImportUsers.length > 0) {
        selectedUsersList.innerHTML = selectedImportUsers.map(user => `
            <div class="list-group-item d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-2">
                        <div class="avatar-xs">
                            <div class="avatar-title bg-soft-primary text-primary rounded">
                                <i class="ri-user-line"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">${user.username}</h6>
                        <small class="text-muted">${user.email}</small>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImportUser(${user.id})">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        `).join('');
        document.getElementById('selectedImportUsers').style.display = 'block';
        importUsersBtn.disabled = false;
        importUsersBtn.innerHTML = `Import Users (${selectedImportUsers.length})`;
    } else {
        document.getElementById('selectedImportUsers').style.display = 'none';
        importUsersBtn.disabled = true;
        importUsersBtn.innerHTML = 'Import Users';
    }
}

// Handle form submission
document.getElementById('assignUserForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = document.getElementById('assignUserBtn');
    const originalText = submitBtn.innerHTML;
    
    try {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ri-loader-4-line"></i> Assigning...';
        
        const response = await fetch('/admin/roles/<?= $role['id'] ?? 'current' ?>/users', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            location.reload();
        } else {
            alert(result.error || 'Failed to assign user to role');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while assigning user');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Handle import form submission
document.getElementById('importUsersForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (selectedImportUsers.length === 0) {
        alert('Please select users to import');
        return;
    }
    
    const submitBtn = document.getElementById('importUsersBtn');
    const originalText = submitBtn.innerHTML;
    
    try {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ri-loader-4-line"></i> Importing...';
        
        const response = await fetch('/admin/roles/<?= $role['id'] ?? 'current' ?>/import-users', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_ids: selectedImportUsers.map(u => u.id) })
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            alert(result.message);
            location.reload();
        } else {
            alert(result.error || 'Failed to import users');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while importing users');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

async function removeUser(roleId, userId, username) {
    if (confirm(`Are you sure you want to remove "${username}" from this role?`)) {
        try {
            const response = await fetch(`/admin/roles/${roleId}/users/${userId}`, {
                method: 'DELETE'
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                location.reload();
            } else {
                alert(result.error || 'Failed to remove user from role');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while removing user');
        }
    }
}

// Reset modals when closed
document.getElementById('assignUserModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('userSearch').value = '';
    document.getElementById('searchResults').style.display = 'none';
    document.getElementById('selectedUser').style.display = 'none';
    document.getElementById('selectedUserId').value = '';
    document.getElementById('assignUserBtn').disabled = true;
});

document.getElementById('importUsersModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('importUserSearch').value = '';
    document.getElementById('importSearchResults').style.display = 'none';
    document.getElementById('selectedImportUsers').style.display = 'none';
    selectedImportUsers = [];
    updateSelectedImportUsers();
});
</script>
