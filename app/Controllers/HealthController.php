<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class HealthController extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();
        try { $db->query('SELECT 1'); $dbOk = true; } catch (\Throwable $e) { $dbOk = false; }
        $cacheOk = true; try { cache()->save('health_ping','1',30); $cacheOk = cache()->get('health_ping')==='1'; } catch (\Throwable $e) { $cacheOk=false; }
        $metrics = [
            'php_version'=>PHP_VERSION,
            'memory_usage_mb'=>round(memory_get_usage()/1048576,2),
            'loaded_extensions'=>count(get_loaded_extensions()),
        ];
        $status = ($dbOk && $cacheOk) ? 200 : 500;
        return service('response')->setStatusCode($status)->setJSON([
            'status'=>$status===200?'ok':'degraded',
            'checks'=>[
                'database'=>$dbOk,
                'cache'=>$cacheOk,
            ],
            'metrics'=>$metrics,
            'timestamp'=>gmdate('c')
        ]);
    }
}
