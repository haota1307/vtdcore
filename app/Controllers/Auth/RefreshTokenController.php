<?php
namespace App\Controllers\Auth;

use CodeIgniter\RESTful\ResourceController;

class RefreshTokenController extends ResourceController
{
    protected $format = 'json';

    public function issue()
    {
        $user = service('auth')->user();
        if (!$user) return $this->failUnauthorized();
        $rt = service('refreshTokens')->issue($user['id']);
        return $this->respondCreated($rt);
    }

    public function rotate()
    {
        $token = $this->request->getPost('refresh_token');
        if (!$token) return $this->failValidationErrors('Missing refresh_token');
        $new = service('refreshTokens')->rotate($token);
        if (!$new) return $this->failUnauthorized('Invalid refresh token');
        audit_event('refresh.rotate',[ 'user_id'=>$new['user_id'] ]);
        // optionally issue short-lived access token
        $access = service('tokens')->issue($new['user_id'],'access',['*'],15); // 15 min TTL
        return $this->respond(['refresh'=>$new,'access'=>$access]);
    }

    public function revoke()
    {
        $token = $this->request->getPost('refresh_token');
        if (!$token) return $this->failValidationErrors('Missing refresh_token');
        service('refreshTokens')->revoke($token);
        audit_event('refresh.revoke',[]);
        return $this->respond(['revoked'=>true]);
    }
}
