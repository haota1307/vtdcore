<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SettingSet extends BaseCommand
{
    protected $group = 'Settings';
    protected $name = 'setting:set';
    protected $description = 'Set a setting value. Usage: setting:set [--user=ID] [--module=mod] key value';

    public function run(array $params)
    {
        $opts = CLI::getOptions();
        $userId = isset($opts['user']) ? (int)$opts['user'] : null;
        $module = $opts['module'] ?? null;
        $key = $params[0] ?? null;
        $value = $params[1] ?? null;
        if (! $key) { CLI::error('key required'); return; }
        if ($value === null) { CLI::error('value required'); return; }
        $svc = service('settings');
        $decoded = json_decode($value, true);
        $storeVal = $decoded !== null ? $decoded : $value;
        if ($userId) {
            $svc->setUser($userId, $key, $storeVal, null, $module);
            CLI::write("User setting saved ($userId:$module:$key)");
        } elseif ($module) {
            $svc->setModule($module, $key, $storeVal);
            CLI::write("Module setting saved ($module:$key)");
        } else {
            $svc->setSystem($key, $storeVal);
            CLI::write("System setting saved ($key)");
        }
    }
}
