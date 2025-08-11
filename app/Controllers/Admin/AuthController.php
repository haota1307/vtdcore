<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;
use App\Services\PasswordResetService;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends AdminBaseController
{
    private PasswordResetService $passwordReset;

    public function __construct()
    {
        parent::__construct();
        $this->passwordReset = service('passwordReset');
    }

    /**
     * Show login page
     */
    public function login()
    {
        // If already logged in, redirect to dashboard
        if ($this->auth->user()) {
            return redirect()->to(admin_url());
        }

        return view('admin/auth/login', [
            'title' => 'Admin Login'
        ]);
    }

    /**
     * Process login form
     */
    public function processLogin(): ResponseInterface
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember') === 'on';

        // Validation
        if (empty($email) || empty($password)) {
            session()->setFlashdata('error', 'Email and password are required');
            return redirect()->back()->withInput();
        }

        // Attempt login
        if ($this->auth->attempt($email, $password)) {
            $user = $this->auth->user();
            
            // Check if user has admin access
            if (!$this->hasAdminAccess($user)) {
                $this->auth->logout();
                session()->setFlashdata('error', 'Access denied. Admin privileges required.');
                return redirect()->back()->withInput();
            }

            // Set remember me if requested
            if ($remember) {
                session()->set('remember_me', true);
            }

            // Log successful login
            audit_event('admin.login.success', [
                'user_id' => $user['id'],
                'email' => $email,
                'ip' => $this->request->getIPAddress()
            ]);

            session()->setFlashdata('success', 'Welcome back, ' . $user['username'] . '!');
            return redirect()->to(admin_url());
        }

        // Log failed login
        audit_event('admin.login.fail', [
            'email' => $email,
            'ip' => $this->request->getIPAddress()
        ]);

        session()->setFlashdata('error', 'Invalid email or password');
        return redirect()->back()->withInput();
    }

    /**
     * Show forgot password page
     */
    public function forgotPassword()
    {
        return view('admin/auth/forgot_password', [
            'title' => 'Forgot Password'
        ]);
    }

    /**
     * Process forgot password form
     */
    public function processForgotPassword(): ResponseInterface
    {
        $email = $this->request->getPost('email');

        if (empty($email)) {
            session()->setFlashdata('error', 'Email address is required');
            return redirect()->back()->withInput();
        }

        try {
            $resetData = $this->passwordReset->create($email);
            if ($resetData) {
                // TODO: Send email with reset token
                session()->setFlashdata('success', 'Password reset link has been sent to your email');
            } else {
                session()->setFlashdata('error', 'Email address not found');
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Show reset password page
     */
    public function resetPassword()
    {
        $token = $this->request->getGet('token');
        $email = $this->request->getGet('email');
        
        if (empty($token) || empty($email)) {
            session()->setFlashdata('error', 'Invalid reset token');
            return redirect()->to(admin_url('auth/login'));
        }

        // Verify token
        try {
            if ($this->passwordReset->verify($email, $token)) {
                return view('admin/auth/reset_password', [
                    'title' => 'Reset Password',
                    'token' => $token,
                    'email' => $email
                ]);
            } else {
                session()->setFlashdata('error', 'Invalid or expired reset token');
                return redirect()->to(admin_url('auth/login'));
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Invalid or expired reset token');
            return redirect()->to(admin_url('auth/login'));
        }
    }

    /**
     * Process reset password form
     */
    public function processResetPassword(): ResponseInterface
    {
        $token = $this->request->getPost('token');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (empty($token) || empty($email) || empty($password) || empty($confirmPassword)) {
            session()->setFlashdata('error', 'All fields are required');
            return redirect()->back()->withInput();
        }

        if ($password !== $confirmPassword) {
            session()->setFlashdata('error', 'Passwords do not match');
            return redirect()->back()->withInput();
        }

        // Validate password strength
        $errors = $this->validatePassword($password);
        if (!empty($errors)) {
            session()->setFlashdata('error', 'Password does not meet requirements: ' . implode(', ', $errors));
            return redirect()->back()->withInput();
        }

        try {
            if ($this->passwordReset->consume($email, $token, $password)) {
                session()->setFlashdata('success', 'Password has been reset successfully. You can now login with your new password.');
                return redirect()->to(admin_url('auth/login'));
            } else {
                session()->setFlashdata('error', 'Invalid or expired reset token');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Logout
     */
    public function logout(): ResponseInterface
    {
        $user = $this->auth->user();
        if ($user) {
            audit_event('admin.logout', [
                'user_id' => $user['id'],
                'ip' => $this->request->getIPAddress()
            ]);
        }

        $this->auth->logout();
        session()->setFlashdata('success', 'You have been logged out successfully');
        return redirect()->to(admin_url('auth/login'));
    }

    /**
     * Check if user has admin access
     */
    private function hasAdminAccess(array $user): bool
    {
        // Check if user is active
        if (isset($user['status']) && $user['status'] !== 'active') {
            return false;
        }

        // Check for admin role or specific permissions
        $roles = service('roles')->userRoles($user['id']);
        foreach ($roles as $role) {
            if ($role['slug'] === 'admin' || $role['slug'] === 'super-admin') {
                return true;
            }
        }

        // Check for admin permissions
        $permissions = service('roles')->userAggregatedPermissions($user['id']);
        foreach ($permissions as $permission) {
            if (strpos($permission, 'admin.') === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate password strength
     */
    private function validatePassword(string $password): array
    {
        $errors = [];
        $config = config(\App\Config\AuthSecurity::class);

        if (strlen($password) < $config->passwordMinLength) {
            $errors[] = 'minimum ' . $config->passwordMinLength . ' characters';
        }

        if ($config->passwordRequireNumber && !preg_match('/\d/', $password)) {
            $errors[] = 'at least one number';
        }

        if ($config->passwordRequireSymbol && !preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors[] = 'at least one special character';
        }

        if ($config->passwordRequireUpper && !preg_match('/[A-Z]/', $password)) {
            $errors[] = 'at least one uppercase letter';
        }

        return $errors;
    }
}
