<?php

namespace App\Core;

use App\Core\Contracts\ModuleInterface;
use CodeIgniter\Router\RouteCollection;
use RuntimeException;

class ModuleManager
{
    private string $modulesPath;
    /** @var array<string,ModuleInterface> keyed by lower id */
    private array $modules = [];
    private bool $initialized = false;
    /** @var array<string,bool> original-case id => status */
    private array $statuses = [];
    private string $statusFile;
    private string $manifestFile;
    private ?array $manifest = null;
    private ModuleScanner $scanner;

    public function __construct(?string $modulesPath = null)
    {
        $this->modulesPath = rtrim($modulesPath ?? APPPATH . 'Modules', '/\\') . DIRECTORY_SEPARATOR;
        $this->statusFile = WRITEPATH . 'modules' . DIRECTORY_SEPARATOR . 'statuses.php';
        $this->manifestFile = WRITEPATH . 'cache' . DIRECTORY_SEPARATOR . 'modules_manifest.php';
        $this->loadStatuses();
        $this->scanner = new ModuleScanner($this->modulesPath, $this->manifestFile);
    }

    private function loadStatuses(): void
    {
        if (is_file($this->statusFile)) {
            $data = include $this->statusFile;
            if (is_array($data)) {
                $this->statuses = $data;
            }
        }
    }

    private function saveStatuses(): void
    {
        $dir = dirname($this->statusFile);
        if (! is_dir($dir)) { mkdir($dir, 0775, true); }
        $export = '<?php return ' . var_export($this->statuses, true) . ';';
        $tmp = $this->statusFile . '.' . uniqid('tmp', true);
        $fp = fopen($tmp, 'wb');
        if ($fp) {
            fwrite($fp, $export);
            fflush($fp);
            fclose($fp);
            @rename($tmp, $this->statusFile);
        } else {
            file_put_contents($this->statusFile, $export, LOCK_EX);
        }
    }

    public function clearCache(): void
    {
        $this->manifest = null;
        $this->scanner->clearCache();
    }

    public function scan(): void
    {
        $result = $this->scanner->scan();
        $this->modules = $result['modules'];
        $this->manifest = $result['manifest'];
        foreach ($this->modules as $lowerId => $m) {
            $origId = $m->getId();
            if (! array_key_exists($origId, $this->statuses)) {
                $this->statuses[$origId] = true; // default enabled
            }
        }
        $this->saveStatuses();
    }

    private function resolveOrder(): array
    {
        $sorted = [];
        $temp = [];
        $perm = [];
        $visit = function(string $lowerId) use (&$visit, &$sorted, &$temp, &$perm) {
            if (isset($perm[$lowerId])) return;
            if (isset($temp[$lowerId])) throw new RuntimeException('Circular module dependency at ' . $lowerId);
            $temp[$lowerId] = true;
            $mod = $this->modules[$lowerId] ?? null;
            if ($mod) {
                foreach ($mod->getDependencies() as $depOrig) {
                    $dep = strtolower($depOrig);
                    if (! isset($this->modules[$dep])) {
                        throw new RuntimeException("Missing dependency '$depOrig' required by '{$mod->getId()}'");
                    }
                    $visit($dep);
                }
            }
            $perm[$lowerId] = true;
            unset($temp[$lowerId]);
            $sorted[] = $lowerId;
        };
        foreach (array_keys($this->modules) as $lid) { $visit($lid); }
        return $sorted;
    }

    public function initialize(): void
    {
        if ($this->initialized) return;
        $this->scan();
        $order = $this->resolveOrder();
        foreach ($order as $lid) {
            $m = $this->modules[$lid];
            if (! $this->isEnabled($m->getId())) continue;
            $m->register();
        }
        foreach ($order as $lid) {
            $m = $this->modules[$lid];
            if (! $this->isEnabled($m->getId())) continue;
            $m->boot();
        }
        $this->initialized = true;
    }

    public function routes(RouteCollection $routes): void
    {
        $this->initialize();
        foreach ($this->modules as $lid => $module) {
            $origId = $module->getId();
            if (! $this->isEnabled($origId)) continue;
            $module->routes($routes);
            $ref = new \ReflectionClass($module);
            $root = dirname($ref->getFileName());
            foreach ([ $root . '/Routes.php', $root . '/Config/Routes.php'] as $candidate) {
                if (is_file($candidate)) {
                    $r = $routes; /** @var RouteCollection $r */
                    require $candidate;
                }
            }
        }
    }

    public function all(): array
    {
        $this->initialize();
        return array_values(array_filter($this->modules, fn($m)=>$this->isEnabled($m->getId())));
    }

    public function isEnabled(string $id): bool
    {
        foreach ($this->statuses as $orig => $val) {
            if (strcasecmp($orig, $id) === 0) return $val;
        }
        return false;
    }

    public function setEnabled(string $id, bool $enabled): void
    {
        $matched = null;
        foreach (array_keys($this->statuses) as $orig) {
            if (strcasecmp($orig, $id) === 0) { $matched = $orig; break; }
        }
        if ($matched === null) {
            $this->statuses[$id] = $enabled;
        } else {
            $this->statuses[$matched] = $enabled;
        }
        $this->saveStatuses();
        $this->clearCache();
        $this->initialized = false;
        $this->modules = [];
    }

    public function getManifest(): ?array
    {
        return $this->manifest;
    }

    public function getStatuses(): array
    {
        return $this->statuses;
    }
}
