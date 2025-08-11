<?php

namespace App\Core\Contracts;

use CodeIgniter\Router\RouteCollection;

interface ModuleInterface
{
    /** Unique machine id (slug). */
    public function getId(): string;

    /** Human readable name. */
    public function getName(): string;

    /** Semantic version string. */
    public function getVersion(): string;

    /** List of module ids this module depends on. */
    public function getDependencies(): array;

    /** Called early to register bindings, config, defaults. */
    public function register(): void;

    /** Called after all modules registered; can use others. */
    public function boot(): void;

    /** Provide module route definitions if any. */
    public function routes(RouteCollection $routes): void;
}
