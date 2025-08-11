<?php
namespace App\Controllers\Admin;

use CodeIgniter\HTTP\ResponseInterface;

class UsersController extends AdminBaseController
{
    protected ?string $requiredPermission = 'admin.users.view';

    public function index()
    {
        $model = new \App\Models\UserModel();
        $data = $this->paginate($model, 25);
        
        return $this->render('users/manage', [
            'title' => 'Users Management',
            'users' => $data['items'],
            'meta' => $data['pager'],
            'user' => $this->auth->user(),
        ]);
    }

    public function create()
    {
        if ($resp = $this->guardManage()) return $resp;
        
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        
        // Validation
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            return $this->response->setJSON(['error' => 'Username, email and password are required'])->setStatusCode(400);
        }
        
        $model = new \App\Models\UserModel();
        
        // Check if username or email already exists
        if ($model->where('username', $data['username'])->first()) {
            return $this->response->setJSON(['error' => 'Username already exists'])->setStatusCode(400);
        }
        
        if ($model->where('email', $data['email'])->first()) {
            return $this->response->setJSON(['error' => 'Email already exists'])->setStatusCode(400);
        }
        
        // Create user
        $userData = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'status' => $data['status'] ?? 'active',
        ];
        
        $userId = $model->insert($userData);
        
        if (function_exists('audit_event')) {
            audit_event('user.create', ['id' => $userId, 'username' => $data['username']]);
        }
        
        return $this->response->setJSON(['success' => true, 'user_id' => $userId]);
    }

    public function show($id)
    {
        if ($resp = $this->guardManage()) return $resp;
        
        $model = new \App\Models\UserModel();
        $user = $model->find($id);
        
        if (!$user) {
            return $this->response->setJSON(['error' => 'User not found'])->setStatusCode(404);
        }
        
        unset($user['password_hash']);
        return $this->response->setJSON(['user' => $user]);
    }

    public function update($id)
    {
        if ($resp = $this->guardManage()) return $resp;
        
        $model = new \App\Models\UserModel();
        $user = $model->find($id);
        
        if (!$user) {
            return $this->response->setJSON(['error' => 'User not found'])->setStatusCode(404);
        }
        
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        $updateData = [];
        
        // Update fields if provided
        if (isset($data['username'])) {
            $existingUser = $model->where('username', $data['username'])->where('id !=', $id)->first();
            if ($existingUser) {
                return $this->response->setJSON(['error' => 'Username already exists'])->setStatusCode(400);
            }
            $updateData['username'] = $data['username'];
        }
        
        if (isset($data['email'])) {
            $existingUser = $model->where('email', $data['email'])->where('id !=', $id)->first();
            if ($existingUser) {
                return $this->response->setJSON(['error' => 'Email already exists'])->setStatusCode(400);
            }
            $updateData['email'] = $data['email'];
        }
        
        if (isset($data['password']) && !empty($data['password'])) {
            $updateData['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        if (isset($data['status'])) {
            $updateData['status'] = $data['status'];
        }
        
        if (!empty($updateData)) {
            $model->update($id, $updateData);
            
            if (function_exists('audit_event')) {
                audit_event('user.update', ['id' => $id, 'fields' => array_keys($updateData)]);
            }
        }
        
        return $this->response->setJSON(['success' => true]);
    }

    public function delete($id)
    {
        if ($resp = $this->guardManage()) return $resp;
        
        $model = new \App\Models\UserModel();
        $user = $model->find($id);
        
        if (!$user) {
            return $this->response->setJSON(['error' => 'User not found'])->setStatusCode(404);
        }
        
        // Prevent deleting own account
        if ((int)$id === (int)$this->auth->user()['id']) {
            return $this->response->setJSON(['error' => 'Cannot delete your own account'])->setStatusCode(400);
        }
        
        $model->delete($id);
        
        if (function_exists('audit_event')) {
            audit_event('user.delete', ['id' => $id, 'username' => $user['username']]);
        }
        
        return $this->response->setJSON(['success' => true]);
    }

    protected function guardManage()
    {
        return $this->guard('admin.users.manage');
    }

    public function toggle($id)
    {
        if ($resp = $this->guardManage()) return $resp;
        $model = new \App\Models\UserModel();
        $user = $model->find($id);
        if (! $user) return service('response')->setStatusCode(404);
        $new = ($user['status'] ?? 'active') === 'active' ? 'disabled' : 'active';
        $model->update($id, ['status'=>$new]);
        if (function_exists('audit_event')) audit_event('user.status.toggle',['id'=>$id,'to'=>$new]);
        return service('response')->setJSON(['id'=>$id,'status'=>$new]);
    }

    public function resetPassword($id)
    {
        if ($resp = $this->guardManage()) return $resp;
        $model = new \App\Models\UserModel();
        $user = $model->find($id);
        if (! $user) return service('response')->setStatusCode(404);
        $newPass = bin2hex(random_bytes(4));
        $model->update($id, ['password_hash'=>password_hash($newPass, PASSWORD_DEFAULT)]);
        if (function_exists('audit_event')) audit_event('user.password.reset',['id'=>$id]);
        return service('response')->setJSON(['id'=>$id,'new_password'=>$newPass]);
    }

    public function testDropdown()
    {
        return view('admin/users/test-dropdown');
    }
}
