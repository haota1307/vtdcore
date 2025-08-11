<?php
namespace App\Services;

use CodeIgniter\I18n\Time;
use Config\Database;

class RoleService
{
    private $db;
    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create(string $name, array $permissions = []): array
    {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i','-',$name));
        $this->db->table('roles')->insert([
            'name'=>$name,
            'slug'=>$slug,
            'created_at'=>Time::now()->toDateTimeString()
        ]);
        $id = $this->db->insertID();
        foreach ($permissions as $p) {
            // Find permission by slug and get its ID
            $perm = $this->db->table('permissions')->where('slug', $p)->get()->getRowArray();
            if ($perm) {
                $this->db->table('role_permissions')->insert(['role_id'=>$id,'permission_id'=>$perm['id']]);
            }
        }
        return $this->find($id);
    }

    public function find(int $id): ?array
    {
        $role = $this->db->table('roles')->where('id',$id)->get()->getRowArray();
        if (!$role) return null;
        $perms = $this->db->table('role_permissions rp')
            ->select('p.slug')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('rp.role_id',$id)
            ->get()->getResultArray();
        $role['permissions'] = array_column($perms,'slug');
        return $role;
    }

    public function attachRoleToUser(int $userId, int $roleId): bool
    {
        return $this->db->table('user_roles')->insert(['user_id'=>$userId,'role_id'=>$roleId]);
    }

    public function userRoles(int $userId): array
    {
        $rows = $this->db->table('user_roles u')->select('r.*')->join('roles r','r.id=u.role_id','left')->where('u.user_id',$userId)->get()->getResultArray();
        return $rows;
    }

    public function rolePermissions(int $roleId): array
    {
        $perms = $this->db->table('role_permissions rp')
            ->select('p.slug')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('rp.role_id',$roleId)
            ->get()->getResultArray();
        return array_column($perms,'slug');
    }

    public function userAggregatedPermissions(int $userId): array
    {
        $perms = [];
        foreach ($this->userRoles($userId) as $role) {
            foreach ($this->rolePermissions($role['id']) as $p) { $perms[$p]=true; }
        }
        return array_keys($perms);
    }

    public function addPermission(int $roleId, string $permission): bool
    {
        // Find permission by slug and get its ID
        $perm = $this->db->table('permissions')->where('slug', $permission)->get()->getRowArray();
        if (!$perm) return false;
        return (bool)$this->db->table('role_permissions')->insert(['role_id'=>$roleId,'permission_id'=>$perm['id']]);
    }

    public function removePermission(int $roleId, string $permission): bool
    {
        // Find permission by slug and get its ID
        $perm = $this->db->table('permissions')->where('slug', $permission)->get()->getRowArray();
        if (!$perm) return false;
        return (bool)$this->db->table('role_permissions')->where(['role_id'=>$roleId,'permission_id'=>$perm['id']])->delete();
    }

    public function findRoleBySlug(string $slug): ?array
    {
        $role = $this->db->table('roles')->where('slug', $slug)->get()->getRowArray();
        if (!$role) return null;
        $perms = $this->db->table('role_permissions rp')
            ->select('p.slug')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('rp.role_id', $role['id'])
            ->get()->getResultArray();
        $role['permissions'] = array_column($perms, 'slug');
        return $role;
    }
}
