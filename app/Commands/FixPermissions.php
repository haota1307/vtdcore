<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class FixPermissions extends BaseCommand
{
    protected $group = 'RBAC';
    protected $name = 'fix:permissions';
    protected $description = 'Fix missing permissions for superadmin role';

    public function run(array $params)
    {
        $db = Database::connect();
        
        CLI::write('=== Fixing Superadmin Permissions ===', 'yellow');
        
        // Check if admin.roles.manage permission exists
        $permission = $db->table('permissions')
            ->where('slug', 'admin.roles.manage')
            ->get()
            ->getRowArray();
            
        if (!$permission) {
            CLI::error('admin.roles.manage permission does not exist!');
            return;
        }
        
        CLI::write("Found permission: {$permission['name']} (ID: {$permission['id']})", 'green');
        
        // Check if already assigned to superadmin
        $existing = $db->table('role_permissions')
            ->where('role_id', 1)
            ->where('permission_id', $permission['id'])
            ->get()
            ->getRowArray();
            
        if ($existing) {
            CLI::write('Permission already assigned to superadmin role.', 'green');
        } else {
            // Add permission to superadmin role
            $result = $db->table('role_permissions')->insert([
                'role_id' => 1,
                'permission_id' => $permission['id']
            ]);
            
            if ($result) {
                CLI::write('SUCCESS: Added admin.roles.manage permission to superadmin role!', 'green');
                
                // Clear permission cache
                service('permissions')->clearCache();
                CLI::write('Permission cache cleared.', 'yellow');
            } else {
                CLI::error('Failed to add permission!');
                return;
            }
        }
        
        // Verify the fix
        $result = $db->table('permissions p')
            ->select('p.slug, p.name')
            ->join('role_permissions rp', 'rp.permission_id = p.id')
            ->where('rp.role_id', 1)
            ->where('p.slug', 'admin.roles.manage')
            ->get()
            ->getRowArray();
        
        if ($result) {
            CLI::write("VERIFICATION: Superadmin now has {$result['name']} permission.", 'green');
        } else {
            CLI::error('VERIFICATION FAILED: Permission assignment failed!');
        }
        
        CLI::write('=== Fix Complete ===', 'yellow');
    }
}
