<?php
namespace App\Controllers\Auth;

use CodeIgniter\RESTful\ResourceController;

class TwoFactorController extends ResourceController
{
    protected $format = 'json';

    public function enable()
    {
        $user = service('auth')->user();
        if (!$user) return $this->failUnauthorized();
        $secret = service('twoFactor')->enable($user['id']);
        return $this->respond(['secret'=>$secret['secret'],'info'=>'Use reversed first 6 chars as code']);
    }

    public function verify()
    {
        $user = service('auth')->user();
        if (!$user) return $this->failUnauthorized();
        $code = $this->request->getPost('code');
        if (!$code) return $this->failValidationErrors('Missing code');
        if (! service('twoFactor')->verify($user['id'],$code)) {
            return $this->failUnauthorized('Invalid code');
        }
        session()->set('2fa_verified_user',$user['id']);
        return $this->respond(['verified'=>true]);
    }

    public function disable()
    {
        $user = service('auth')->user(); if (!$user) return $this->failUnauthorized();
        service('twoFactor')->disable($user['id']);
        audit_event('2fa.disable',[ 'user_id'=>$user['id'] ]);
        return $this->respond(['disabled'=>true]);
    }

    public function backupCodes()
    {
        $user = service('auth')->user(); if (!$user) return $this->failUnauthorized();
        $codes = service('twoFactor')->generateBackupCodes($user['id']);
        audit_event('2fa.backup.generate',[ 'user_id'=>$user['id'] ]);
        return $this->respond(['backup_codes'=>$codes]);
    }

    public function backupVerify()
    {
        $user = service('auth')->user(); if (!$user) return $this->failUnauthorized();
        $code = $this->request->getPost('code'); if (!$code) return $this->failValidationErrors('Missing code');
        if (!service('twoFactor')->consumeBackupCode($user['id'],$code)) {
            audit_event('2fa.backup.fail',[ 'user_id'=>$user['id'] ]);
            return $this->failUnauthorized('Invalid code');
        }
        session()->set('2fa_verified_user',$user['id']);
        audit_event('2fa.backup.success',[ 'user_id'=>$user['id'] ]);
        return $this->respond(['verified_via_backup'=>true]);
    }
}
