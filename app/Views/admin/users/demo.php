<?php
// Demo data for testing the interface
$demoUsers = [
    [
        'id' => 1,
        'username' => 'admin',
        'email' => 'admin@example.com',
        'status' => 'active',
        'last_login_at' => '2024-01-15 10:30:00',
        'created_at' => '2024-01-01 00:00:00'
    ],
    [
        'id' => 2,
        'username' => 'john_doe',
        'email' => 'john.doe@example.com',
        'status' => 'active',
        'last_login_at' => '2024-01-14 15:45:00',
        'created_at' => '2024-01-05 12:00:00'
    ],
    [
        'id' => 3,
        'username' => 'jane_smith',
        'email' => 'jane.smith@example.com',
        'status' => 'disabled',
        'last_login_at' => '2024-01-10 09:15:00',
        'created_at' => '2024-01-08 14:30:00'
    ],
    [
        'id' => 4,
        'username' => 'bob_wilson',
        'email' => 'bob.wilson@example.com',
        'status' => 'active',
        'last_login_at' => null,
        'created_at' => '2024-01-12 16:20:00'
    ],
    [
        'id' => 5,
        'username' => 'alice_brown',
        'email' => 'alice.brown@example.com',
        'status' => 'active',
        'last_login_at' => '2024-01-13 11:00:00',
        'created_at' => '2024-01-10 10:45:00'
    ]
];

$demoMeta = [
    'page' => 1,
    'per_page' => 25,
    'total' => 5,
    'page_count' => 1
];

