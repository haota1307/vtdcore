<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends AdminBaseController
{
    public function index()
    {
        $user = $this->auth->user();
        
        $data = [
            'title' => 'Dashboard',
            'user' => $user,
            'stats' => [
                'total_users' => $this->getTotalUsers(),
                'total_media' => $this->getTotalMedia(),
                'total_orders' => $this->getTotalOrders(),
                'total_revenue' => $this->getTotalRevenue(),
                'active_sessions' => $this->getActiveSessions(),
            ],
            'recent_activities' => $this->getRecentActivities(),
            'quick_actions' => $this->getQuickActions(),
            'system_info' => $this->getSystemInfo(),
        ];

        return $this->render('dashboard/index', $data);
    }

    private function getTotalUsers(): int
    {
        $userModel = new \App\Models\UserModel();
        return $userModel->countAll();
    }

    private function getTotalMedia(): int
    {
        $mediaModel = new \App\Models\MediaModel();
        return $mediaModel->countAll();
    }

    private function getRecentActivities(): array
    {
        $auditModel = new \App\Models\AuditLogModel();
        $activities = $auditModel->orderBy('created_at', 'DESC')
                                ->limit(10)
                                ->findAll();
        
        // Transform audit log data to match view expectations
        $transformed = [];
        foreach ($activities as $activity) {
            $transformed[] = [
                'color' => $this->getActivityColor($activity['action']),
                'icon' => $this->getActivityIcon($activity['action']),
                'message' => $this->getActivityMessage($activity),
                'time' => $activity['created_at']
            ];
        }
        
        return $transformed;
    }

    private function getActivityColor(string $action): string
    {
        $colors = [
            'admin.login.success' => 'success',
            'admin.login.fail' => 'danger',
            'admin.logout' => 'warning',
            'user.created' => 'primary',
            'user.updated' => 'info',
            'user.deleted' => 'danger',
            'media.uploaded' => 'success',
            'media.deleted' => 'danger',
            'settings.updated' => 'warning',
        ];
        
        return $colors[$action] ?? 'secondary';
    }

    private function getActivityIcon(string $action): string
    {
        $icons = [
            'admin.login.success' => 'log-in',
            'admin.login.fail' => 'alert-circle',
            'admin.logout' => 'log-out',
            'user.created' => 'user-plus',
            'user.updated' => 'user',
            'user.deleted' => 'user-minus',
            'media.uploaded' => 'upload',
            'media.deleted' => 'trash-2',
            'settings.updated' => 'settings',
        ];
        
        return $icons[$action] ?? 'activity';
    }

    private function getActivityMessage(array $activity): string
    {
        $action = $activity['action'];
        $context = json_decode($activity['context'] ?? '{}', true);
        
        switch ($action) {
            case 'admin.login.success':
                return 'Admin login successful';
            case 'admin.login.fail':
                return 'Failed login attempt';
            case 'admin.logout':
                return 'Admin logged out';
            case 'user.created':
                return 'New user created';
            case 'user.updated':
                return 'User profile updated';
            case 'user.deleted':
                return 'User deleted';
            case 'media.uploaded':
                return 'Media file uploaded';
            case 'media.deleted':
                return 'Media file deleted';
            case 'settings.updated':
                return 'System settings updated';
            default:
                return ucfirst(str_replace('.', ' ', $action));
        }
    }

    private function getTotalOrders(): int
    {
        // TODO: Implement when order system is available
        return 0;
    }

    private function getTotalRevenue(): float
    {
        // TODO: Implement when order system is available
        return 0.00;
    }

    private function getActiveSessions(): int
    {
        // TODO: Implement when session tracking is available
        return 0;
    }

    private function getQuickActions(): array
    {
        return [
            [
                'title' => 'Add User',
                'url' => admin_url('users/create'),
                'icon' => 'user-plus',
                'color' => 'primary'
            ],
            [
                'title' => 'Upload Media',
                'url' => admin_url('media/upload'),
                'icon' => 'upload',
                'color' => 'success'
            ],
            [
                'title' => 'View Logs',
                'url' => admin_url('logs'),
                'icon' => 'file-text',
                'color' => 'info'
            ],
            [
                'title' => 'Settings',
                'url' => admin_url('settings'),
                'icon' => 'settings',
                'color' => 'warning'
            ]
        ];
    }

    private function getSystemInfo(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'codeigniter_version' => \CodeIgniter\CodeIgniter::CI_VERSION,
            'framework_version' => 'VTDevCore 1.0.0',
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_usage' => $this->formatBytes(memory_get_usage(true)),
            'peak_memory' => $this->formatBytes(memory_get_peak_usage(true)),
            'disk_free_space' => $this->formatBytes(disk_free_space('/')),
            'uptime' => $this->getUptime(),
        ];
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    private function getUptime(): string
    {
        // TODO: Implement actual uptime tracking
        return 'Unknown';
    }
}
