<div class="page-content">
    <div class="container-fluid">
        <!-- Tiêu đề trang -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <h4 class="mb-sm-0 fw-bold">Thêm người dùng mới</h4>
                        <p class="text-muted mb-0">Tạo tài khoản người dùng mới trong hệ thống</p>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= admin_url() ?>">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="<?= admin_url('users') ?>">Người dùng</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông báo -->
        <?php if (session()->getFlashdata('success')): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i data-feather="check-circle" class="me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (isset($errors['general'])): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i data-feather="alert-circle" class="me-2"></i>
                    <?= esc($errors['general']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Form thêm người dùng -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card create-user-card">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i data-feather="user-plus" class="icon-sm me-2 text-primary"></i>
                            Thông tin người dùng
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= admin_url('users') ?>" method="POST" id="createUserForm">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">
                                            Tên đăng nhập <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i data-feather="user" class="icon-xs"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" 
                                                   id="username" 
                                                   name="username" 
                                                   value="<?= esc($old_data['username'] ?? '') ?>"
                                                   placeholder="Nhập tên đăng nhập" 
                                                   required>
                                        </div>
                                        <?php if (isset($errors['username'])): ?>
                                        <div class="invalid-feedback d-block">
                                            <i data-feather="alert-circle" class="icon-xs me-1"></i>
                                            <?= esc($errors['username']) ?>
                                        </div>
                                        <?php endif; ?>
                                        <div class="form-text">Tên đăng nhập phải có ít nhất 3 ký tự</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            Địa chỉ email <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i data-feather="mail" class="icon-xs"></i>
                                            </span>
                                            <input type="email" 
                                                   class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                                   id="email" 
                                                   name="email" 
                                                   value="<?= esc($old_data['email'] ?? '') ?>"
                                                   placeholder="Nhập địa chỉ email" 
                                                   required>
                                        </div>
                                        <?php if (isset($errors['email'])): ?>
                                        <div class="invalid-feedback d-block">
                                            <i data-feather="alert-circle" class="icon-xs me-1"></i>
                                            <?= esc($errors['email']) ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">
                                            Mật khẩu <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i data-feather="lock" class="icon-xs"></i>
                                            </span>
                                            <input type="password" 
                                                   class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                                   id="password" 
                                                   name="password" 
                                                   placeholder="Nhập mật khẩu" 
                                                   required>
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i data-feather="eye" class="icon-xs"></i>
                                            </button>
                                        </div>
                                        <?php if (isset($errors['password'])): ?>
                                        <div class="invalid-feedback d-block">
                                            <i data-feather="alert-circle" class="icon-xs me-1"></i>
                                            <?= esc($errors['password']) ?>
                                        </div>
                                        <?php endif; ?>
                                        <div class="form-text">Mật khẩu phải có ít nhất 8 ký tự</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">
                                            Xác nhận mật khẩu <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i data-feather="lock" class="icon-xs"></i>
                                            </span>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="confirm_password" 
                                                   name="confirm_password" 
                                                   placeholder="Nhập lại mật khẩu" 
                                                   required>
                                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                                <i data-feather="eye" class="icon-xs"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback" id="confirmPasswordError" style="display: none;">
                                            <i data-feather="alert-circle" class="icon-xs me-1"></i>
                                            Mật khẩu xác nhận không khớp
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Trạng thái</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="active" <?= ($old_data['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>
                                                Hoạt động
                                            </option>
                                            <option value="inactive" <?= ($old_data['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>
                                                Không hoạt động
                                            </option>
                                        </select>
                                        <div class="form-text">Chọn trạng thái tài khoản sau khi tạo</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tùy chọn</label>
                                        <div class="form-text">
                                            <i data-feather="info" class="icon-xs me-1"></i>
                                            Tài khoản sẽ được tạo với thông tin cơ bản. Người dùng có thể cập nhật thêm thông tin sau khi đăng nhập.
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="<?= admin_url('users') ?>" class="btn btn-secondary">
                                            <i data-feather="arrow-left" class="icon-xs me-1"></i>
                                            Quay lại
                                        </a>
                                        <button type="reset" class="btn btn-outline-secondary">
                                            <i data-feather="refresh-cw" class="icon-xs me-1"></i>
                                            Đặt lại
                                        </button>
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i data-feather="plus" class="icon-xs me-1"></i>
                                            Tạo người dùng
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Card styling */
.create-user-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.create-user-card .card-header {
    border-bottom: 1px solid #e3e6f0;
    border-radius: 12px 12px 0 0;
}

.info-card {
    border: 1px solid #e3e6f0;
    border-radius: 8px;
}

.info-card .card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    padding: 0.75rem 1rem;
}

.info-card .card-body {
    padding: 1rem;
}

/* Form styling */
.form-control:focus,
.form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.input-group-text {
    background-color: #f8f9fc;
    border-color: #e3e6f0;
}

.form-check-input:checked {
    background-color: #4e73df;
    border-color: #4e73df;
}

/* Icons */
.icon-xs {
    width: 16px;
    height: 16px;
}

.icon-sm {
    width: 18px;
    height: 18px;
}

/* Button styling */
.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2e59d9;
}

/* Alert styling */
.alert {
    border-radius: 8px;
    border: none;
}

.alert-success {
    background-color: rgba(28, 200, 138, 0.1);
    color: #1cc88a;
}

/* Validation styling */
.is-invalid {
    border-color: #e74a3b;
}

.invalid-feedback {
    color: #e74a3b;
    font-size: 0.875rem;
}

/* Responsive */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace({ 'stroke-width': 1.5 });
    }

    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('svg');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.setAttribute('data-feather', 'eye-off');
            } else {
                passwordInput.type = 'password';
                icon.setAttribute('data-feather', 'eye');
            }
            feather.replace();
        });
    }
    
    if (toggleConfirmPassword) {
        toggleConfirmPassword.addEventListener('click', function() {
            const confirmPasswordInput = document.getElementById('confirm_password');
            const icon = this.querySelector('svg');
            
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                icon.setAttribute('data-feather', 'eye-off');
            } else {
                confirmPasswordInput.type = 'password';
                icon.setAttribute('data-feather', 'eye');
            }
            feather.replace();
        });
    }

    // Password confirmation validation
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const confirmPasswordError = document.getElementById('confirmPasswordError');
    
    function validatePasswordMatch() {
        if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.classList.add('is-invalid');
            confirmPasswordError.style.display = 'block';
            return false;
        } else {
            confirmPasswordInput.classList.remove('is-invalid');
            confirmPasswordError.style.display = 'none';
            return true;
        }
    }
    
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', validatePasswordMatch);
        passwordInput.addEventListener('input', validatePasswordMatch);
    }

    // Form validation
    const form = document.getElementById('createUserForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate required fields
            const requiredFields = ['username', 'email', 'password', 'confirm_password'];
            requiredFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            // Validate password match
            if (!validatePasswordMatch()) {
                isValid = false;
            }
            
            // Validate email format
            const emailField = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailField.value && !emailRegex.test(emailField.value)) {
                emailField.classList.add('is-invalid');
                isValid = false;
            }
            
            // Validate username length
            const usernameField = document.getElementById('username');
            if (usernameField.value && usernameField.value.length < 3) {
                usernameField.classList.add('is-invalid');
                isValid = false;
            }
            
            // Validate password length
            if (passwordInput.value && passwordInput.value.length < 8) {
                passwordInput.classList.add('is-invalid');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Vui lòng kiểm tra và sửa các lỗi trong form', 'error');
            } else {
                // Show loading state
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i data-feather="loader" class="icon-xs me-1 spinner"></i> Đang tạo...';
                feather.replace();
            }
        });
    }

    // Real-time validation
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid') && this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
});

// Show notification function
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
