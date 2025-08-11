<?php

namespace App\Services;

class SidebarService
{
    private array $menuItems = [];
    private array $userPermissions = [];
    private array $userRoles = [];

    public function __construct()
    {
        $this->loadUserData();
        $this->buildMenuItems();
    }

    /**
     * Load current user's permissions and roles
     */
    private function loadUserData(): void
    {
        $auth = service('auth');
        $user = $auth->user();
        
        if ($user) {
            $this->userPermissions = service('permissions')->userPermissions($user['id']);
            $this->userRoles = service('roles')->userRoles($user['id']);
        }
    }

    /**
     * Check if user has any of the specified permissions
     */
    private function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (in_array($permission, $this->userPermissions, true)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the specified permissions
     */
    private function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!in_array($permission, $this->userPermissions, true)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if user has any of the specified roles
     */
    private function hasAnyRole(array $roleSlugs): bool
    {
        foreach ($this->userRoles as $role) {
            if (in_array($role['slug'], $roleSlugs, true)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Build menu items configuration
     */
    private function buildMenuItems(): void
    {
        $this->menuItems = [
            'main' => [
                'title' => 'Menu',
                'items' => [
                    'dashboard' => [
                        'label' => 'Dashboard',
                        'icon' => 'mdi mdi-speedometer',
                        'url' => admin_url(),
                        'permissions' => [], // Always visible for logged in users
                        'always_visible' => true
                    ],
                    'users' => [
                        'label' => 'Users',
                        'icon' => 'mdi mdi-account-group',
                        'url' => admin_url('users'),
                        'permissions' => ['admin.users.view', 'admin.users.manage']
                    ],
                    'roles' => [
                        'label' => 'Roles',
                        'icon' => 'mdi mdi-shield-account',
                        'url' => admin_url('roles'),
                        'permissions' => ['admin.roles.view', 'admin.roles.manage']
                    ],
                    'media' => [
                        'label' => 'Media',
                        'icon' => 'mdi mdi-image-multiple',
                        'url' => admin_url('media'),
                        'permissions' => ['admin.media.manage', 'manager.media.manage', 'editor.media.upload', 'editor.media.view']
                    ],
                    'settings' => [
                        'label' => 'Settings',
                        'icon' => 'mdi mdi-cog',
                        'url' => admin_url('settings'),
                        'permissions' => ['admin.settings.manage']
                    ],
                    'audit' => [
                        'label' => 'Audit Logs',
                        'icon' => 'mdi mdi-file-document',
                        'url' => admin_url('audit'),
                        'permissions' => ['admin.audit.view', 'admin.audit.manage']
                    ]
                ]
            ],
            'system' => [
                'title' => 'System',
                'permissions' => ['system.manage', 'system.config', 'system.backup'],
                'items' => [
                    'config' => [
                        'label' => 'Configuration',
                        'icon' => 'mdi mdi-tune',
                        'url' => admin_url('system/config'),
                        'permissions' => ['system.config']
                    ],
                    'backup' => [
                        'label' => 'Backup',
                        'icon' => 'mdi mdi-backup-restore',
                        'url' => admin_url('system/backup'),
                        'permissions' => ['system.backup']
                    ]
                ]
            ],
            'manager' => [
                'title' => 'Manager',
                'permissions' => ['manager.dashboard', 'manager.users.view', 'manager.content.manage', 'manager.reports.view'],
                'items' => [
                    'dashboard' => [
                        'label' => 'Manager Dashboard',
                        'icon' => 'mdi mdi-view-dashboard',
                        'url' => admin_url('manager/dashboard'),
                        'permissions' => ['manager.dashboard']
                    ],
                    'content' => [
                        'label' => 'Content Management',
                        'icon' => 'mdi mdi-file-document-edit',
                        'url' => admin_url('manager/content'),
                        'permissions' => ['manager.content.manage']
                    ],
                    'reports' => [
                        'label' => 'Reports',
                        'icon' => 'mdi mdi-chart-line',
                        'url' => admin_url('manager/reports'),
                        'permissions' => ['manager.reports.view']
                    ]
                ]
            ],
            'editor' => [
                'title' => 'Editor',
                'permissions' => ['editor.dashboard', 'editor.content.create', 'editor.content.edit', 'editor.content.publish'],
                'items' => [
                    'dashboard' => [
                        'label' => 'Editor Dashboard',
                        'icon' => 'mdi mdi-pencil-box',
                        'url' => admin_url('editor/dashboard'),
                        'permissions' => ['editor.dashboard']
                    ],
                    'create' => [
                        'label' => 'Create Content',
                        'icon' => 'mdi mdi-plus-circle',
                        'url' => admin_url('editor/content/create'),
                        'permissions' => ['editor.content.create']
                    ],
                    'manage' => [
                        'label' => 'Manage Content',
                        'icon' => 'mdi mdi-file-edit',
                        'url' => admin_url('editor/content'),
                        'permissions' => ['editor.content.edit', 'editor.content.publish']
                    ]
                ]
            ],
            'user' => [
                'title' => 'User',
                'permissions' => ['user.dashboard', 'user.profile.view', 'user.profile.edit'],
                'items' => [
                    'dashboard' => [
                        'label' => 'User Dashboard',
                        'icon' => 'mdi mdi-account',
                        'url' => admin_url('user/dashboard'),
                        'permissions' => ['user.dashboard']
                    ],
                    'profile' => [
                        'label' => 'Profile',
                        'icon' => 'mdi mdi-account-circle',
                        'url' => admin_url('profile'),
                        'permissions' => ['user.profile.view', 'user.profile.edit']
                    ]
                ]
            ],
            'account' => [
                'title' => 'Account',
                'always_visible' => true,
                'items' => [
                    'profile' => [
                        'label' => 'Profile',
                        'icon' => 'mdi mdi-account-circle',
                        'url' => admin_url('profile'),
                        'always_visible' => true
                    ],
                    'logout' => [
                        'label' => 'Logout',
                        'icon' => 'mdi mdi-logout',
                        'url' => admin_url('auth/logout'),
                        'always_visible' => true
                    ]
                ]
            ]
        ];
    }

    /**
     * Get filtered menu items based on user permissions
     */
    public function getMenuItems(): array
    {
        $filteredMenu = [];

        foreach ($this->menuItems as $sectionKey => $section) {
            // Check if section should be visible
            if (isset($section['always_visible']) && $section['always_visible']) {
                $sectionVisible = true;
            } elseif (isset($section['permissions'])) {
                $sectionVisible = $this->hasAnyPermission($section['permissions']);
            } else {
                $sectionVisible = false;
            }

            if (!$sectionVisible) {
                continue;
            }

            $filteredItems = [];
            foreach ($section['items'] as $itemKey => $item) {
                // Check if item should be visible
                if (isset($item['always_visible']) && $item['always_visible']) {
                    $itemVisible = true;
                } elseif (isset($item['permissions'])) {
                    $itemVisible = $this->hasAnyPermission($item['permissions']);
                } else {
                    $itemVisible = false;
                }

                if ($itemVisible) {
                    $filteredItems[$itemKey] = $item;
                }
            }

            // Only add section if it has visible items
            if (!empty($filteredItems)) {
                $filteredMenu[$sectionKey] = $section;
                $filteredMenu[$sectionKey]['items'] = $filteredItems;
            }
        }

        return $filteredMenu;
    }

    /**
     * Add a custom menu item
     */
    public function addMenuItem(string $section, string $key, array $item): void
    {
        if (!isset($this->menuItems[$section])) {
            $this->menuItems[$section] = [
                'title' => ucfirst($section),
                'items' => []
            ];
        }

        $this->menuItems[$section]['items'][$key] = $item;
    }

    /**
     * Remove a menu item
     */
    public function removeMenuItem(string $section, string $key): void
    {
        if (isset($this->menuItems[$section]['items'][$key])) {
            unset($this->menuItems[$section]['items'][$key]);
        }
    }

    /**
     * Get user permissions (for debugging)
     */
    public function getUserPermissions(): array
    {
        return $this->userPermissions;
    }

    /**
     * Get user roles (for debugging)
     */
    public function getUserRoles(): array
    {
        return $this->userRoles;
    }
}
