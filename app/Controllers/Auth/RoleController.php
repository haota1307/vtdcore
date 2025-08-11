<?php
namespace App\Controllers\Auth;

use CodeIgniter\RESTful\ResourceController;

class RoleController extends ResourceController
{
    protected $format = 'json';

    public function create()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        if (empty($data['name'])) return $this->failValidationErrors('name');
        $perms = $data['permissions'] ?? [];
        $role = service('roles')->create($data['name'], $perms);
        return $this->respondCreated(['role'=>$role]);
    }

    public function userRoles($userId)
    {
        $roles = service('roles')->userRoles((int)$userId);
        return $this->respond(['roles'=>$roles]);
    }

    public function attach($userId, $roleId)
    {
        service('roles')->attachRoleToUser((int)$userId,(int)$roleId);
        return $this->respond(['attached'=>true]);
    }

    public function addPermission($roleId)
    {
        $perm = $this->request->getPost('permission');
        if (!$perm) return $this->failValidationErrors('permission');
        service('roles')->addPermission((int)$roleId, $perm);
        audit_event('role.permission.add',[ 'role_id'=>(int)$roleId,'permission'=>$perm ]);
        return $this->respond(['added'=>true]);
    }

    public function removePermission($roleId)
    {
        $perm = $this->request->getPost('permission');
        if (!$perm) return $this->failValidationErrors('permission');
        service('roles')->removePermission((int)$roleId, $perm);
        audit_event('role.permission.remove',[ 'role_id'=>(int)$roleId,'permission'=>$perm ]);
        return $this->respond(['removed'=>true]);
    }
}
