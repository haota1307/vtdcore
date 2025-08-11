<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;

class TwoFactorFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = service('auth');
        $user = $auth->user();
        if (!$user) {
            return service('response')->setStatusCode(401)->setJSON(['error'=>'Unauthenticated']);
        }
        $row = Database::connect()->table('user_twofactor')->where('user_id',$user['id'])->get()->getRowArray();
        if ($row && $row['enabled_at']) {
            $session = session();
            if ($session->get('2fa_verified_user') !== $user['id']) {
                return service('response')->setStatusCode(428)->setJSON(['error'=>'2FA required']);
            }
        }
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
