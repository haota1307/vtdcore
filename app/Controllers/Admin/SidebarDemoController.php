<?php

namespace App\Controllers\Admin;

class SidebarDemoController extends AdminBaseController
{
    public function index()
    {
        // Thêm menu item tùy chỉnh cho demo
        add_sidebar_item('demo', 'custom-feature', [
            'label' => 'Custom Feature',
            'icon' => 'mdi mdi-star',
            'url' => admin_url('sidebar-demo/custom'),
            'permissions' => ['admin.dashboard'] // Chỉ admin mới thấy
        ]);

        // Lấy thông tin user hiện tại
        $user = service('auth')->user();
        $permissions = get_user_permissions();
        $roles = get_user_roles();
        $menuItems = get_sidebar_menu();

        return $this->render('sidebar_demo/index', [
            'title' => 'Sidebar Demo',
            'user' => $user,
            'permissions' => $permissions,
            'roles' => $roles,
            'menuItems' => $menuItems
        ]);
    }

    public function custom()
    {
        return $this->render('sidebar_demo/custom', [
            'title' => 'Custom Feature'
        ]);
    }

    public function debug()
    {
        // Chỉ admin mới có thể truy cập
        if (!$this->guard('admin.dashboard')) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $sidebarService = service('sidebar');
        
        return $this->render('sidebar_demo/debug', [
            'title' => 'Sidebar Debug',
            'userPermissions' => $sidebarService->getUserPermissions(),
            'userRoles' => $sidebarService->getUserRoles(),
            'menuItems' => $sidebarService->getMenuItems()
        ]);
    }
}
