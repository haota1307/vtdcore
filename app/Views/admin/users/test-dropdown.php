<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropdown Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
</head>
<body>
    <div class="container mt-5">
        <h2>Dropdown Test</h2>
        
        <div class="row">
            <div class="col-md-6">
                <h4>Bootstrap Dropdown (should work)</h4>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Bootstrap Dropdown
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="alert('View clicked')">View</a></li>
                        <li><a class="dropdown-item" href="#" onclick="alert('Edit clicked')">Edit</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="alert('Delete clicked')">Delete</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <h4>Custom Dropdown (fallback)</h4>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="customDropdown">
                        Custom Dropdown
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="alert('View clicked')">View</a></li>
                        <li><a class="dropdown-item" href="#" onclick="alert('Edit clicked')">Edit</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="alert('Delete clicked')">Delete</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <h4>Users Table with Actions</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>John Doe</td>
                            <td>john@example.com</td>
                            <td>
                                <div class="dropdown">
                                    <a href="#" class="btn btn-soft-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions <i class="mdi mdi-chevron-down"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" onclick="alert('View user 1')"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="alert('Edit user 1')"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="alert('Toggle status 1')"><i class="ri-lock-fill align-bottom me-2 text-muted"></i> Toggle Status</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="alert('Delete user 1')"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Jane Smith</td>
                            <td>jane@example.com</td>
                            <td>
                                <div class="dropdown">
                                    <a href="#" class="btn btn-soft-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions <i class="mdi mdi-chevron-down"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" onclick="alert('View user 2')"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="alert('Edit user 2')"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="alert('Toggle status 2')"><i class="ri-lock-fill align-bottom me-2 text-muted"></i> Toggle Status</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="alert('Delete user 2')"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Custom dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const customDropdown = document.getElementById('customDropdown');
            const customMenu = customDropdown.nextElementSibling;
            
            customDropdown.addEventListener('click', function(e) {
                e.preventDefault();
                customMenu.classList.toggle('show');
            });
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown')) {
                    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                        menu.classList.remove('show');
                    });
                }
            });
            
            // Debug info
            console.log('Bootstrap version:', typeof bootstrap !== 'undefined' ? 'Loaded' : 'Not loaded');
            console.log('Custom dropdown functionality initialized');
        });
    </script>
</body>
</html>
