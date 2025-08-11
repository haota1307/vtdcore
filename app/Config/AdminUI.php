<?php
namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class AdminUI extends BaseConfig
{
    /** Segment label overrides for breadcrumbs */
    public array $breadcrumbLabels = [
        'users' => 'Users',
        'roles' => 'Roles',
        'permissions' => 'Permissions',
        'media' => 'Media Library',
        'settings' => 'Settings',
        'rbac' => 'RBAC',
        'dashboard' => 'Dashboard',
        'files' => 'Files',
        'modules' => 'Modules',
    ];

    /** Segments to skip in breadcrumb building beyond the admin prefix */
    public array $breadcrumbSkip = [];
}
