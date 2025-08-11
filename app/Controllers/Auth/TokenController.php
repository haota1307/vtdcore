<?php
namespace App\Controllers\Auth;

use App\Controllers\BaseController;

class TokenController extends BaseController
{
    public function issue()
    {
        $auth = service('auth');
        $user = $auth->user();
        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON(['error'=>'Unauthenticated']);
        }
        $name = $this->request->getPost('name') ?? 'api';
        $abilities = $this->request->getPost('abilities');
        if (is_string($abilities)) {
            $abilities = array_map('trim', explode(',', $abilities));
        }
        if (!$abilities) $abilities = ['*'];
        $ttl = $this->request->getPost('ttl'); // minutes
        $issued = service('tokens')->issue($user['id'], $name, $abilities, $ttl? (int)$ttl : null);
        return $this->response->setJSON($issued);
    }

    public function revoke($id = null)
    {
        $auth = service('auth');
        $user = $auth->user();
        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON(['error'=>'Unauthenticated']);
        }
        $tokenModel = new \App\Models\UserApiTokenModel();
        $token = $tokenModel->find($id);
        if (!$token || (int)$token['user_id'] !== (int)$user['id']) {
            return $this->response->setStatusCode(404)->setJSON(['error'=>'Token not found']);
        }
        service('tokens')->revoke($id);
        return $this->response->setJSON(['revoked'=>true]);
    }
}
