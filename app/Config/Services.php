<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you may use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */

    public static function auth($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('auth');
        }

        return new \App\Services\AuthService();
    }

    public static function media($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('media');
        }

        return new \App\Services\MediaService();
    }

    public static function virusScanner($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('virusScanner');
        }

        return new \App\Services\VirusScannerService();
    }

    public static function audit($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('audit');
        }

        return new \App\Services\AuditService();
    }

    public static function settings($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('settings');
        }

        return new \App\Services\SettingsService();
    }

    public static function roles($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('roles');
        }

        return new \App\Services\RoleService();
    }

    public static function permissions($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('permissions');
        }

        return new \App\Services\RBAC\PermissionService();
    }

    public static function passwordReset($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('passwordReset');
        }

        return new \App\Services\PasswordResetService();
    }

    public static function twoFactor($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('twoFactor');
        }

        return new \App\Services\TwoFactorService();
    }

    public static function token($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('token');
        }

        return new \App\Services\TokenService();
    }

    public static function refreshToken($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('refreshToken');
        }

        return new \App\Services\RefreshTokenService();
    }

    public static function modules($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('modules');
        }

        return new \App\Core\ModuleManager(APPPATH . 'Modules');
    }

    public static function sidebar($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('sidebar');
        }

        return new \App\Services\SidebarService();
    }
}
