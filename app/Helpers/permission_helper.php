<?php

if (!function_exists('user_has_permission')) {
    /**
     * Check if current user has a specific permission
     * 
     * @param string $permission Permission slug to check
     * @return bool
     */
    function user_has_permission(string $permission): bool
    {
        $auth = service('auth');
        $user = $auth->user();
        
        if (!$user) {
            return false;
        }
        
        return service('permissions')->userHas($user['id'], $permission);
    }
}

if (!function_exists('user_has_any_permission')) {
    /**
     * Check if current user has any of the specified permissions
     * 
     * @param array $permissions Array of permission slugs to check
     * @return bool
     */
    function user_has_any_permission(array $permissions): bool
    {
        $auth = service('auth');
        $user = $auth->user();
        
        if (!$user) {
            return false;
        }
        
        $userPermissions = service('permissions')->userPermissions($user['id']);
        
        foreach ($permissions as $permission) {
            if (in_array($permission, $userPermissions, true)) {
                return true;
            }
        }
        
        return false;
    }
}

if (!function_exists('user_has_all_permissions')) {
    /**
     * Check if current user has all of the specified permissions
     * 
     * @param array $permissions Array of permission slugs to check
     * @return bool
     */
    function user_has_all_permissions(array $permissions): bool
    {
        $auth = service('auth');
        $user = $auth->user();
        
        if (!$user) {
            return false;
        }
        
        $userPermissions = service('permissions')->userPermissions($user['id']);
        
        foreach ($permissions as $permission) {
            if (!in_array($permission, $userPermissions, true)) {
                return false;
            }
        }
        
        return true;
    }
}

if (!function_exists('user_has_role')) {
    /**
     * Check if current user has a specific role
     * 
     * @param string $roleSlug Role slug to check
     * @return bool
     */
    function user_has_role(string $roleSlug): bool
    {
        $auth = service('auth');
        $user = $auth->user();
        
        if (!$user) {
            return false;
        }
        
        $roles = service('roles')->userRoles($user['id']);
        
        foreach ($roles as $role) {
            if ($role['slug'] === $roleSlug) {
                return true;
            }
        }
        
        return false;
    }
}

if (!function_exists('user_has_any_role')) {
    /**
     * Check if current user has any of the specified roles
     * 
     * @param array $roleSlugs Array of role slugs to check
     * @return bool
     */
    function user_has_any_role(array $roleSlugs): bool
    {
        $auth = service('auth');
        $user = $auth->user();
        
        if (!$user) {
            return false;
        }
        
        $roles = service('roles')->userRoles($user['id']);
        
        foreach ($roles as $role) {
            if (in_array($role['slug'], $roleSlugs, true)) {
                return true;
            }
        }
        
        return false;
    }
}

if (!function_exists('get_user_permissions')) {
    /**
     * Get all permissions for current user
     * 
     * @return array
     */
    function get_user_permissions(): array
    {
        $auth = service('auth');
        $user = $auth->user();
        
        if (!$user) {
            return [];
        }
        
        return service('permissions')->userPermissions($user['id']);
    }
}

if (!function_exists('get_user_roles')) {
    /**
     * Get all roles for current user
     * 
     * @return array
     */
    function get_user_roles(): array
    {
        $auth = service('auth');
        $user = $auth->user();
        
        if (!$user) {
            return [];
        }
        
        return service('roles')->userRoles($user['id']);
    }
}

if (!function_exists('get_sidebar_menu')) {
    /**
     * Get filtered sidebar menu items based on user permissions
     * 
     * @return array
     */
    function get_sidebar_menu(): array
    {
        return service('sidebar')->getMenuItems();
    }
}

if (!function_exists('add_sidebar_item')) {
    /**
     * Add a custom sidebar menu item
     * 
     * @param string $section Section name
     * @param string $key Item key
     * @param array $item Item configuration
     */
    function add_sidebar_item(string $section, string $key, array $item): void
    {
        service('sidebar')->addMenuItem($section, $key, $item);
    }
}

if (!function_exists('remove_sidebar_item')) {
    /**
     * Remove a sidebar menu item
     * 
     * @param string $section Section name
     * @param string $key Item key
     */
    function remove_sidebar_item(string $section, string $key): void
    {
        service('sidebar')->removeMenuItem($section, $key);
    }
}
