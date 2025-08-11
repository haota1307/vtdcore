<?php
namespace App\Controllers\Auth;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

class PasswordResetController extends ResourceController
{
    protected $format = 'json';

    public function request(): ResponseInterface
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        $email = $data['email'] ?? null;
        if (!$email) return $this->failValidationErrors('Missing email');
        $reset = service('passwordResets')->create($email,30);
    if ($reset) { audit_event('password.reset.request',[ 'email'=>$email ]); }
        return $this->respond(['message'=>'If account exists, email sent']);
    }

    public function reset(): ResponseInterface
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        foreach (['email','token','password'] as $f) if (!isset($data[$f])) return $this->failValidationErrors('Missing '.$f);
    $sec = config(\App\Config\AuthSecurity::class);
    $pw = (string)$data['password'];
    if (strlen($pw) < $sec->passwordMinLength
        || ($sec->passwordRequireNumber && !preg_match('/\d/',$pw))
        || ($sec->passwordRequireSymbol && !preg_match('/[^a-zA-Z0-9]/',$pw))
        || ($sec->passwordRequireUpper && !preg_match('/[A-Z]/',$pw)) ) {
        return $this->failValidationErrors('weak_password');
    }
    $ok = service('passwordResets')->consume($data['email'],$data['token'],$data['password']);
    if (!$ok) return $this->failUnauthorized('Invalid or expired token');
    audit_event('password.reset.consume',[ 'email'=>$data['email'] ]);
    return $this->respond(['message'=>'Password updated']);
    }
}
