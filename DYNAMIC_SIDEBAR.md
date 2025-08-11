# Dynamic Sidebar System

Hệ thống sidebar động dựa trên phân quyền người dùng (Role-Based Access Control - RBAC).

## Tổng quan

Sidebar động tự động hiển thị các menu item dựa trên quyền hạn của người dùng đang đăng nhập. Hệ thống sử dụng:

- **Permission Helper**: Các hàm tiện ích để kiểm tra quyền hạn
- **SidebarService**: Service quản lý cấu hình menu
- **RBAC System**: Hệ thống phân quyền có sẵn

## Cách sử dụng

### 1. Kiểm tra quyền hạn trong View

```php
<?php if (user_has_permission('admin.users.view')): ?>
    <a href="<?= admin_url('users') ?>">Quản lý người dùng</a>
<?php endif; ?>

<?php if (user_has_any_permission(['admin.users.view', 'admin.users.manage'])): ?>
    <a href="<?= admin_url('users') ?>">Quản lý người dùng</a>
<?php endif; ?>

<?php if (user_has_role('admin')): ?>
    <a href="<?= admin_url('admin-panel') ?>">Admin Panel</a>
<?php endif; ?>
```

### 2. Các hàm helper có sẵn

#### Kiểm tra quyền hạn
- `user_has_permission(string $permission)`: Kiểm tra một quyền cụ thể
- `user_has_any_permission(array $permissions)`: Kiểm tra có bất kỳ quyền nào trong danh sách
- `user_has_all_permissions(array $permissions)`: Kiểm tra có tất cả quyền trong danh sách

#### Kiểm tra vai trò
- `user_has_role(string $roleSlug)`: Kiểm tra một vai trò cụ thể
- `user_has_any_role(array $roleSlugs)`: Kiểm tra có bất kỳ vai trò nào trong danh sách

#### Lấy thông tin
- `get_user_permissions()`: Lấy tất cả quyền hạn của user hiện tại
- `get_user_roles()`: Lấy tất cả vai trò của user hiện tại

### 3. Sử dụng SidebarService

#### Lấy menu items đã được lọc
```php
$menuItems = get_sidebar_menu();
```

#### Thêm menu item tùy chỉnh
```php
add_sidebar_item('custom', 'my-feature', [
    'label' => 'My Feature',
    'icon' => 'mdi mdi-star',
    'url' => admin_url('my-feature'),
    'permissions' => ['custom.feature.access']
]);
```

#### Xóa menu item
```php
remove_sidebar_item('main', 'users');
```

## Cấu trúc Menu

### Cấu hình mặc định

Hệ thống có sẵn các section menu:

1. **Main Menu** (Menu chính)
   - Dashboard (luôn hiển thị cho user đã đăng nhập)
   - Users (cần quyền: `admin.users.view` hoặc `admin.users.manage`)
   - Roles (cần quyền: `admin.roles.view` hoặc `admin.roles.manage`)
   - Media (cần quyền: `admin.media.manage`, `manager.media.manage`, `editor.media.upload`, `editor.media.view`)
   - Settings (cần quyền: `admin.settings.manage`)
   - Audit Logs (cần quyền: `admin.audit.view` hoặc `admin.audit.manage`)

2. **System** (Hệ thống)
   - Configuration (cần quyền: `system.config`)
   - Backup (cần quyền: `system.backup`)

3. **Manager** (Quản lý)
   - Manager Dashboard (cần quyền: `manager.dashboard`)
   - Content Management (cần quyền: `manager.content.manage`)
   - Reports (cần quyền: `manager.reports.view`)

4. **Editor** (Biên tập)
   - Editor Dashboard (cần quyền: `editor.dashboard`)
   - Create Content (cần quyền: `editor.content.create`)
   - Manage Content (cần quyền: `editor.content.edit` hoặc `editor.content.publish`)

5. **User** (Người dùng)
   - User Dashboard (cần quyền: `user.dashboard`)
   - Profile (cần quyền: `user.profile.view` hoặc `user.profile.edit`)

6. **Account** (Tài khoản - luôn hiển thị)
   - Profile
   - Logout

### Cấu trúc Menu Item

```php
[
    'label' => 'Tên hiển thị',
    'icon' => 'mdi mdi-icon-name',
    'url' => admin_url('route'),
    'permissions' => ['permission1', 'permission2'], // Quyền cần thiết
    'always_visible' => false // Luôn hiển thị (không cần kiểm tra quyền)
]
```

## Tùy chỉnh

### 1. Thêm menu item mới

Trong controller hoặc service:

```php
// Thêm vào section có sẵn
add_sidebar_item('main', 'analytics', [
    'label' => 'Analytics',
    'icon' => 'mdi mdi-chart-bar',
    'url' => admin_url('analytics'),
    'permissions' => ['admin.analytics.view']
]);

// Tạo section mới
add_sidebar_item('reports', 'sales', [
    'label' => 'Sales Report',
    'icon' => 'mdi mdi-cash-register',
    'url' => admin_url('reports/sales'),
    'permissions' => ['reports.sales.view']
]);
```

### 2. Sửa đổi SidebarService

Để thay đổi cấu hình mặc định, chỉnh sửa file `app/Services/SidebarService.php`:

```php
private function buildMenuItems(): void
{
    $this->menuItems = [
        'main' => [
            'title' => 'Menu',
            'items' => [
                // Thêm/sửa/xóa menu items ở đây
            ]
        ],
        // Thêm sections mới
    ];
}
```

### 3. Tạo sidebar tùy chỉnh

Tạo file view mới và sử dụng helper functions:

```php
<!-- app/Views/admin/layout/custom_sidebar.php -->
<ul class="navbar-nav">
    <?php foreach (get_sidebar_menu() as $section): ?>
        <li class="menu-title"><?= $section['title'] ?></li>
        <?php foreach ($section['items'] as $item): ?>
            <li class="nav-item">
                <a href="<?= $item['url'] ?>">
                    <i class="<?= $item['icon'] ?>"></i>
                    <?= $item['label'] ?>
                </a>
            </li>
        <?php endforeach; ?>
    <?php endforeach; ?>
</ul>
```

## Bảo mật

- Tất cả menu items đều được kiểm tra quyền hạn trước khi hiển thị
- Helper functions tự động kiểm tra user session
- Không có thông tin nhạy cảm nào được hiển thị cho user không có quyền

## Debug

Để debug quyền hạn của user hiện tại:

```php
// Trong view hoặc controller
$permissions = get_user_permissions();
$roles = get_user_roles();

// Hoặc sử dụng SidebarService trực tiếp
$sidebarService = service('sidebar');
$userPerms = $sidebarService->getUserPermissions();
$userRoles = $sidebarService->getUserRoles();
```

## Lưu ý

1. Đảm bảo helper `permission` đã được load trong controller
2. Kiểm tra quyền hạn trong database trước khi thêm menu item
3. Sử dụng permission slugs chính xác như đã định nghĩa trong seeder
4. Cache permissions để tăng hiệu suất (đã được implement trong PermissionService)
