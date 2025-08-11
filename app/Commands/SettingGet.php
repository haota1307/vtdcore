<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SettingGet extends BaseCommand
{
    protected $group = 'Settings';
    protected $name = 'setting:get';
    protected $description = 'Get a setting by precedence. Usage: setting:get [--user=ID] [--module=mod] key';

    public function run(array $params)
    {
        $opts = CLI::getOptions();
        $userId = isset($opts['user']) ? (int)$opts['user'] : null;
        $module = $opts['module'] ?? null;
        $key = $params[0] ?? null;
        if (! $key) { CLI::error('key required'); return; }
        $svc = service('settings');
        $value = $svc->get($key, array_filter(['user_id'=>$userId,'module'=>$module]));
        CLI::write(json_encode(['key'=>$key,'value'=>$value], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    }
}
