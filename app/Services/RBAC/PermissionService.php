<?php
namespace App\Services\RBAC;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\ConnectionInterface;
use Config\Database;

class PermissionService
{
    private ConnectionInterface|BaseConnection $db;
    private array $cacheUserPerms = [];
    private ?array $cacheAllPerms = null;

    public function __construct(?ConnectionInterface $db = null)
    {
        $this->db = $db ?? Database::connect();
    }

    public function clearCache(int $userId = null): void
    {
        if ($userId === null) { $this->cacheUserPerms = []; return; }
        unset($this->cacheUserPerms[$userId]);
    }

    public function clearAllPermissionsCache(): void
    {
        $this->cacheAllPerms = null;
    }

    public function userPermissions(int $userId): array
    {
        if (isset($this->cacheUserPerms[$userId])) return $this->cacheUserPerms[$userId];
        $sql = 'SELECT DISTINCT p.slug FROM permissions p
                JOIN role_permissions rp ON rp.permission_id = p.id
                JOIN user_roles ur ON ur.role_id = rp.role_id
                WHERE ur.user_id = ?';
        $rows = $this->db->query($sql, [$userId])->getResultArray();
        $perms = array_map(fn($r)=> $r['slug'], $rows);
        sort($perms);
        return $this->cacheUserPerms[$userId] = $perms;
    }

    public function userHas(int $userId, string $permission): bool
    {
        return in_array($permission, $this->userPermissions($userId), true);
    }

    public function assignRole(int $userId, int $roleId): void
    {
        $this->db->table('user_roles')->ignore(true)->insert([
            'user_id'=>$userId,
            'role_id'=>$roleId,
        ]);
        $this->clearCache($userId);
    }

    public function createRole(string $slug, string $name, ?string $description = null): int
    {
        $this->db->table('roles')->insert([
            'slug'=>$slug,
            'name'=>$name,
            'description'=>$description,
            'created_at'=>date('Y-m-d H:i:s'),
        ]);
        return (int)$this->db->insertID();
    }

    public function createPermission(string $slug, string $name, ?string $group = null): int
    {
        $this->db->table('permissions')->insert([
            'slug'=>$slug,
            'name'=>$name,
            'group'=>$group,
            'created_at'=>date('Y-m-d H:i:s'),
        ]);
        $this->clearAllPermissionsCache();
        return (int)$this->db->insertID();
    }

    public function attachPermissionToRole(int $roleId, int $permissionId): void
    {
        $this->db->table('role_permissions')->ignore(true)->insert([
            'role_id'=>$roleId,
            'permission_id'=>$permissionId,
        ]);
        $this->cacheUserPerms = []; // invalidate all user caches
    }

    public function allPermissions(): array
    {
        if ($this->cacheAllPerms !== null) return $this->cacheAllPerms;
        $rows = $this->db->table('permissions')->select('slug')->get()->getResultArray();
        return $this->cacheAllPerms = array_map(fn($r)=>$r['slug'],$rows);
    }

    public function findRoleBySlug(string $slug): ?array
    {
        $row = $this->db->table('roles')->where('slug',$slug)->get()->getRowArray();
        return $row ?: null;
    }

    public function findPermissionBySlug(string $slug): ?array
    {
        $row = $this->db->table('permissions')->where('slug',$slug)->get()->getRowArray();
        return $row ?: null;
    }
}
