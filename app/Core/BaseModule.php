<?php

namespace App\Core;

use App\Core\Contracts\ModuleInterface;
use CodeIgniter\Router\RouteCollection;

abstract class BaseModule implements ModuleInterface
{
    public function getDependencies(): array
    {
        return [];
    }

    public function register(): void
    {
        // default no-op
    }

    public function boot(): void
    {
        // default no-op
    }

    public function routes(RouteCollection $routes): void
    {
        // default no routes
    }
}
