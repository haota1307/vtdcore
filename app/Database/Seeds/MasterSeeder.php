<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class MasterSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        echo "🎯 Bắt đầu seeding Master Database...\n";
        
        // Tắt kiểm tra foreign key
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        
        // Xóa dữ liệu cũ theo thứ tự phụ thuộc
        $tables = ['role_permissions', 'user_roles', 'permissions', 'roles', 'users', 'media', 'settings', 'audit_logs'];
        foreach ($tables as $table) {
            if ($db->tableExists($table)) {
                $db->table($table)->truncate();
                echo "✓ Đã xóa dữ liệu bảng: $table\n";
            }
        }
        
        $db->query('SET FOREIGN_KEY_CHECKS=1');
        
        // ===== TẠO USERS =====
        echo "\n👥 TẠO USERS\n";
        echo str_repeat("-", 40) . "\n";
        
        // Super Admin
        $db->table('users')->insert([
            'username' => 'superadmin',
            'email' => 'superadmin@vtdevcore.com',
            'password_hash' => password_hash('superadmin123', PASSWORD_BCRYPT),
            'status' => 'active',
            'created_at' => Time::now(),
            'updated_at' => Time::now()
        ]);
        $superAdminId = (int)$db->insertID();
        echo "✅ Super Admin đã tạo (ID: $superAdminId)\n";
        echo "   📧 Email: superadmin@vtdevcore.com\n";
        echo "   🔑 Password: superadmin123\n";
        
        // Admin
        $db->table('users')->insert([
            'username' => 'admin',
            'email' => 'admin@vtdevcore.com',
            'password_hash' => password_hash('admin123', PASSWORD_BCRYPT),
            'status' => 'active',
            'created_at' => Time::now(),
            'updated_at' => Time::now()
        ]);
        $adminId = (int)$db->insertID();
        echo "✅ Admin đã tạo (ID: $adminId)\n";
        echo "   📧 Email: admin@vtdevcore.com\n";
        echo "   🔑 Password: admin123\n";
        
        // Manager
        $db->table('users')->insert([
            'username' => 'manager',
            'email' => 'manager@vtdevcore.com',
            'password_hash' => password_hash('manager123', PASSWORD_BCRYPT),
            'status' => 'active',
            'created_at' => Time::now(),
            'updated_at' => Time::now()
        ]);
        $managerId = (int)$db->insertID();
        echo "✅ Manager đã tạo (ID: $managerId)\n";
        echo "   📧 Email: manager@vtdevcore.com\n";
        echo "   🔑 Password: manager123\n";
        
        // Editor
        $db->table('users')->insert([
            'username' => 'editor',
            'email' => 'editor@vtdevcore.com',
            'password_hash' => password_hash('editor123', PASSWORD_BCRYPT),
            'status' => 'active',
            'created_at' => Time::now(),
            'updated_at' => Time::now()
        ]);
        $editorId = (int)$db->insertID();
        echo "✅ Editor đã tạo (ID: $editorId)\n";
        echo "   📧 Email: editor@vtdevcore.com\n";
        echo "   🔑 Password: editor123\n";
        
        // User thường
        $db->table('users')->insert([
            'username' => 'user1',
            'email' => 'user1@vtdevcore.com',
            'password_hash' => password_hash('user123', PASSWORD_BCRYPT),
            'status' => 'active',
            'created_at' => Time::now(),
            'updated_at' => Time::now()
        ]);
        $userId = (int)$db->insertID();
        echo "✅ User thường đã tạo (ID: $userId)\n";
        echo "   📧 Email: user1@vtdevcore.com\n";
        echo "   🔑 Password: user123\n";
        
        // ===== TẠO ROLES =====
        echo "\n👑 TẠO ROLES\n";
        echo str_repeat("-", 40) . "\n";
        
        $db->table('roles')->insert([
            'slug' => 'superadmin',
            'name' => 'Super Administrator',
            'description' => 'Quản trị viên cấp cao với toàn quyền hệ thống',
            'created_at' => Time::now(),
            'updated_at' => Time::now()
        ]);
        $roleSuperAdmin = (int)$db->insertID();
        echo "✅ Role Super Admin đã tạo (ID: $roleSuperAdmin)\n";
        
        $db->table('roles')->insert([
            'slug' => 'admin',
            'name' => 'Administrator',
            'description' => 'Quản trị viên hệ thống',
            'created_at' => Time::now(),
            'updated_at' => Time::now()
        ]);
        $roleAdmin = (int)$db->insertID();
        echo "✅ Role Admin đã tạo (ID: $roleAdmin)\n";
        
        $db->table('roles')->insert([
            'slug' => 'manager',
            'name' => 'Manager',
            'description' => 'Quản lý với quyền hạn chế',
            'created_at' => Time::now(),
            'updated_at' => Time::now()
        ]);
        $roleManager = (int)$db->insertID();
        echo "✅ Role Manager đã tạo (ID: $roleManager)\n";
        
        $db->table('roles')->insert([
            'slug' => 'editor',
            'name' => 'Editor',
            'description' => 'Biên tập viên nội dung',
            'created_at' => Time::now(),
            'updated_at' => Time::now()
        ]);
        $roleEditor = (int)$db->insertID();
        echo "✅ Role Editor đã tạo (ID: $roleEditor)\n";
        
        $db->table('roles')->insert([
            'slug' => 'user',
            'name' => 'User',
            'description' => 'Người dùng thường',
            'created_at' => Time::now(),
            'updated_at' => Time::now()
        ]);
        $roleUser = (int)$db->insertID();
        echo "✅ Role User đã tạo (ID: $roleUser)\n";
        
        // ===== TẠO PERMISSIONS =====
        echo "\n🔐 TẠO PERMISSIONS\n";
        echo str_repeat("-", 40) . "\n";
        
        $permissions = [
            // Super Admin Permissions
            ['slug' => 'system.manage', 'name' => 'Manage System', 'group' => 'system'],
            ['slug' => 'system.config', 'name' => 'System Configuration', 'group' => 'system'],
            ['slug' => 'system.backup', 'name' => 'System Backup', 'group' => 'system'],
            
            // Admin Permissions
            ['slug' => 'admin.dashboard', 'name' => 'Admin Dashboard', 'group' => 'admin'],
            ['slug' => 'admin.users.view', 'name' => 'View Users', 'group' => 'admin'],
            ['slug' => 'admin.users.manage', 'name' => 'Manage Users', 'group' => 'admin'],
            ['slug' => 'admin.roles.view', 'name' => 'View Roles', 'group' => 'admin'],
            ['slug' => 'admin.roles.manage', 'name' => 'Manage Roles', 'group' => 'admin'],
            ['slug' => 'admin.permissions.view', 'name' => 'View Permissions', 'group' => 'admin'],
            ['slug' => 'admin.permissions.manage', 'name' => 'Manage Permissions', 'group' => 'admin'],
            ['slug' => 'admin.media.manage', 'name' => 'Manage Media', 'group' => 'admin'],
            ['slug' => 'admin.settings.manage', 'name' => 'Manage Settings', 'group' => 'admin'],
            ['slug' => 'admin.audit.view', 'name' => 'View Audit Logs', 'group' => 'admin'],
            ['slug' => 'admin.audit.manage', 'name' => 'Manage Audit Logs', 'group' => 'admin'],
            
            // Manager Permissions
            ['slug' => 'manager.dashboard', 'name' => 'Manager Dashboard', 'group' => 'manager'],
            ['slug' => 'manager.users.view', 'name' => 'View Users (Manager)', 'group' => 'manager'],
            ['slug' => 'manager.users.edit', 'name' => 'Edit Users (Manager)', 'group' => 'manager'],
            ['slug' => 'manager.media.manage', 'name' => 'Manage Media (Manager)', 'group' => 'manager'],
            ['slug' => 'manager.content.manage', 'name' => 'Manage Content', 'group' => 'manager'],
            ['slug' => 'manager.reports.view', 'name' => 'View Reports', 'group' => 'manager'],
            
            // Editor Permissions
            ['slug' => 'editor.dashboard', 'name' => 'Editor Dashboard', 'group' => 'editor'],
            ['slug' => 'editor.content.create', 'name' => 'Create Content', 'group' => 'editor'],
            ['slug' => 'editor.content.edit', 'name' => 'Edit Content', 'group' => 'editor'],
            ['slug' => 'editor.content.publish', 'name' => 'Publish Content', 'group' => 'editor'],
            ['slug' => 'editor.media.upload', 'name' => 'Upload Media', 'group' => 'editor'],
            ['slug' => 'editor.media.view', 'name' => 'View Media', 'group' => 'editor'],
            
            // User Permissions
            ['slug' => 'user.dashboard', 'name' => 'User Dashboard', 'group' => 'user'],
            ['slug' => 'user.profile.view', 'name' => 'View Profile', 'group' => 'user'],
            ['slug' => 'user.profile.edit', 'name' => 'Edit Profile', 'group' => 'user'],
            ['slug' => 'user.content.view', 'name' => 'View Content', 'group' => 'user'],
            ['slug' => 'user.media.view', 'name' => 'View Media', 'group' => 'user'],
        ];
        
        $permissionIds = [];
        foreach ($permissions as $permission) {
            $db->table('permissions')->insert([
                'slug' => $permission['slug'],
                'name' => $permission['name'],
                'group' => $permission['group'],
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ]);
            $permissionIds[$permission['slug']] = (int)$db->insertID();
            echo "✅ Permission '{$permission['name']}' đã tạo\n";
        }
        
        // ===== GÁN PERMISSIONS CHO ROLES =====
        echo "\n🔗 GÁN PERMISSIONS CHO ROLES\n";
        echo str_repeat("-", 40) . "\n";
        
        // Super Admin permissions (tất cả permissions)
        $superAdminPermissions = array_keys($permissionIds);
        foreach ($superAdminPermissions as $permSlug) {
            $db->table('role_permissions')->insert([
                'role_id' => $roleSuperAdmin,
                'permission_id' => $permissionIds[$permSlug]
            ]);
        }
        echo "✅ Đã gán " . count($superAdminPermissions) . " permissions cho Super Admin\n";
        
        // Admin permissions
        $adminPermissions = [
            'admin.dashboard', 'admin.users.view', 'admin.users.manage',
            'admin.roles.view', 'admin.roles.manage', 'admin.permissions.view',
            'admin.media.manage', 'admin.settings.manage', 'admin.audit.view',
            'manager.dashboard', 'manager.users.view', 'manager.users.edit',
            'manager.media.manage', 'manager.content.manage', 'manager.reports.view',
            'editor.dashboard', 'editor.content.create', 'editor.content.edit',
            'editor.content.publish', 'editor.media.upload', 'editor.media.view',
            'user.dashboard', 'user.profile.view', 'user.profile.edit',
            'user.content.view', 'user.media.view'
        ];
        
        foreach ($adminPermissions as $permSlug) {
            if (isset($permissionIds[$permSlug])) {
                $db->table('role_permissions')->insert([
                    'role_id' => $roleAdmin,
                    'permission_id' => $permissionIds[$permSlug]
                ]);
            }
        }
        echo "✅ Đã gán " . count($adminPermissions) . " permissions cho Admin\n";
        
        // Manager permissions
        $managerPermissions = [
            'manager.dashboard', 'manager.users.view', 'manager.users.edit',
            'manager.media.manage', 'manager.content.manage', 'manager.reports.view',
            'editor.dashboard', 'editor.content.create', 'editor.content.edit',
            'editor.content.publish', 'editor.media.upload', 'editor.media.view',
            'user.dashboard', 'user.profile.view', 'user.profile.edit',
            'user.content.view', 'user.media.view'
        ];
        
        foreach ($managerPermissions as $permSlug) {
            if (isset($permissionIds[$permSlug])) {
                $db->table('role_permissions')->insert([
                    'role_id' => $roleManager,
                    'permission_id' => $permissionIds[$permSlug]
                ]);
            }
        }
        echo "✅ Đã gán " . count($managerPermissions) . " permissions cho Manager\n";
        
        // Editor permissions
        $editorPermissions = [
            'editor.dashboard', 'editor.content.create', 'editor.content.edit',
            'editor.content.publish', 'editor.media.upload', 'editor.media.view',
            'user.dashboard', 'user.profile.view', 'user.profile.edit',
            'user.content.view', 'user.media.view'
        ];
        
        foreach ($editorPermissions as $permSlug) {
            if (isset($permissionIds[$permSlug])) {
                $db->table('role_permissions')->insert([
                    'role_id' => $roleEditor,
                    'permission_id' => $permissionIds[$permSlug]
                ]);
            }
        }
        echo "✅ Đã gán " . count($editorPermissions) . " permissions cho Editor\n";
        
        // User permissions
        $userPermissions = [
            'user.dashboard', 'user.profile.view', 'user.profile.edit',
            'user.content.view', 'user.media.view'
        ];
        
        foreach ($userPermissions as $permSlug) {
            if (isset($permissionIds[$permSlug])) {
                $db->table('role_permissions')->insert([
                    'role_id' => $roleUser,
                    'permission_id' => $permissionIds[$permSlug]
                ]);
            }
        }
        echo "✅ Đã gán " . count($userPermissions) . " permissions cho User\n";
        
        // ===== GÁN ROLES CHO USERS =====
        echo "\n👤 GÁN ROLES CHO USERS\n";
        echo str_repeat("-", 40) . "\n";
        
        $db->table('user_roles')->insert(['user_id' => $superAdminId, 'role_id' => $roleSuperAdmin]);
        echo "✅ Super Admin user được gán role Super Administrator\n";
        
        $db->table('user_roles')->insert(['user_id' => $adminId, 'role_id' => $roleAdmin]);
        echo "✅ Admin user được gán role Administrator\n";
        
        $db->table('user_roles')->insert(['user_id' => $managerId, 'role_id' => $roleManager]);
        echo "✅ Manager user được gán role Manager\n";
        
        $db->table('user_roles')->insert(['user_id' => $editorId, 'role_id' => $roleEditor]);
        echo "✅ Editor user được gán role Editor\n";
        
        $db->table('user_roles')->insert(['user_id' => $userId, 'role_id' => $roleUser]);
        echo "✅ User thường được gán role User\n";
        
        // ===== TẠO MEDIA MẪU =====
        echo "\n📁 TẠO MEDIA MẪU\n";
        echo str_repeat("-", 40) . "\n";
        
        if ($db->tableExists('media')) {
            $mediaFiles = [
                [
                    'disk' => 'local',
                    'path' => '2025/08/sample-image-1.jpg',
                    'original_name' => 'sample-image-1.jpg',
                    'mime' => 'image/jpeg',
                    'size' => 12345,
                    'hash' => 'sample_hash_1',
                    'owner_id' => $superAdminId,
                    'scan_status' => 'clean',
                    'created_at' => Time::now()
                ],
                [
                    'disk' => 'local',
                    'path' => '2025/08/sample-document.pdf',
                    'original_name' => 'sample-document.pdf',
                    'mime' => 'application/pdf',
                    'size' => 54321,
                    'hash' => 'sample_hash_2',
                    'owner_id' => $adminId,
                    'scan_status' => 'clean',
                    'created_at' => Time::now()
                ],
                [
                    'disk' => 'local',
                    'path' => '2025/08/sample-video.mp4',
                    'original_name' => 'sample-video.mp4',
                    'mime' => 'video/mp4',
                    'size' => 1024000,
                    'hash' => 'sample_hash_3',
                    'owner_id' => $managerId,
                    'scan_status' => 'clean',
                    'created_at' => Time::now()
                ],
                [
                    'disk' => 'local',
                    'path' => '2025/08/sample-audio.mp3',
                    'original_name' => 'sample-audio.mp3',
                    'mime' => 'audio/mpeg',
                    'size' => 2048000,
                    'hash' => 'sample_hash_4',
                    'owner_id' => $editorId,
                    'scan_status' => 'clean',
                    'created_at' => Time::now()
                ]
            ];
            
            foreach ($mediaFiles as $index => $media) {
                $db->table('media')->insert($media);
                echo "✅ Media mẫu " . ($index + 1) . " đã tạo ({$media['original_name']})\n";
            }
        }
        
        // ===== TẠO SETTINGS MẪU =====
        echo "\n⚙️ TẠO SETTINGS MẪU\n";
        echo str_repeat("-", 40) . "\n";
        
        if ($db->tableExists('settings')) {
            $settings = [
                ['key' => 'site_name', 'value' => 'VTDevCore', 'type' => 'string'],
                ['key' => 'site_description', 'value' => 'Hệ thống quản lý nội dung chuyên nghiệp', 'type' => 'string'],
                ['key' => 'site_keywords', 'value' => 'CMS, Management, Content', 'type' => 'string'],
                ['key' => 'site_author', 'value' => 'VTDevCore Team', 'type' => 'string'],
                ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean'],
                ['key' => 'maintenance_message', 'value' => 'Hệ thống đang bảo trì, vui lòng quay lại sau!', 'type' => 'string'],
                ['key' => 'max_upload_size', 'value' => '10485760', 'type' => 'integer'],
                ['key' => 'allowed_file_types', 'value' => 'jpg,jpeg,png,gif,pdf,doc,docx,mp4,mp3', 'type' => 'string'],
                ['key' => 'max_login_attempts', 'value' => '5', 'type' => 'integer'],
                ['key' => 'session_timeout', 'value' => '3600', 'type' => 'integer'],
                ['key' => 'enable_registration', 'value' => '1', 'type' => 'boolean'],
                ['key' => 'enable_email_verification', 'value' => '1', 'type' => 'boolean'],
                ['key' => 'smtp_host', 'value' => 'smtp.gmail.com', 'type' => 'string'],
                ['key' => 'smtp_port', 'value' => '587', 'type' => 'integer'],
                ['key' => 'smtp_encryption', 'value' => 'tls', 'type' => 'string'],
            ];
            
            foreach ($settings as $setting) {
                $db->table('settings')->insert([
                    'key' => $setting['key'],
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'created_at' => Time::now(),
                    'updated_at' => Time::now()
                ]);
            }
            echo "✅ Đã tạo " . count($settings) . " settings mẫu\n";
        }
        
        // ===== TẠO AUDIT LOGS MẪU =====
        echo "\n📝 TẠO AUDIT LOGS MẪU\n";
        echo str_repeat("-", 40) . "\n";
        
        if ($db->tableExists('audit_logs')) {
            $auditLogs = [
                [
                    'user_id' => $superAdminId,
                    'action' => 'user.login',
                    'ip' => '127.0.0.1',
                    'context' => json_encode([
                        'description' => 'Super Admin đăng nhập hệ thống',
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                        'timestamp' => Time::now()->toDateTimeString()
                    ]),
                    'created_at' => Time::now()
                ],
                [
                    'user_id' => $adminId,
                    'action' => 'user.create',
                    'ip' => '127.0.0.1',
                    'context' => json_encode([
                        'description' => 'Admin tạo user mới: manager@vtdevcore.com',
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                        'timestamp' => Time::now()->toDateTimeString()
                    ]),
                    'created_at' => Time::now()
                ],
                [
                    'user_id' => $managerId,
                    'action' => 'media.upload',
                    'ip' => '127.0.0.1',
                    'context' => json_encode([
                        'description' => 'Manager upload file: sample-video.mp4',
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                        'timestamp' => Time::now()->toDateTimeString()
                    ]),
                    'created_at' => Time::now()
                ],
                [
                    'user_id' => $editorId,
                    'action' => 'content.create',
                    'ip' => '127.0.0.1',
                    'context' => json_encode([
                        'description' => 'Editor tạo bài viết mới: "Hướng dẫn sử dụng hệ thống"',
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                        'timestamp' => Time::now()->toDateTimeString()
                    ]),
                    'created_at' => Time::now()
                ]
            ];
            
            foreach ($auditLogs as $log) {
                $db->table('audit_logs')->insert($log);
            }
            echo "✅ Đã tạo " . count($auditLogs) . " audit logs mẫu\n";
        }
        
        // ===== KẾT QUẢ =====
        echo "\n" . str_repeat("🎉", 30) . "\n";
        echo "🎉 MASTER SEEDING HOÀN THÀNH THÀNH CÔNG! 🎉\n";
        echo str_repeat("🎉", 30) . "\n";
        
        echo "\n📋 THÔNG TIN ĐĂNG NHẬP:\n";
        echo "┌─────────────────┬─────────────────────┬─────────────┬─────────────────┐\n";
        echo "│ Username        │ Email               │ Password    │ Role            │\n";
        echo "├─────────────────┼─────────────────────┼─────────────┼─────────────────┤\n";
        echo "│ superadmin      │ superadmin@vtdevcore.com │ superadmin123 │ Super Admin     │\n";
        echo "│ admin           │ admin@vtdevcore.com │ admin123    │ Admin           │\n";
        echo "│ manager         │ manager@vtdevcore.com│ manager123  │ Manager         │\n";
        echo "│ editor          │ editor@vtdevcore.com│ editor123   │ Editor          │\n";
        echo "│ user1           │ user1@vtdevcore.com │ user123     │ User            │\n";
        echo "└─────────────────┴─────────────────────┴─────────────┴─────────────────┘\n";
        
        echo "\n🔑 PHÂN QUYỀN CHI TIẾT:\n";
        echo "• 👑 Super Admin: Toàn quyền hệ thống (" . count($superAdminPermissions) . " permissions)\n";
        echo "• 👨‍💼 Admin: Quản lý users, roles, permissions, media, settings (" . count($adminPermissions) . " permissions)\n";
        echo "• 👨‍💻 Manager: Quản lý content, media, reports (" . count($managerPermissions) . " permissions)\n";
        echo "• ✍️ Editor: Tạo và chỉnh sửa content, upload media (" . count($editorPermissions) . " permissions)\n";
        echo "• 👤 User: Xem content, media, quản lý profile (" . count($userPermissions) . " permissions)\n";
        
        echo "\n📁 DỮ LIỆU ĐÃ TẠO:\n";
        echo "• 👥 5 Users với 5 Roles khác nhau\n";
        echo "• 👑 5 Roles: Super Admin, Admin, Manager, Editor, User\n";
        echo "• 🔐 " . count($permissions) . " Permissions được phân nhóm chi tiết\n";
        echo "• 🔗 Role-Permission mappings đầy đủ\n";
        echo "• 📁 4 Media files mẫu (image, document, video, audio)\n";
        echo "• ⚙️ " . count($settings) . " Settings cơ bản\n";
        echo "• 📝 4 Audit logs mẫu\n";
        
        echo "\n✅ Database đã sẵn sàng sử dụng!\n";
        echo "🚀 Master RBAC system đã được thiết lập hoàn chỉnh!\n";
        echo "🎯 Hệ thống phân quyền chi tiết và đầy đủ!\n";
    }
}
