<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class MakeModule extends BaseCommand
{
    protected $group       = 'Modules';
    protected $name        = 'make:module';
    protected $description = 'Scaffold a new module structure.';
    protected $usage       = 'make:module <Name>';    

    public function run(array $params)
    {
        $name = $params[0] ?? null;
        if (! $name) {
            CLI::error('Module name required.');
            return;
        }
        $studly = preg_replace('/[^A-Za-z0-9]/', '', $name);
        $moduleDir = APPPATH . 'Modules/' . $studly;
        if (is_dir($moduleDir)) {
            CLI::error('Module already exists: ' . $studly);
            return;
        }
        $dirs = [
            $moduleDir,
            $moduleDir . '/Http/Controllers',
            $moduleDir . '/Database/Migrations',
            $moduleDir . '/Database/Seeds',
            $moduleDir . '/Config',
            $moduleDir . '/Views',
        ];
        foreach ($dirs as $d) {
            if (! is_dir($d)) { mkdir($d, 0775, true); }
        }
        $id = strtolower($studly);

        $moduleTpl = <<<'PHP'
<?php

namespace App\Modules\%STUDLY%;

use App\Core\BaseModule;
use CodeIgniter\Router\RouteCollection;

class Module extends BaseModule
{
    public function getId(): string { return '%ID%'; }
    public function getName(): string { return '%STUDLY% Module'; }
    public function getVersion(): string { return '0.1.0'; }

    public function routes(RouteCollection $routes): void
    {
        // Inline route example (can also use Routes.php file)
        $routes->group('%ID%', static function(RouteCollection $routes) {
            $routes->get('/', [\App\Modules\%STUDLY%\Http\Controllers\SampleController::class, 'index']);
        });
    }
}
PHP;
        $moduleClass = str_replace(['%STUDLY%','%ID%'], [$studly, $id], $moduleTpl);
        file_put_contents($moduleDir . '/Module.php', $moduleClass);

        $controllerTpl = <<<'PHP'
<?php

namespace App\Modules\%STUDLY%\Http\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class SampleController extends Controller
{
    public function index(): ResponseInterface
    {
        return $this->response->setJSON(['module'=>'%ID%','message'=>'Hello from %STUDLY%']);
    }
}
PHP;
        $controller = str_replace(['%STUDLY%','%ID%'], [$studly, $id], $controllerTpl);
        file_put_contents($moduleDir . '/Http/Controllers/SampleController.php', $controller);

        $routesStub = <<<'PHP'
<?php
/** @var CodeIgniter\Router\RouteCollection $r */
// Extra routes for %STUDLY% module can go here.
// Example:
// $r->group('%ID%', function($r){ $r->get('ping', function(){ return 'pong'; }); });
PHP;
        $routesStub = str_replace(['%STUDLY%','%ID%'], [$studly,$id], $routesStub);
        file_put_contents($moduleDir . '/Routes.php', $routesStub);
        CLI::write('Module created: ' . $studly);
    }
}
