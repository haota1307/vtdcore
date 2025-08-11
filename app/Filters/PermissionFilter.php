<?php
namespace App\Filters;

use App\Services\RBAC\PermissionService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $permission = $arguments[0] ?? null;
        if (! $permission) {
            return service('response')->setStatusCode(500)->setJSON(['error'=>'Permission not specified']);
        }
        $auth = service('auth');
        $user = $auth->user();
        if (! $user) {
            return service('response')->setStatusCode(401)->setJSON(['error'=>'Unauthenticated']);
        }
        /** @var PermissionService $perms */
        $perms = service('permissions');
        if (! $perms->userHas($user['id'], $permission)) {
            return service('response')->setStatusCode(403)->setJSON(['error'=>'Forbidden','missing'=>$permission]);
        }
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
