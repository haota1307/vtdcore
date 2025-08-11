<?php
namespace App\Commands\RBAC;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class PermissionSeed extends BaseCommand
{
    protected $group = 'RBAC';
    protected $name = 'permission:seed';
    protected $description = 'Seed core permissions (audit.view, media.manage, media.read, media.delete).';

    public function run(array $params)
    {
        $db = Database::connect();
        $perms = [
            ['slug'=>'audit.view','name'=>'View audit logs'],
            ['slug'=>'media.manage','name'=>'Manage media'],
            ['slug'=>'media.read','name'=>'Read media'],
            ['slug'=>'media.delete','name'=>'Delete media'],
        ];
        $count=0;
        foreach ($perms as $p) {
            $exists = $db->table('permissions')->where('slug',$p['slug'])->get()->getRowArray();
            if (!$exists) { $db->table('permissions')->insert($p + ['created_at'=>date('Y-m-d H:i:s')]); $count++; }
        }
        CLI::write("Seeded $count new permissions","green");
    }
}
