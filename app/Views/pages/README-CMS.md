# CMS - Content Management System

## 📋 Tổng quan

Đây là một ứng dụng **Content Management System (CMS)** được xây dựng dựa trên template Velzon Admin Dashboard. CMS này cung cấp đầy đủ các tính năng quản lý nội dung cho website.

## 🚀 Tính năng chính

### 📊 Dashboard
- **Thống kê tổng quan**: Số lượng posts, users, comments
- **Biểu đồ phân tích**: Content overview, user activity
- **Widget thông tin**: Recent posts, top categories, recent comments
- **Quick actions**: Thêm post mới, filter theo thời gian

### 📝 Content Management
- **Posts Management**: Quản lý bài viết với đầy đủ CRUD
- **Categories Management**: Quản lý danh mục với icons và status
- **Pages Management**: Quản lý trang tĩnh
- **Media Library**: Quản lý file media (images, documents)

### 👥 User Management
- **User List**: Danh sách người dùng với thông tin chi tiết
- **Roles & Permissions**: Phân quyền người dùng
- **User Profiles**: Thông tin cá nhân và cài đặt

### ⚙️ Settings & Configuration
- **General Settings**: Cài đặt chung của website
- **Appearance**: Tùy chỉnh giao diện
- **SEO Settings**: Tối ưu hóa SEO

### 💬 Comments Management
- **Comment Moderation**: Duyệt và quản lý bình luận
- **Spam Protection**: Bảo vệ khỏi spam

### 📈 Analytics
- **Content Analytics**: Phân tích hiệu suất nội dung
- **User Analytics**: Thống kê người dùng
- **Traffic Reports**: Báo cáo lưu lượng truy cập

## 🛠️ Công nghệ sử dụng

- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript
- **Icons**: Material Design Icons, Remix Icons
- **Charts**: ApexCharts, Chart.js
- **UI Components**: Velzon Admin Template
- **Responsive**: Mobile-first design

## 📁 Cấu trúc file

```
├── cms-dashboard.html          # Dashboard chính
├── cms-posts.html             # Quản lý Posts
├── cms-users.html             # Quản lý Users
├── cms-categories.html        # Quản lý Categories
├── cms-pages.html             # Quản lý Pages
├── cms-media.html             # Media Library
├── cms-comments.html          # Quản lý Comments
├── cms-analytics.html         # Analytics
├── cms-settings.html          # Settings
└── README-CMS.md             # Hướng dẫn này
```

## 🎯 Các trang chính

### 1. Dashboard (`cms-dashboard.html`)
- **URL**: `cms-dashboard.html`
- **Mô tả**: Trang tổng quan với thống kê và biểu đồ
- **Tính năng**:
  - Widget thống kê (Posts, Users, Comments)
  - Biểu đồ content overview
  - Recent posts và comments
  - Quick actions

### 2. Posts Management (`cms-posts.html`)
- **URL**: `cms-posts.html`
- **Mô tả**: Quản lý bài viết
- **Tính năng**:
  - Danh sách posts với filter
  - Thêm/sửa/xóa posts
  - Status management (Published, Draft, Pending)
  - Category assignment
  - Search và pagination

### 3. Users Management (`cms-users.html`)
- **URL**: `cms-users.html`
- **Mô tả**: Quản lý người dùng
- **Tính năng**:
  - User list với roles
  - Add new user
  - User status management
  - Role assignment

### 4. Categories Management (`cms-categories.html`)
- **URL**: `cms-categories.html`
- **Mô tả**: Quản lý danh mục
- **Tính năng**:
  - Category list với icons
  - Add/edit categories
  - Post count per category
  - Status management

## 🎨 UI/UX Features

### Design System
- **Color Scheme**: Professional blue theme
- **Typography**: Clean và readable fonts
- **Icons**: Material Design Icons
- **Components**: Bootstrap 5 components

### Responsive Design
- **Mobile-first**: Tối ưu cho mobile
- **Tablet**: Responsive cho tablet
- **Desktop**: Full features cho desktop

### User Experience
- **Intuitive Navigation**: Menu rõ ràng và dễ sử dụng
- **Quick Actions**: Buttons và shortcuts
- **Search & Filter**: Tìm kiếm và lọc dữ liệu
- **Modal Forms**: Forms trong modal cho UX tốt hơn

## 🔧 Cài đặt và sử dụng

### 1. Cài đặt
```bash
# Clone hoặc download project
# Mở file cms-dashboard.html trong browser
```

### 2. Sử dụng
1. **Mở CMS**: Truy cập `cms-dashboard.html`
2. **Navigation**: Sử dụng sidebar menu để điều hướng
3. **Content Management**: 
   - Tạo posts mới từ Dashboard
   - Quản lý categories trong Categories page
   - Upload media trong Media Library
4. **User Management**: Quản lý users và roles
5. **Settings**: Cấu hình website trong Settings

### 3. Customization
- **Colors**: Thay đổi trong CSS variables
- **Layout**: Tùy chỉnh trong HTML structure
- **Features**: Thêm tính năng mới theo nhu cầu

## 📱 Responsive Breakpoints

- **Mobile**: < 768px
- **Tablet**: 768px - 1024px
- **Desktop**: > 1024px

## 🎯 Best Practices

### Content Management
- **SEO Optimization**: Meta tags, descriptions
- **Image Optimization**: Compress images
- **Content Quality**: Regular updates
- **User Engagement**: Comments, social sharing

### Security
- **User Authentication**: Secure login
- **Role-based Access**: Permission control
- **Data Validation**: Input validation
- **Backup**: Regular data backup

### Performance
- **Image Optimization**: WebP format
- **Code Minification**: Minified CSS/JS
- **Caching**: Browser caching
- **CDN**: Content delivery network

## 🔮 Roadmap

### Phase 1 (Current)
- ✅ Dashboard với thống kê
- ✅ Posts management
- ✅ Users management
- ✅ Categories management

### Phase 2 (Future)
- 🔄 Advanced analytics
- 🔄 SEO tools
- 🔄 Email marketing
- 🔄 Social media integration

### Phase 3 (Advanced)
- 🔄 E-commerce features
- 🔄 Multi-language support
- 🔄 API integration
- 🔄 Advanced security

## 📞 Support

Nếu bạn cần hỗ trợ hoặc có câu hỏi:
- **Documentation**: Đọc file README này
- **Issues**: Tạo issue trên repository
- **Features**: Đề xuất tính năng mới

## 📄 License

Dự án này sử dụng template Velzon Admin Dashboard với license tương ứng.

---

**CMS - Content Management System** | Built with ❤️ using Velzon Admin Template 