// Set demo data
$users = $demoUsers;
$meta = $demoMeta;
 $user = ['username' => 'Demo Admin'];
 ?>

 <style>
 /* Ensure dropdown menus display correctly */
 .dropdown-menu.show {
     display: block !important;
 }
 
 .dropdown-menu {
     position: absolute;
     top: 100%;
     right: 0;
     z-index: 1000;
     display: none;
     min-width: 10rem;
     padding: 0.5rem 0;
     margin: 0.125rem 0 0;
     font-size: 0.875rem;
     color: #212529;
     text-align: left;
     list-style: none;
     background-color: #fff;
     background-clip: padding-box;
     border: 1px solid rgba(0, 0, 0, 0.15);
     border-radius: 0.375rem;
     box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
 }
 
 .dropdown-item {
     display: block;
     width: 100%;
     padding: 0.25rem 1rem;
     clear: both;
     font-weight: 400;
     color: #212529;
     text-align: inherit;
     text-decoration: none;
     white-space: nowrap;
     background-color: transparent;
     border: 0;
 }
 
 .dropdown-item:hover {
     color: #1e2125;
     background-color: #e9ecef;
 }
 
 .dropdown-divider {
     height: 0;
     margin: 0.5rem 0;
     overflow: hidden;
     border-top: 1px solid rgba(0, 0, 0, 0.15);
 }
 </style>

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Users Management (Demo)</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= admin_url() ?>">Admin</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Users</a></li>
                            <li class="breadcrumb-item active">Demo Users</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Demo Notice -->
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-information-outline me-2"></i>
                    <strong>Demo Mode:</strong> This is a demonstration of the Users Management interface. All actions are simulated and no real data will be modified.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm">
                                <div>
                                    <h5 class="card-title mb-0">Users List (Demo Data)</h5>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                        <i class="ri-add-line align-bottom me-1"></i> Add New User
                                    </button>
                                    <a href="<?= admin_url('users') ?>" class="btn btn-primary">
                                        <i class="ri-database-2-line align-bottom me-1"></i> View Real Data
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th scope="col">User</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Last Login</th>
                                        <th scope="col">Created</th>
                                        <th scope="col" style="width: 150px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $u): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <img src="<?= base_url('assets/images/users/avatar-' . (($u['id'] % 3) + 1) . '.jpg') ?>" alt="" class="avatar-sm rounded-circle">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fs-14 mb-0"><?= esc($u['username']) ?></h6>
                                                    <p class="text-muted mb-0">User ID: <?= esc($u['id']) ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= esc($u['email']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $u['status'] === 'active' ? 'success-subtle text-success' : 'secondary-subtle text-secondary' ?>">
                                                <?= esc(ucfirst($u['status'])) ?>
                                            </span>
                                        </td>
                                        <td><?= esc($u['last_login_at'] ?? 'Never') ?></td>
                                        <td><?= esc($u['created_at']) ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-soft-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions <i class="mdi mdi-chevron-down"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#" onclick="demoViewUser(<?= (int)$u['id'] ?>)"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="demoEditUser(<?= (int)$u['id'] ?>)"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="demoToggleStatus(<?= (int)$u['id'] ?>, this)"><i class="ri-lock-fill align-bottom me-2 text-muted"></i> Toggle Status</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="demoResetPassword(<?= (int)$u['id'] ?>, this)"><i class="ri-key-fill align-bottom me-2 text-muted"></i> Reset Password</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="demoDeleteUser(<?= (int)$u['id'] ?>)"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="row align-items-center mt-4">
                            <div class="col-sm">
                                <div class="text-muted">
                                    Showing <span class="fw-semibold"><?= esc($meta['page']) ?></span> of <span class="fw-semibold"><?= esc($meta['total']) ?></span> results
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination pagination-separated pagination-sm mb-0 justify-content-center">
                                        <li class="page-item disabled">
                                            <span class="page-link">Previous</span>
                                        </li>
                                        <li class="page-item disabled">
                                            <span class="page-link">Next</span>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User (Demo)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addUserForm">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        This is a demo form. No real data will be saved.
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="userUsername" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="userUsername" name="username" placeholder="Enter username" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="userEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="userEmail" name="email" placeholder="Enter email address" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="userPassword" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="userPassword" name="password" placeholder="Enter password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="userStatus" class="form-label">Status</label>
                                <select class="form-select" id="userStatus" name="status">
                                    <option value="active">Active</option>
                                    <option value="disabled">Disabled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add User (Demo)</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Demo functions that simulate real functionality
function demoViewUser(id) {
    const users = <?= json_encode($demoUsers) ?>;
    const user = users.find(u => u.id === id);
    
    if (user) {
        const details = `
            Username: ${user.username}
            Email: ${user.email}
            Status: ${user.status}
            Created: ${user.created_at}
            Last Login: ${user.last_login_at || 'Never'}
        `;
        
        alert('Demo - User Details:\n\n' + details);
    }
}

function demoEditUser(id) {
    const users = <?= json_encode($demoUsers) ?>;
    const user = users.find(u => u.id === id);
    
    if (user) {
        alert(`Demo - Edit User ${user.username}\n\nThis would open an edit modal in the real application.`);
    }
}

function demoToggleStatus(id, btn) {
    if (confirm('Demo - Toggle User Status\n\nThis would toggle the user status in the real application.')) {
        showNotification('Demo: User status would be toggled', 'info');
    }
}

function demoResetPassword(id, btn) {
    if (confirm('Demo - Reset User Password\n\nThis would reset the user password in the real application.')) {
        const newPassword = Math.random().toString(36).substring(2, 10);
        showNotification(`Demo: Password would be reset to: ${newPassword}`, 'info');
    }
}

function demoDeleteUser(id) {
    if (confirm('Demo - Delete User\n\nThis would delete the user in the real application.')) {
        showNotification('Demo: User would be deleted', 'info');
    }
}

// Demo form submission
document.getElementById('addUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
         showNotification('Demo: User would be added with data: ' + JSON.stringify(data), 'success');
     document.getElementById('addUserModal').querySelector('.btn-close').click();
     this.reset();
 });

 // Ensure dropdowns work with vanilla JavaScript as fallback
 document.addEventListener('DOMContentLoaded', function() {
     const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
     
     dropdownToggles.forEach(toggle => {
         toggle.addEventListener('click', function(e) {
             e.preventDefault();
             const dropdown = this.closest('.dropdown');
             const menu = dropdown.querySelector('.dropdown-menu');
             
             // Close other dropdowns
             document.querySelectorAll('.dropdown-menu.show').forEach(openMenu => {
                 if (openMenu !== menu) {
                     openMenu.classList.remove('show');
                 }
             });
             
             // Toggle current dropdown
             menu.classList.toggle('show');
         });
     });
     
     // Close dropdowns when clicking outside
     document.addEventListener('click', function(e) {
         if (!e.target.closest('.dropdown')) {
             document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                 menu.classList.remove('show');
             });
         }
     });
 });

// Show notification
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
