<?php
namespace App\Commands\Settings;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class SettingExport extends BaseCommand
{
    protected $group = 'Settings';
    protected $name = 'setting:export';
    protected $description = 'Export all settings to JSON file';
    protected $usage = 'setting:export path/to/file.json';

    public function run(array $params)
    {
        $path = $params[0] ?? null;
        if (!$path) { CLI::error('Missing output path'); return; }
        $rows = Database::connect()->table('settings')->get()->getResultArray();
        file_put_contents($path, json_encode($rows, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        CLI::write('Exported '.count($rows).' settings to '.$path,'green');
    }
}
