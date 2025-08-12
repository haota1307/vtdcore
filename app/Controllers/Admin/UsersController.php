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
        
        return $this->render('users/index', [
            'title' => 'Users Management',
            'users' => $data['items'],
            'meta' => $data['pager'],
            'user' => $this->auth->user(),
        ]);
    }

    public function createForm()
    {
        if ($resp = $this->guardManage()) return $resp;
        
        return $this->render('users/create', [
            'title' => 'Thêm người dùng mới',
        ]);
    }

    public function create()
    {
        if ($resp = $this->guardManage()) return $resp;
        
        // Check if this is a JSON request
        $contentType = $this->request->getHeaderLine('Content-Type');
        $isJsonRequest = strpos($contentType, 'application/json') !== false;
        
        // Debug logging
        log_message('debug', 'UsersController::create - Content-Type: ' . $contentType);
        log_message('debug', 'UsersController::create - Is JSON: ' . ($isJsonRequest ? 'yes' : 'no'));
        
        if ($isJsonRequest) {
            try {
                $data = $this->request->getJSON(true);
                if ($data === null) {
                    // Try to get raw input and decode manually
                    $rawInput = $this->request->getBody();
                    if (!empty($rawInput)) {
                        $data = json_decode($rawInput, true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            throw new \Exception('JSON decode error: ' . json_last_error_msg());
                        }
                    } else {
                        $data = [];
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'JSON parsing error in UsersController::create: ' . $e->getMessage());
                return $this->response->setJSON(['error' => 'Invalid JSON data: ' . $e->getMessage()])->setStatusCode(400);
            }
        } else {
            $data = $this->request->getPost();
            // Fallback: if POST is empty, try to get from request body
            if (empty($data)) {
                $rawInput = $this->request->getBody();
                if (!empty($rawInput)) {
                    parse_str($rawInput, $data);
                }
            }
        }
        
        $isFormSubmission = !$isJsonRequest;
        
        // Debug logging
        log_message('debug', 'UsersController::create - Data received: ' . json_encode($data));
        log_message('debug', 'UsersController::create - Is form submission: ' . ($isFormSubmission ? 'yes' : 'no'));
        
        // Validation
        $errors = [];
        
        if (!$data || !is_array($data)) {
            if ($isFormSubmission) {
                return $this->render('users/create', [
                    'title' => 'Thêm người dùng mới',
                    'errors' => ['general' => 'Dữ liệu không hợp lệ'],
                ]);
            }
            return $this->response->setJSON(['error' => 'No data provided'])->setStatusCode(400);
        }
        
        if (empty($data['username'])) {
            $errors['username'] = 'Tên đăng nhập là bắt buộc';
        } elseif (strlen($data['username']) < 3) {
            $errors['username'] = 'Tên đăng nhập phải có ít nhất 3 ký tự';
        }
        
        if (empty($data['email'])) {
            $errors['email'] = 'Email là bắt buộc';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        }
        
        if (empty($data['password'])) {
            $errors['password'] = 'Mật khẩu là bắt buộc';
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = 'Mật khẩu phải có ít nhất 8 ký tự';
        }
        
        if (!empty($errors)) {
            if ($isFormSubmission) {
                return $this->render('users/create', [
                    'title' => 'Thêm người dùng mới',
                    'errors' => $errors,
                    'old_data' => $data
                ]);
            }
            return $this->response->setJSON(['errors' => $errors])->setStatusCode(400);
        }
        
        $model = new \App\Models\UserModel();
        
        // Check if username or email already exists
        if ($model->where('username', $data['username'])->first()) {
            $error = 'Tên đăng nhập đã tồn tại';
            if ($isFormSubmission) {
                return $this->render('users/create', [
                    'title' => 'Thêm người dùng mới',
                    'errors' => ['username' => $error],
                    'old_data' => $data
                ]);
            }
            return $this->response->setJSON(['error' => $error])->setStatusCode(400);
        }
        
        if ($model->where('email', $data['email'])->first()) {
            $error = 'Email đã tồn tại';
            if ($isFormSubmission) {
                return $this->render('users/create', [
                    'title' => 'Thêm người dùng mới',
                    'errors' => ['email' => $error],
                    'old_data' => $data
                ]);
            }
            return $this->response->setJSON(['error' => $error])->setStatusCode(400);
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
        
        if ($isFormSubmission) {
            session()->setFlashdata('success', 'Người dùng đã được tạo thành công');
            return redirect()->to(admin_url('users'));
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
    
    public function exportToExcel()
    {
        if ($resp = $this->guardManage()) return $resp;
        
        // Lấy tất cả người dùng từ database
        $model = new \App\Models\UserModel();
        $users = $model->findAll();
        
        // Áp dụng bộ lọc nếu có
        $status = $this->request->getPost('status');
        $search = $this->request->getPost('search');
        
        if (!empty($status) || !empty($search)) {
            $filteredUsers = [];
            foreach ($users as $user) {
                $matchStatus = empty($status) || (($user['status'] ?? 'active') === 'active' && $status === 'active') || 
                              (($user['status'] ?? 'active') !== 'active' && $status === 'inactive');
                
                $matchSearch = empty($search) || 
                              (stripos($user['username'] ?? '', $search) !== false) || 
                              (stripos($user['email'] ?? '', $search) !== false);
                
                if ($matchStatus && $matchSearch) {
                    $filteredUsers[] = $user;
                }
            }
            $users = $filteredUsers;
        }
        
        // Tạo tên file với định dạng Excel
        $filename = 'danh-sach-nguoi-dung-' . date('Y-m-d-H-i-s') . '.xls';
        
        // Set header để Excel nhận diện đúng
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Tạo HTML cho file Excel
        echo '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Danh sách người dùng</title>
            <style>
                table {
                    border-collapse: collapse;
                    width: 100%;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                .center {
                    text-align: center;
                }
                .text-success {
                    color: green;
                }
                .text-danger {
                    color: red;
                }
            </style>
        </head>
        <body>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Email</th>
                        <th>Trạng thái</th>
                        <th>Đăng nhập cuối</th>
                        <th>Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>';
        
        // Thêm dữ liệu người dùng
        foreach ($users as $user) {
            $status = ($user['status'] ?? 'active') === 'active' ? 
                '<span class="text-success">Hoạt động</span>' : 
                '<span class="text-danger">Không hoạt động</span>';
            
            $lastLogin = !empty($user['last_login_at']) ? 
                date('d/m/Y H:i', strtotime($user['last_login_at'])) : 
                'Chưa đăng nhập';
            
            $createdAt = !empty($user['created_at']) ? 
                date('d/m/Y H:i', strtotime($user['created_at'])) : 
                'N/A';
            
            echo '<tr>
                <td class="center">' . esc($user['id']) . '</td>
                <td>' . esc($user['username'] ?? 'N/A') . '</td>
                <td>' . esc($user['email'] ?? 'N/A') . '</td>
                <td class="center">' . $status . '</td>
                <td class="center">' . $lastLogin . '</td>
                <td class="center">' . $createdAt . '</td>
            </tr>';
        }
        
        echo '</tbody>
            </table>
        </body>
        </html>';
        
        exit;
    }
}
