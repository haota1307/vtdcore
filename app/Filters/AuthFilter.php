<?php
namespace App\Filters;

use App\Services\AuthService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = service('auth');
        if (! $auth->user()) {
            return service('response')->setStatusCode(401)->setJSON(['error'=>'Unauthenticated']);
        }
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
}
