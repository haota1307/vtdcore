<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="<?= admin_url() ?>" class="logo logo-dark">
            <span class="logo-sm">
                <img src="<?= base_url(); ?>assets/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?= base_url(); ?>assets/images/logo-dark.png" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="<?= admin_url() ?>" class="logo logo-light">
            <span class="logo-sm">
                <img src="<?= base_url(); ?>assets/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?= base_url(); ?>assets/images/logo-light.png" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                
                <!-- Dashboard - Always visible for logged in users -->
                <?php if (service('auth')->user()): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url() ?>">
                        <i class="mdi mdi-speedometer"></i> <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Users Management -->
                <?php if (user_has_any_permission(['admin.users.view', 'admin.users.manage'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url('users') ?>">
                        <i class="mdi mdi-account-group"></i> <span data-key="t-users">Users</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Roles Management -->
                <?php if (user_has_any_permission(['admin.roles.view', 'admin.roles.manage'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url('roles') ?>">
                        <i class="mdi mdi-shield-account"></i> <span data-key="t-roles">Roles</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Media Management -->
                <?php if (user_has_any_permission(['admin.media.manage', 'manager.media.manage', 'editor.media.upload', 'editor.media.view'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url('media') ?>">
                        <i class="mdi mdi-image-multiple"></i> <span data-key="t-media">Media</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Settings -->
                <?php if (user_has_permission('admin.settings.manage')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url('settings') ?>">
                        <i class="mdi mdi-cog"></i> <span data-key="t-settings">Settings</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Audit Logs -->
                <?php if (user_has_any_permission(['admin.audit.view', 'admin.audit.manage'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url('audit') ?>">
                        <i class="mdi mdi-file-document"></i> <span data-key="t-audit">Audit Logs</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- System Management -->
                <?php if (user_has_any_permission(['system.manage', 'system.config', 'system.backup'])): ?>
                <li class="menu-title"><span data-key="t-system">System</span></li>
                
                <!-- System Configuration -->
                <?php if (user_has_permission('system.config')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url('system/config') ?>">
                        <i class="mdi mdi-tune"></i> <span data-key="t-system-config">Configuration</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- System Backup -->
                <?php if (user_has_permission('system.backup')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url('system/backup') ?>">
                        <i class="mdi mdi-backup-restore"></i> <span data-key="t-system-backup">Backup</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php endif; ?>

                <!-- Manager Section -->
                <?php if (user_has_any_permission(['manager.dashboard', 'manager.users.view', 'manager.content.manage', 'manager.reports.view'])): ?>
                <li class="menu-title"><span data-key="t-manager">Manager</span></li>
                
                <!-- Manager Dashboard -->
                <?php if (user_has_permission('manager.dashboard')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url('manager/dashboard') ?>">
                        <i class="mdi mdi-view-dashboard"></i> <span data-key="t-manager-dashboard">Manager Dashboard</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Content Management -->
                <?php if (user_has_permission('manager.content.manage')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url('manager/content') ?>">
                        <i class="mdi mdi-file-document-edit"></i> <span data-key="t-content-manage">Content Management</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Reports -->
                <?php if (user_has_permission('manager.reports.view')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url('manager/reports') ?>">
                        <i class="mdi mdi-chart-line"></i> <span data-key="t-reports">Reports</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php endif; ?>

                <!-- Editor Section -->
                <?php if (user_has_any_permission(['editor.dashboard', 'editor.content.create', 'editor.content.edit', 'editor.content.publish'])): ?>
                <li class="menu-title"><span data-key="t-editor">Editor</span></li>
                
                <!-- Editor Dashboard -->
                <?php if (user_has_permission('editor.dashboard')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url('editor/dashboard') ?>">
                        <i class="mdi mdi-pencil-box"></i> <span data-key="t-editor-dashboard">Editor Dashboard</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Content Creation -->
                <?php if (user_has_permission('editor.content.create')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url('editor/content/create') ?>">
                        <i class="mdi mdi-plus-circle"></i> <span data-key="t-create-content">Create Content</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Content Management -->
                <?php if (user_has_any_permission(['editor.content.edit', 'editor.content.publish'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url('editor/content') ?>">
                        <i class="mdi mdi-file-edit"></i> <span data-key="t-manage-content">Manage Content</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php endif; ?>

                <!-- User Section -->
                <?php if (user_has_any_permission(['user.dashboard', 'user.profile.view', 'user.profile.edit'])): ?>
                <li class="menu-title"><span data-key="t-user">User</span></li>
                
                <!-- User Dashboard -->
                <?php if (user_has_permission('user.dashboard')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url('user/dashboard') ?>">
                        <i class="mdi mdi-account"></i> <span data-key="t-user-dashboard">User Dashboard</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Profile -->
                <?php if (user_has_any_permission(['user.profile.view', 'user.profile.edit'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url('profile') ?>">
                        <i class="mdi mdi-account-circle"></i> <span data-key="t-profile">Profile</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php endif; ?>

                <!-- Account Section - Always visible for logged in users -->
                <?php if (service('auth')->user()): ?>
                <li class="menu-title"><span data-key="t-account">Account</span></li>

                <!-- Profile -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url('profile') ?>">
                        <i class="mdi mdi-account-circle"></i> <span data-key="t-profile">Profile</span>
                    </a>
                </li>

                <!-- Logout -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= admin_url('auth/logout') ?>">
                        <i class="mdi mdi-logout"></i> <span data-key="t-logout">Logout</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>