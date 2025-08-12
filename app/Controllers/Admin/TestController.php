<?php
namespace App\Controllers\Admin;

use CodeIgniter\HTTP\ResponseInterface;

class TestController extends AdminBaseController
{
    /**
     * Render admin layout với nội dung từ `app/Views/pages/...`
     * Truyền tham số ?page=pages/xxx.html (mặc định chọn một trang mẫu có sẵn)
     */
    public function index(): string|ResponseInterface
    {
        // Bảo vệ (auth/twofactor đã có ở routes). Cho phép không cần permission riêng.
        $guard = $this->guard(null);
        if ($guard !== null) { return $guard; }

        $req = service('request');
        $page = (string) ($req->getGet('page') ?? 'pages/apps-calendar.html');

        // Chỉ cho phép trong thư mục pages/ để tránh traversal
        if (! str_starts_with($page, 'pages/')) {
            $page = 'pages/apps-file-manager.html';
        }

        // Nếu file không tồn tại, rơi về trang mặc định
        $viewsRoot = rtrim(APPPATH . 'Views' . DIRECTORY_SEPARATOR, '/\\');
        $candidate = $viewsRoot . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $page);
        if (! is_file($candidate)) {
            $page = 'pages/apps-file-manager.html';
        }

        $data = [ 'title' => 'Admin Test', 'breadcrumbs' => function_exists('build_admin_breadcrumbs') ? build_admin_breadcrumbs() : [] ];

        // Truyền trực tiếp content_view là đường dẫn view ở dưới app/Views
        $wrapper = $data + [
            'content_view' => $page,
            'content_data' => [],
        ];
        return view('admin/layout/main', $wrapper);
    }
}


