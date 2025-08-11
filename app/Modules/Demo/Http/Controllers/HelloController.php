<?php

namespace App\Modules\Demo\Http\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class HelloController extends Controller
{
    public function index(): ResponseInterface
    {
        return $this->response->setJSON([
            'module' => 'demo',
            'message' => 'Hello from Demo Module',
            'version' => '0.1.0'
        ]);
    }
}
