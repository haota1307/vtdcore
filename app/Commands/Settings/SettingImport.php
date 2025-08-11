<?php
namespace App\Commands\Settings;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class SettingImport extends BaseCommand
{
    protected $group = 'Settings';
    protected $name = 'setting:import';
    protected $description = 'Import settings from JSON file';
    protected $usage = 'setting:import path/to/file.json';

    public function run(array $params)
    {
        $path = $params[0] ?? null;
        if (!$path || !is_file($path)) { CLI::error('File not found'); return; }
        $json = json_decode(file_get_contents($path), true);
        if (!is_array($json)) { CLI::error('Invalid JSON'); return; }
        $db = Database::connect();
        $count=0;
        foreach ($json as $row) {
            if (!isset($row['scope_type'],$row['key'])) continue;
            $exists = $db->table('settings')
                ->where('scope_type',$row['scope_type'])
                ->where('key',$row['key'])
                ->where('scope_id', $row['scope_type']==='user'?($row['scope_id']??null):null)
                ->where('module', $row['module']??null)
                ->get()->getRowArray();
            $data = [
                'scope_type'=>$row['scope_type'],
                'scope_id'=>$row['scope_type']==='user'?($row['scope_id']??null):null,
                'module'=>$row['module']??null,
                'key'=>$row['key'],
                'value'=>$row['value']??null,
                'type'=>$row['type']??'string',
                'updated_at'=>date('Y-m-d H:i:s'),
            ];
            if ($exists) {
                $db->table('settings')->where('id',$exists['id'])->update($data);
            } else {
                $data['created_at']=date('Y-m-d H:i:s');
                $db->table('settings')->insert($data);
            }
            $count++;
        }
        service('settings')->clearCache();
        CLI::write('Imported '.$count.' settings','green');
    }
}
