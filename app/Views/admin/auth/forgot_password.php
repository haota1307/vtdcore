<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Forgot Password' ?> - VTDevCore</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/feather-icons@4.29.0/dist/feather.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.175);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .login-brand {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .login-body {
            padding: 2rem;
        }

        .form-floating {
            margin-bottom: 1rem;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.4);
            color: white;
        }

        .alert {
            border: none;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-brand">
                <i data-feather="lock"></i> VTDevCore
            </div>
            <div class="login-subtitle">Forgot Password</div>
        </div>
        
        <div class="login-body">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i data-feather="check-circle" class="me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i data-feather="alert-circle" class="me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="<?= admin_url('auth/process-forgot-password') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" 
                           value="<?= old('email') ?>" required>
                    <label for="email">Email address</label>
                </div>
                
                <button type="submit" class="btn btn-login w-100">
                    <i data-feather="send" class="me-2"></i>
                    Send Reset Link
                </button>
            </form>
            
            <div class="text-center mt-3">
                <a href="<?= admin_url('auth/login') ?>" class="text-decoration-none">
                    <i data-feather="arrow-left" class="me-1"></i>
                    Back to Login
                </a>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.0/dist/feather.min.js"></script>
    
    <script>
        // Initialize Feather Icons
        feather.replace();

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html> 