<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>Users Management | Admin - VTD Core</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Users Management - VTD Core Admin Panel" name="description" />
    <meta content="VTD Core" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico') ?>">

    <!-- Layout config Js -->
    <script src="<?= base_url('assets/js/layout.js') ?>"></script>
    <!-- Bootstrap Css -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?= base_url('assets/css/icons.min.css') ?>" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= base_url('assets/css/app.min.css') ?>" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="<?= base_url('assets/css/custom.min.css') ?>" rel="stylesheet" type="text/css" />
</head>

<body>
    <!-- Begin page -->
    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box horizontal-logo">
                            <a href="<?= admin_url() ?>" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="<?= base_url('assets/images/logo-sm.png') ?>" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="<?= base_url('assets/images/logo-dark.png') ?>" alt="" height="17">
                                </span>
                            </a>

                            <a href="<?= admin_url() ?>" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="<?= base_url('assets/images/logo-sm.png') ?>" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="<?= base_url('assets/images/logo-light.png') ?>" alt="" height="17">
                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger shadow-none" id="topnav-hamburger-icon">
                            <span class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>

                        <!-- App Search-->
                        <form class="app-search d-none d-md-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Search users..." autocomplete="off" id="search-options" value="">
                                <span class="mdi mdi-magnify search-widget-icon"></span>
                                <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none" id="search-close-options"></span>
                            </div>
                        </form>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="dropdown d-inline-block user-dropdown">
                            <button type="button" class="btn header-item" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="<?= base_url('assets/images/users/avatar-1.jpg') ?>" alt="Header Avatar">
                                <span class="d-none d-xl-inline-block ms-1"><?= esc($user['username'] ?? 'Admin') ?></span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="<?= admin_url('profile') ?>"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span>My Profile</span></a>
                                <a class="dropdown-item" href="<?= admin_url('settings') ?>"><i class="mdi mdi-cog text-muted fs-16 align-middle me-1"></i> <span>Settings</span></a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= admin_url('auth/logout') ?>"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span>Logout</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">
            <div data-simplebar class="h-100">
                <div id="sidebar-menu">
                    <ul class="metismenu list-unstyled" id="side-menu">
                        <li class="menu-title">Admin Panel</li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="<?= admin_url() ?>">
                                <i class="mdi mdi-view-dashboard"></i> <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="<?= admin_url('users') ?>">
                                <i class="mdi mdi-account-group"></i> <span>Users</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="<?= admin_url('roles') ?>">
                                <i class="mdi mdi-shield-account"></i> <span>Roles</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="<?= admin_url('media') ?>">
                                <i class="mdi mdi-image-multiple"></i> <span>Media</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="<?= admin_url('settings') ?>">
                                <i class="mdi mdi-cog"></i> <span>Settings</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="<?= admin_url('audit') ?>">
                                <i class="mdi mdi-clipboard-list"></i> <span>Audit Logs</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Users Management</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?= admin_url() ?>">Admin</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Users</a></li>
                                        <li class="breadcrumb-item active">All Users</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0">Users List</h5>
                                            </div>
                                        </div>
                                        <div class="col-sm-auto">
                                            <div class="d-flex flex-wrap gap-2">
                                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                                    <i class="ri-add-line align-bottom me-1"></i> Add New User
                                                </button>
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
                                                <?php if (!empty($users)): foreach ($users as $u): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-3">
                                                                <img src="<?= base_url('assets/images/users/avatar-1.jpg') ?>" alt="" class="avatar-sm rounded-circle">
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="fs-14 mb-0"><?= esc($u['username'] ?? 'N/A') ?></h6>
                                                                <p class="text-muted mb-0">User ID: <?= esc($u['id']) ?></p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?= esc($u['email'] ?? 'N/A') ?></td>
                                                    <td>
                                                        <span class="badge bg-<?= ($u['status'] ?? 'active') === 'active' ? 'success-subtle text-success' : 'secondary-subtle text-secondary' ?>">
                                                            <?= esc(ucfirst($u['status'] ?? 'N/A')) ?>
                                                        </span>
                                                    </td>
                                                    <td><?= esc($u['last_login_at'] ?? 'Never') ?></td>
                                                    <td><?= esc($u['created_at'] ?? 'N/A') ?></td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="#" class="btn btn-soft-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                Actions <i class="mdi mdi-chevron-down"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item" href="#" onclick="viewUser(<?= (int)$u['id'] ?>)"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                                                <li><a class="dropdown-item" href="#" onclick="editUser(<?= (int)$u['id'] ?>)"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                                                <li><a class="dropdown-item" href="#" onclick="toggleStatus(<?= (int)$u['id'] ?>, this)"><i class="ri-lock-fill align-bottom me-2 text-muted"></i> Toggle Status</a></li>
                                                                <li><a class="dropdown-item" href="#" onclick="resetPassword(<?= (int)$u['id'] ?>, this)"><i class="ri-key-fill align-bottom me-2 text-muted"></i> Reset Password</a></li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteUser(<?= (int)$u['id'] ?>)"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-4">
                                                        <i class="mdi mdi-account-off fs-1 text-muted"></i>
                                                        <p class="mt-2">No users found.</p>
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <?php if (isset($meta)): ?>
                                    <div class="row align-items-center mt-4">
                                        <div class="col-sm">
                                            <div class="text-muted">
                                                Showing <span class="fw-semibold"><?= esc($meta['current_page'] ?? 1) ?></span> of <span class="fw-semibold"><?= esc($meta['total'] ?? 0) ?></span> results
                                            </div>
                                        </div>
                                        <div class="col-sm-auto">
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination pagination-separated pagination-sm mb-0 justify-content-center">
                                                    <?php if (isset($meta['previous_page']) && $meta['previous_page']): ?>
                                                    <li class="page-item">
                                                        <a class="page-link" href="?page=<?= $meta['previous_page'] ?>">Previous</a>
                                                    </li>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (isset($meta['next_page']) && $meta['next_page']): ?>
                                                    <li class="page-item">
                                                        <a class="page-link" href="?page=<?= $meta['next_page'] ?>">Next</a>
                                                    </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                    <?php endif; ?>
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
                        <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addUserForm">
                        <div class="modal-body">
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
                            <button type="submit" class="btn btn-primary">Add User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editUserForm">
                        <input type="hidden" id="editUserId" name="id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editUserUsername" class="form-label">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="editUserUsername" name="username" placeholder="Enter username" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editUserEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="editUserEmail" name="email" placeholder="Enter email address" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editUserPassword" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="editUserPassword" name="password" placeholder="Leave blank to keep current">
                                        <small class="text-muted">Leave blank to keep current password</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editUserStatus" class="form-label">Status</label>
                                        <select class="form-select" id="editUserStatus" name="status">
                                            <option value="active">Active</option>
                                            <option value="disabled">Disabled</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- JAVASCRIPT -->
        <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/simplebar/simplebar.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/node-waves/waves.min.js') ?>"></script>
        <script src="<?= base_url('assets/libs/feather-icons/feather.min.js') ?>"></script>
        <script src="<?= base_url('assets/js/pages/plugins/lord-icon-2.1.0.js') ?>"></script>
        <script src="<?= base_url('assets/js/plugins.js') ?>"></script>
        <script src="<?= base_url('assets/js/app.js') ?>"></script>

        <script>
        // Toggle user status
        async function toggleStatus(id, btn) {
            if (!confirm('Are you sure you want to toggle this user\'s status?')) return;
            
            try {
                const response = await fetch(`<?= admin_url('users') ?>/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to toggle status');
                }
                
                const result = await response.json();
                showNotification('User status updated successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } catch (error) {
                console.error('Error:', error);
                showNotification('Failed to toggle user status', 'error');
            }
        }

        // Reset user password
        async function resetPassword(id, btn) {
            if (!confirm('Are you sure you want to reset this user\'s password?')) return;
            
            try {
                const response = await fetch(`<?= admin_url('users') ?>/${id}/reset-password`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to reset password');
                }
                
                const result = await response.json();
                showNotification(`Password reset successfully. New password: ${result.new_password}`, 'success');
            } catch (error) {
                console.error('Error:', error);
                showNotification('Failed to reset password', 'error');
            }
        }

        // View user details
        function viewUser(id) {
            // Implement view user functionality
            showNotification('View user functionality coming soon', 'info');
        }

        // Edit user
        function editUser(id) {
            // Implement edit user functionality
            showNotification('Edit user functionality coming soon', 'info');
        }

        // Delete user
        function deleteUser(id) {
            if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) return;
            
            // Implement delete user functionality
            showNotification('Delete user functionality coming soon', 'info');
        }

        // Add user form submission
        document.getElementById('addUserForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            try {
                const response = await fetch('<?= admin_url('users') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                if (!response.ok) {
                    throw new Error('Failed to add user');
                }
                
                showNotification('User added successfully', 'success');
                document.getElementById('addUserModal').querySelector('.btn-close').click();
                this.reset();
                setTimeout(() => location.reload(), 1000);
            } catch (error) {
                console.error('Error:', error);
                showNotification('Failed to add user', 'error');
            }
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

        // Search functionality
        document.getElementById('search-options').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
        </script>
    </div>
</body>
</html>
