<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Sidebar Demo</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Thông tin User</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>ID</th>
                            <td><?= $user['id'] ?? 'N/A' ?></td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td><?= $user['username'] ?? 'N/A' ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?= $user['email'] ?? 'N/A' ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Vai trò (Roles)</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($roles)): ?>
                    <ul class="list-group">
                        <?php foreach ($roles as $role): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= $role['name'] ?>
                                <span class="badge bg-primary rounded-pill"><?= $role['slug'] ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">Không có vai trò nào được gán.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Quyền hạn (Permissions)</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($permissions)): ?>
                    <div class="row">
                        <?php foreach ($permissions as $permission): ?>
                            <div class="col-md-3 mb-2">
                                <span class="badge bg-success"><?= $permission ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Không có quyền hạn nào được gán.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Menu Items (Đã được lọc theo quyền)</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($menuItems)): ?>
                    <?php foreach ($menuItems as $sectionKey => $section): ?>
                        <h5 class="text-primary"><?= $section['title'] ?></h5>
                        <div class="row mb-3">
                            <?php foreach ($section['items'] as $itemKey => $item): ?>
                                <div class="col-md-4 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="<?= $item['icon'] ?> me-2"></i>
                                        <span><?= $item['label'] ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">Không có menu items nào được hiển thị.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Kiểm tra quyền hạn</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Kiểm tra quyền cụ thể:</h6>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between">
                                admin.users.view
                                <span class="badge <?= user_has_permission('admin.users.view') ? 'bg-success' : 'bg-danger' ?>">
                                    <?= user_has_permission('admin.users.view') ? 'Có' : 'Không' ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                admin.settings.manage
                                <span class="badge <?= user_has_permission('admin.settings.manage') ? 'bg-success' : 'bg-danger' ?>">
                                    <?= user_has_permission('admin.settings.manage') ? 'Có' : 'Không' ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                system.config
                                <span class="badge <?= user_has_permission('system.config') ? 'bg-success' : 'bg-danger' ?>">
                                    <?= user_has_permission('system.config') ? 'Có' : 'Không' ?>
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Kiểm tra vai trò:</h6>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between">
                                admin
                                <span class="badge <?= user_has_role('admin') ? 'bg-success' : 'bg-danger' ?>">
                                    <?= user_has_role('admin') ? 'Có' : 'Không' ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                manager
                                <span class="badge <?= user_has_role('manager') ? 'bg-success' : 'bg-danger' ?>">
                                    <?= user_has_role('manager') ? 'Có' : 'Không' ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                editor
                                <span class="badge <?= user_has_role('editor') ? 'bg-success' : 'bg-danger' ?>">
                                    <?= user_has_role('editor') ? 'Có' : 'Không' ?>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Hướng dẫn sử dụng</h4>
            </div>
            <div class="card-body">
                <h6>1. Kiểm tra quyền hạn trong View:</h6>
                <pre><code>&lt;?php if (user_has_permission('admin.users.view')): ?&gt;
    &lt;a href="&lt;?= admin_url('users') ?&gt;"&gt;Quản lý người dùng&lt;/a&gt;
&lt;?php endif; ?&gt;</code></pre>

                <h6>2. Thêm menu item tùy chỉnh:</h6>
                <pre><code>add_sidebar_item('custom', 'my-feature', [
    'label' => 'My Feature',
    'icon' => 'mdi mdi-star',
    'url' => admin_url('my-feature'),
    'permissions' => ['custom.feature.access']
]);</code></pre>

                <h6>3. Lấy menu items đã được lọc:</h6>
                <pre><code>$menuItems = get_sidebar_menu();</code></pre>

                <div class="mt-3">
                    <a href="<?= admin_url('sidebar-demo/debug') ?>" class="btn btn-primary">
                        <i class="mdi mdi-bug"></i> Debug Mode (Admin only)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
