<?php
namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Base Admin Controller providing:
 *  - Automatic layout rendering
 *  - Breadcrumb auto-build
 *  - Permission guard via $requiredPermission property
 */

class AdminBaseController extends Controller
{
    protected $helpers = ['breadcrumb', 'media', 'permission'];
    protected ?string $requiredPermission = null; // override per controller or method
    protected $auth;

    public function __construct()
    {
        $this->auth = service('auth');
        
        // Check if user is logged in for protected routes
        if (!$this->auth->user()) {
            // Only redirect if not on auth pages
            $currentPath = service('request')->getPath();
            if (!str_starts_with($currentPath, 'admin/auth')) {
                return redirect()->to(admin_url('auth/login'));
            }
        }
    }

    protected function guard(?string $perm = null): ?ResponseInterface
    {
        $perm = $perm ?? $this->requiredPermission;
        
        if (!$perm) return null;
        $user = service('auth')->user();
        if (!$user) {
            return service('response')->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }
        
        if (! service('permissions')->userHas($user['id'], $perm)) {
            return service('response')->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }
        return null;
    }

    protected function render(string $view, array $data = [], ?string $permission = null): string|ResponseInterface
    {
        $guardResult = $this->guard($permission);
        if ($guardResult !== null) {
            return $guardResult;
        }
        if (! isset($data['title'])) {
            $segments = explode('/', trim($view,'/'));
            $last = end($segments);
            $data['title'] = ucwords(str_replace(['-','_'],' ', $last));
        }
        if (! isset($data['breadcrumbs']) && function_exists('build_admin_breadcrumbs')) {
            $data['breadcrumbs'] = build_admin_breadcrumbs();
        }
        $wrapperData = $data + [
            'content_view' => 'admin/' . ltrim($view,'/'),
            'content_data' => $data,
        ];
        return view('admin/layout/main', $wrapperData);
    }

    protected function paginate(Model|\CodeIgniter\Model $model, int $perPage = 20, string $group = 'default'): array
    {
        $page = (int) (service('request')->getGet('page') ?? 1); if ($page < 1) $page = 1;
        $rows = $model->orderBy('id','desc')->paginate($perPage, $group, $page);
        $pager = $model->pager;
        return [
            'items' => $rows,
            'pager' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $pager->getTotal(),
                'page_count' => $pager->getPageCount(),
            ],
            'pagerObj' => $pager,
        ];
    }
}
