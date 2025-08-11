# Users Management Interface

## Tổng quan

Giao diện quản lý users đã được tạo dựa trên mẫu `cms-users.html` với đầy đủ tính năng CRUD và giao diện đẹp mắt. Giao diện này sử dụng layout admin có sẵn và tích hợp với hệ thống authentication và authorization.

## Tính năng

### 1. Hiển thị danh sách users
- Hiển thị thông tin user: username, email, status, last login, created date
- Phân trang tự động
- Tìm kiếm users theo tên hoặc email
- Sắp xếp theo thời gian tạo mới nhất

### 2. Thêm user mới
- Modal form để thêm user
- Validation: username, email, password bắt buộc
- Kiểm tra trùng lặp username và email
- Chọn status: active/disabled

### 3. Chỉnh sửa user
- Modal form để chỉnh sửa thông tin user
- Có thể thay đổi username, email, status
- Password có thể để trống để giữ nguyên
- Validation và kiểm tra trùng lặp

### 4. Quản lý trạng thái user
- Toggle status: active ↔ disabled
- Reset password với mật khẩu ngẫu nhiên
- Hiển thị thông báo mật khẩu mới

### 5. Xóa user
- Xác nhận trước khi xóa
- Không thể xóa tài khoản của chính mình
- Soft delete (khôi phục được)

### 6. Xem chi tiết user
- Hiển thị đầy đủ thông tin user
- Modal hoặc alert popup

## Cấu trúc file

```
app/
├── Controllers/Admin/
│   └── UsersController.php          # Controller xử lý logic
├── Views/admin/users/
│   ├── index.php                    # View cũ (đơn giản)
│   └── manage.php                   # View mới (đẹp, đầy đủ tính năng)
└── Config/
    └── Routes.php                   # Routes cho users management
```

## Routes

```php
// Admin users routes
$routes->get('admin/users', 'Admin\UsersController::index');
$routes->post('admin/users', 'Admin\UsersController::create');
$routes->get('admin/users/(:num)', 'Admin\UsersController::show/$1');
$routes->put('admin/users/(:num)', 'Admin\UsersController::update/$1');
$routes->delete('admin/users/(:num)', 'Admin\UsersController::delete/$1');
$routes->post('admin/users/(:num)/toggle', 'Admin\UsersController::toggle/$1');
$routes->post('admin/users/(:num)/reset-password', 'Admin\UsersController::resetPassword/$1');
```

## Permissions

Giao diện sử dụng hệ thống permissions có sẵn:

- `admin.users.view` - Xem danh sách users
- `admin.users.manage` - Quản lý users (thêm, sửa, xóa, toggle status, reset password)

## Cách sử dụng

### 1. Truy cập giao diện
```
/admin/users
```

### 2. Thêm user mới
1. Click nút "Add New User"
2. Điền thông tin: username, email, password
3. Chọn status
4. Click "Add User"

### 3. Chỉnh sửa user
1. Click dropdown "Actions" của user cần sửa
2. Chọn "Edit"
3. Thay đổi thông tin cần thiết
4. Click "Update User"

### 4. Toggle status
1. Click dropdown "Actions"
2. Chọn "Toggle Status"
3. Xác nhận thay đổi

### 5. Reset password
1. Click dropdown "Actions"
2. Chọn "Reset Password"
3. Xác nhận reset
4. Ghi nhớ mật khẩu mới hiển thị

### 6. Xóa user
1. Click dropdown "Actions"
2. Chọn "Delete"
3. Xác nhận xóa

## Giao diện

### Thiết kế
- Sử dụng Bootstrap 5
- Responsive design
- Dark sidebar, light topbar
- Modern card layout
- Beautiful modals
- Toast notifications

### Components
- **Header**: Logo, search, user dropdown
- **Sidebar**: Navigation menu
- **Main content**: Users table, pagination
- **Modals**: Add/Edit user forms
- **Notifications**: Success/Error messages

### Icons
- Sử dụng Remix Icons
- Material Design Icons
- Feather Icons

## JavaScript Features

### AJAX Operations
- Tất cả operations đều sử dụng AJAX
- Không reload page khi thực hiện actions
- Real-time feedback

### Form Validation
- Client-side validation
- Server-side validation
- Error handling

### Notifications
- Toast notifications
- Auto-dismiss after 5 seconds
- Different types: success, error, info

## Database

### Users Table
```sql
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(120) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    status VARCHAR(20) DEFAULT 'active',
    last_login_at DATETIME NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    deleted_at DATETIME NULL
);
```

## Security

### Authentication
- Session-based authentication
- Required login cho tất cả admin routes

### Authorization
- Permission-based access control
- Role-based permissions
- Granular permissions cho từng action

### Data Protection
- Password hashing với bcrypt
- Input sanitization
- SQL injection prevention
- XSS protection

## Customization

### Styling
- Có thể customize CSS trong `assets/css/custom.min.css`
- Bootstrap variables có thể override
- Theme colors có thể thay đổi

### Functionality
- Có thể thêm các actions mới
- Có thể customize validation rules
- Có thể thêm các fields mới

### Localization
- Sử dụng CodeIgniter language files
- Có thể thêm multiple languages
- Dynamic text replacement

## Troubleshooting

### Common Issues

1. **Permission denied**
   - Kiểm tra user có permission `admin.users.view` và `admin.users.manage`
   - Kiểm tra role assignments

2. **Modal không hiển thị**
   - Kiểm tra Bootstrap JS đã load
   - Kiểm tra console errors

3. **Dropdown Actions không hoạt động**
   - Kiểm tra Bootstrap JS đã load đúng cách
   - Kiểm tra console có lỗi JavaScript không
   - Fallback JavaScript đã được thêm vào để đảm bảo dropdown hoạt động
   - Kiểm tra file `test-dropdown.php` để test dropdown

4. **AJAX requests fail**
   - Kiểm tra CSRF token
   - Kiểm tra network connectivity
   - Kiểm tra server logs

5. **Validation errors**
   - Kiểm tra form data
   - Kiểm tra server-side validation rules

### Debug Mode
- Enable debug mode trong CodeIgniter
- Check browser console
- Check server error logs

## Future Enhancements

### Planned Features
- Bulk operations (bulk delete, bulk status change)
- Advanced filtering và sorting
- Export users to CSV/Excel
- User activity logs
- User roles management
- Profile pictures upload
- Two-factor authentication

### Performance Optimizations
- Lazy loading cho large datasets
- Caching user data
- Optimized database queries
- CDN for assets

## Support

Nếu gặp vấn đề hoặc cần hỗ trợ:
1. Kiểm tra documentation này
2. Xem error logs
3. Kiểm tra browser console
4. Contact development team
