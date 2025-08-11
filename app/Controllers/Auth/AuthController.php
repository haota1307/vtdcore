<?php
namespace App\Controllers\Auth;

use App\Services\AuthService;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    protected $format = 'json';

    private AuthService $auth;

    public function __construct()
    {
        $this->auth = service('auth');
    }

    public function register(): ResponseInterface
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        if (! isset($data['username'],$data['email'],$data['password'])) {
            return $this->failValidationErrors('Missing fields');
        }
        $sec = config(\App\Config\AuthSecurity::class);
        $pw = (string)$data['password'];
        $errors = [];
        if (strlen($pw) < $sec->passwordMinLength) $errors[] = 'min_length';
        if ($sec->passwordRequireNumber && !preg_match('/\d/',$pw)) $errors[] = 'number';
        if ($sec->passwordRequireSymbol && !preg_match('/[^a-zA-Z0-9]/',$pw)) $errors[] = 'symbol';
        if ($sec->passwordRequireUpper && !preg_match('/[A-Z]/',$pw)) $errors[] = 'upper';
        if ($errors) return $this->failValidationErrors('weak_password:'.implode(',',$errors));
        $user = $this->auth->register($data['username'],$data['email'],$data['password']);
        unset($user['password_hash']);
        return $this->respondCreated(['user'=>$user]);
    }

    public function login(): ResponseInterface
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        if (! isset($data['login'],$data['password'])) {
            return $this->failValidationErrors('Missing login or password');
        }
        $identifier = strtolower((string)$data['login']);
        $ip = $this->request->getIPAddress();
        $config = new \App\Config\AuthSecurity();
        $cache = cache();
        $key = 'login_attempts:' . sha1($identifier.'|'.$ip);
        $attemptData = $cache->get($key) ?? ['count'=>0,'first'=>time()];
        if (time() - $attemptData['first'] > $config->loginDecayMinutes*60) {
            $attemptData = ['count'=>0,'first'=>time()];
        }
        if ($attemptData['count'] >= $config->loginMaxAttempts) {
            $retry = ($config->loginDecayMinutes*60) - (time()-$attemptData['first']);
            return $this->failTooManyRequests('Too many attempts. Retry after '.$retry.'s');
        }
        if (! $this->auth->attempt($data['login'],$data['password'])) {
            $attemptData['count']++;
            $cache->save($key,$attemptData,$config->loginDecayMinutes*60);
            audit_event('auth.login.fail',[ 'login'=>$data['login'] ]);
            return $this->failUnauthorized('Invalid credentials');
        }
        $cache->delete($key);
        $user = $this->auth->user();
    audit_event('auth.login.success',[ 'user_id'=>$user['id'],'login'=>$data['login'] ]);
        unset($user['password_hash']);
        return $this->respond(['user'=>$user]);
    }

    public function logout(): ResponseInterface
    {
        $this->auth->logout();
        return $this->respond(['message'=>'Logged out']);
    }

    public function me(): ResponseInterface
    {
        $u = $this->auth->user();
        if (! $u) { return $this->failUnauthorized(); }
        unset($u['password_hash']);
        return $this->respond(['user'=>$u]);
    }
}
