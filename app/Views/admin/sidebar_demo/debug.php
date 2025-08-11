<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Sidebar Debug</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">User Permissions (Raw Data)</h4>
            </div>
            <div class="card-body">
                <pre class="bg-light p-3 rounded"><code><?= json_encode($userPermissions, JSON_PRETTY_PRINT) ?></code></pre>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">User Roles (Raw Data)</h4>
            </div>
            <div class="card-body">
                <pre class="bg-light p-3 rounded"><code><?= json_encode($userRoles, JSON_PRETTY_PRINT) ?></code></pre>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Menu Items Configuration (Raw Data)</h4>
            </div>
            <div class="card-body">
                <pre class="bg-light p-3 rounded" style="max-height: 500px; overflow-y: auto;"><code><?= json_encode($menuItems, JSON_PRETTY_PRINT) ?></code></pre>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Debug Information</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="mdi mdi-alert-circle"></i>
                    <strong>Lưu ý:</strong> Trang này chỉ dành cho admin và developer để debug hệ thống sidebar.
                </div>
                
                <h6>Thông tin hữu ích:</h6>
                <ul>
                    <li><strong>User Permissions:</strong> Danh sách tất cả quyền hạn của user hiện tại</li>
                    <li><strong>User Roles:</strong> Danh sách tất cả vai trò của user hiện tại</li>
                    <li><strong>Menu Items:</strong> Cấu hình menu đã được lọc theo quyền hạn</li>
                </ul>
                
                <div class="mt-3">
                    <a href="<?= admin_url('sidebar-demo') ?>" class="btn btn-primary">
                        <i class="mdi mdi-arrow-left"></i> Quay lại Demo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
