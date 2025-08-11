<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TestController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'System Test',
            'tests' => [
                'auth_service' => $this->testAuthService(),
                'admin_url_helper' => $this->testAdminUrlHelper(),
                'database_connection' => $this->testDatabaseConnection(),
                'session_working' => $this->testSession(),
                'admin_login_route' => $this->testAdminLoginRoute(),
                'user_model' => $this->testUserModel(),
            ]
        ];

        return view('test/index', $data);
    }

    private function testAuthService(): array
    {
        try {
            $auth = service('auth');
            return [
                'status' => 'success',
                'message' => 'AuthService loaded successfully',
                'class' => get_class($auth)
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    private function testAdminUrlHelper(): array
    {
        try {
            if (function_exists('admin_url')) {
                $url = admin_url('auth/login');
                return [
                    'status' => 'success',
                    'message' => 'admin_url() function works',
                    'example' => $url
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'admin_url() function not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    private function testDatabaseConnection(): array
    {
        try {
            $db = \Config\Database::connect();
            $result = $db->query('SELECT 1 as test')->getRow();
            return [
                'status' => 'success',
                'message' => 'Database connection successful',
                'test_result' => $result->test
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    private function testSession(): array
    {
        try {
            session()->set('test_key', 'test_value');
            $value = session()->get('test_key');
            session()->remove('test_key');
            
            if ($value === 'test_value') {
                return [
                    'status' => 'success',
                    'message' => 'Session working correctly'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Session not working properly'
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    private function testAdminLoginRoute(): array
    {
        try {
            $routes = service('routes');
            $adminLoginRoute = $routes->getRoutes('get')['admin/auth/login'] ?? null;
            
            if ($adminLoginRoute) {
                return [
                    'status' => 'success',
                    'message' => 'Admin login route exists',
                    'controller' => $adminLoginRoute
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Admin login route not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    private function testUserModel(): array
    {
        try {
            $userModel = new \App\Models\UserModel();
            $count = $userModel->countAll();
            return [
                'status' => 'success',
                'message' => 'UserModel working correctly',
                'total_users' => $count
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
