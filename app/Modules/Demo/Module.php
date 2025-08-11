<?php

namespace App\Modules\Demo;

use App\Core\BaseModule;
use CodeIgniter\Router\RouteCollection;

class Module extends BaseModule
{
    public function getId(): string { return 'demo'; }
    public function getName(): string { return 'Demo Module'; }
    public function getVersion(): string { return '0.1.0'; }

    public function routes(RouteCollection $routes): void
    {
        $routes->group('demo', static function(RouteCollection $routes) {
            $routes->get('/', [\App\Modules\Demo\Http\Controllers\HelloController::class, 'index']);
        });
    }
}
