<?php
namespace App\Controllers\Admin;

use CodeIgniter\HTTP\ResponseInterface;

class RolesController extends AdminBaseController
{
    protected ?string $requiredPermission = 'admin.roles.view';

    public function index()
    {
        $db = \Config\Database::connect();
        
        // Get roles with additional data
        $roles = $db->table('roles')
            ->select('roles.*, 
                     (SELECT COUNT(*) FROM user_roles WHERE role_id = roles.id) as user_count,
                     (SELECT COUNT(*) FROM role_permissions WHERE role_id = roles.id) as permission_count')
            ->orderBy('roles.id', 'asc')
            ->get()
            ->getResultArray();

        // Get statistics
        $totalPermissions = $db->table('permissions')->countAllResults();
        $usersWithRoles = $db->table('user_roles')->distinct()->countAllResults('user_id');
        $allPermissions = $db->table('permissions')->select('id, slug, name, `group`')->get()->getResultArray();

        return $this->render('rbac/roles', [
            'title' => 'Roles Management',
            'roles' => $roles,
            'totalPermissions' => $totalPermissions,
            'usersWithRoles' => $usersWithRoles,
            'allPermissions' => $allPermissions
        ]);
    }

    public function create()
    {
        $guardResult = $this->guard('admin.roles.manage');
        if ($guardResult !== null) {
            return $guardResult;
        }

        $name = $this->request->getPost('name');
        $slug = $this->request->getPost('slug');
        $description = $this->request->getPost('description');

        if (empty($name) || empty($slug)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Name and slug are required']);
        }

        $db = \Config\Database::connect();
        
        // Check if slug already exists
        $existingRole = $db->table('roles')->where('slug', $slug)->get()->getRowArray();
        if ($existingRole) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Role slug already exists']);
        }

        $roleId = $db->table('roles')->insert([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        if ($roleId) {
            audit_event('role.create', ['role_id' => $roleId, 'name' => $name, 'slug' => $slug]);
            return $this->response->setJSON(['success' => true, 'role_id' => $roleId]);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to create role']);
        }
    }

    public function show($id)
    {
        $db = \Config\Database::connect();
        
        $role = $db->table('roles')->where('id', $id)->get()->getRowArray();
        if (!$role) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Role not found']);
        }

        // Get role permissions
        $permissions = $db->table('role_permissions rp')
            ->select('p.id, p.slug, p.name, p.group')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('rp.role_id', $id)
            ->get()
            ->getResultArray();

        // Get users with this role
        $users = $db->table('user_roles ur')
            ->select('u.id, u.username, u.email, u.created_at')
            ->join('users u', 'u.id = ur.user_id')
            ->where('ur.role_id', $id)
            ->get()
            ->getResultArray();

        return $this->render('rbac/role_detail', [
            'title' => 'Role Details: ' . $role['name'],
            'role' => $role,
            'permissions' => $permissions,
            'users' => $users
        ]);
    }

    public function edit($id)
    {
        $guardResult = $this->guard('admin.roles.manage');
        if ($guardResult !== null) {
            return $guardResult;
        }

        $db = \Config\Database::connect();
        
        $role = $db->table('roles')->where('id', $id)->get()->getRowArray();
        if (!$role) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Role not found']);
        }

        // Handle JSON request for status toggle
        if ($this->request->getHeaderLine('Content-Type') === 'application/json') {
            $jsonData = $this->request->getJSON(true);
            
            // Handle status toggle
            if (isset($jsonData['status'])) {
                $status = $jsonData['status'];
                if (!in_array($status, ['active', 'inactive'])) {
                    return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid status']);
                }

                $updated = $db->table('roles')->where('id', $id)->update([
                    'status' => $status,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                if ($updated) {
                    audit_event('role.status_update', ['role_id' => $id, 'status' => $status]);
                    return $this->response->setJSON(['success' => true]);
                } else {
                    return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update role status']);
                }
            }
        }

        // Handle form data for regular edit
        $name = $this->request->getPost('name');
        $slug = $this->request->getPost('slug');
        $description = $this->request->getPost('description');

        if (empty($name) || empty($slug)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Name and slug are required']);
        }

        // Check if slug already exists (excluding current role)
        $existingRole = $db->table('roles')->where('slug', $slug)->where('id !=', $id)->get()->getRowArray();
        if ($existingRole) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Role slug already exists']);
        }

        $updated = $db->table('roles')->where('id', $id)->update([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($updated) {
            audit_event('role.update', ['role_id' => $id, 'name' => $name, 'slug' => $slug]);
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update role']);
        }
    }

    public function delete($id)
    {
        $guardResult = $this->guard('admin.roles.manage');
        if ($guardResult !== null) {
            return $guardResult;
        }

        $db = \Config\Database::connect();
        
        $role = $db->table('roles')->where('id', $id)->get()->getRowArray();
        if (!$role) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Role not found']);
        }

        // Check if role is assigned to any users
        $userCount = $db->table('user_roles')->where('role_id', $id)->countAllResults();
        if ($userCount > 0) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Cannot delete role that is assigned to users']);
        }

        // Delete role permissions first
        $db->table('role_permissions')->where('role_id', $id)->delete();
        
        // Delete the role
        $deleted = $db->table('roles')->where('id', $id)->delete();

        if ($deleted) {
            audit_event('role.delete', ['role_id' => $id, 'name' => $role['name']]);
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete role']);
        }
    }

    public function permissions($roleId)
    {
        $guardResult = $this->guard('admin.roles.manage');
        if ($guardResult !== null) {
            return $guardResult;
        }
        
        $db = \Config\Database::connect();
        
        $role = $db->table('roles')->where('id', $roleId)->get()->getRowArray();
        if (!$role) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Role not found']);
        }

        // Get all permissions grouped by category
        $allPermissions = $db->table('permissions')
            ->select('id, slug, name, `group`')
            ->orderBy('`group`', 'asc')
            ->orderBy('name', 'asc')
            ->get()
            ->getResultArray();

        // Get current role permissions
        $rolePermissions = $db->table('role_permissions')
            ->where('role_id', $roleId)
            ->get()
            ->getResultArray();
        $rolePermissionIds = array_column($rolePermissions, 'permission_id');

        // Group permissions by category
        $groupedPermissions = [];
        foreach ($allPermissions as $permission) {
            $group = $permission['group'] ?? 'General';
            if (!isset($groupedPermissions[$group])) {
                $groupedPermissions[$group] = [];
            }
            $groupedPermissions[$group][] = $permission;
        }

        // Check if this is an AJAX request
        if ($this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
            return $this->response->setJSON([
                'role' => $role,
                'groupedPermissions' => $groupedPermissions,
                'rolePermissionIds' => $rolePermissionIds
            ]);
        }

        return $this->render('rbac/role_permissions', [
            'title' => 'Role Permissions: ' . $role['name'],
            'role' => $role,
            'allPermissions' => $allPermissions,
            'groupedPermissions' => $groupedPermissions,
            'rolePermissionIds' => $rolePermissionIds
        ]);
    }

    public function updatePermissions($roleId)
    {
        $guardResult = $this->guard('admin.roles.manage');
        if ($guardResult !== null) {
            return $guardResult;
        }

        $db = \Config\Database::connect();
        
        $role = $db->table('roles')->where('id', $roleId)->get()->getRowArray();
        if (!$role) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Role not found']);
        }

        $permissions = $this->request->getPost('permissions');
        if (!is_array($permissions)) {
            $permissions = [];
        }

        // Remove all current permissions
        $db->table('role_permissions')->where('role_id', $roleId)->delete();

        // Add new permissions
        if (!empty($permissions)) {
            $permissionData = [];
            foreach ($permissions as $permissionId) {
                $permissionData[] = [
                    'role_id' => $roleId,
                    'permission_id' => $permissionId
                ];
            }
            $db->table('role_permissions')->insertBatch($permissionData);
        }

        audit_event('role.permissions.update', ['role_id' => $roleId, 'permissions_count' => count($permissions)]);
        return $this->response->setJSON(['success' => true]);
    }

    public function users($roleId)
    {
        // Add guard check for consistency
        $guardResult = $this->guard('admin.roles.view');
        if ($guardResult !== null) {
            return $guardResult;
        }

        $db = \Config\Database::connect();
        
        $role = $db->table('roles')->where('id', $roleId)->get()->getRowArray();
        if (!$role) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Role not found']);
        }

        // Get users with this role - fix the query to handle missing columns
        $users = $db->table('user_roles ur')
            ->select('u.id, u.username, u.email, u.created_at')
            ->join('users u', 'u.id = ur.user_id')
            ->where('ur.role_id', $roleId)
            ->orderBy('u.username', 'asc')
            ->get()
            ->getResultArray();

        // Add user count to role data
        $role['user_count'] = count($users);

        // Check if this is an AJAX request
        if ($this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
            return $this->response->setJSON([
                'role' => $role,
                'users' => $users
            ]);
        }

        return $this->render('rbac/role_users', [
            'title' => 'Role Users: ' . $role['name'],
            'role' => $role,
            'users' => $users
        ]);
    }

    public function assignUser($roleId)
    {
        $guardResult = $this->guard('admin.roles.manage');
        if ($guardResult !== null) {
            return $guardResult;
        }

        $userId = $this->request->getPost('user_id');
        if (!$userId) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'User ID is required']);
        }

        $db = \Config\Database::connect();
        
        // Check if role exists
        $role = $db->table('roles')->where('id', $roleId)->get()->getRowArray();
        if (!$role) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Role not found']);
        }

        // Check if user exists
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'User not found']);
        }

        // Check if already assigned
        $existing = $db->table('user_roles')->where('role_id', $roleId)->where('user_id', $userId)->get()->getRowArray();
        if ($existing) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'User already has this role']);
        }

        $assigned = $db->table('user_roles')->insert([
            'role_id' => $roleId,
            'user_id' => $userId
        ]);

        if ($assigned) {
            audit_event('role.user.assign', ['role_id' => $roleId, 'user_id' => $userId]);
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to assign role to user']);
        }
    }

    public function removeUser($roleId, $userId)
    {
        $guardResult = $this->guard('admin.roles.manage');
        if ($guardResult !== null) {
            return $guardResult;
        }

        $db = \Config\Database::connect();
        
        $removed = $db->table('user_roles')->where('role_id', $roleId)->where('user_id', $userId)->delete();

        if ($removed) {
            audit_event('role.user.remove', ['role_id' => $roleId, 'user_id' => $userId]);
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to remove role from user']);
        }
    }

    public function getRoleData($id)
    {
        $db = \Config\Database::connect();
        
        $role = $db->table('roles')->where('id', $id)->get()->getRowArray();
        if (!$role) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Role not found']);
        }

        return $this->response->setJSON($role);
    }

    public function searchUsers()
    {
        $search = $this->request->getGet('search');
        $roleId = $this->request->getGet('role_id');

        $db = \Config\Database::connect();
        
        $query = $db->table('users u')
            ->select('u.id, u.username, u.email')
            ->where('(u.username LIKE ? OR u.email LIKE ?)', ["%$search%", "%$search%"]);

        // Exclude users who already have this role
        if ($roleId) {
            $query->where('u.id NOT IN (SELECT user_id FROM user_roles WHERE role_id = ?)', [$roleId]);
        }

        $users = $query->limit(10)->get()->getResultArray();

        return $this->response->setJSON($users);
    }

    public function exportUsers($roleId)
    {
        $guardResult = $this->guard('admin.roles.view');
        if ($guardResult !== null) {
            return $guardResult;
        }

        $db = \Config\Database::connect();
        
        $role = $db->table('roles')->where('id', $roleId)->get()->getRowArray();
        if (!$role) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Role not found']);
        }

        // Get users with this role
        $users = $db->table('user_roles ur')
            ->select('u.id, u.username, u.email, u.created_at')
            ->join('users u', 'u.id = ur.user_id')
            ->where('ur.role_id', $roleId)
            ->get()
            ->getResultArray();

        // Create CSV content
        $csv = "ID,Username,Email,Created At\n";
        foreach ($users as $user) {
            $csv .= "{$user['id']},{$user['username']},{$user['email']},{$user['created_at']}\n";
        }

        // Set headers for download
        $filename = "role_{$role['slug']}_users_" . date('Y-m-d_H-i-s') . ".csv";
        
        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', "attachment; filename=\"$filename\"")
            ->setBody($csv);
    }

    public function importUsers($roleId)
    {
        $guardResult = $this->guard('admin.roles.manage');
        if ($guardResult !== null) {
            return $guardResult;
        }

        $db = \Config\Database::connect();
        
        $role = $db->table('roles')->where('id', $roleId)->get()->getRowArray();
        if (!$role) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Role not found']);
        }

        $userIds = $this->request->getPost('user_ids');
        if (!is_array($userIds) || empty($userIds)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'No users selected']);
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($userIds as $userId) {
            // Check if user exists
            $user = $db->table('users')->where('id', $userId)->get()->getRowArray();
            if (!$user) {
                $errorCount++;
                continue;
            }

            // Check if already assigned
            $existing = $db->table('user_roles')->where('role_id', $roleId)->where('user_id', $userId)->get()->getRowArray();
            if ($existing) {
                $errorCount++;
                continue;
            }

            // Assign role
            $assigned = $db->table('user_roles')->insert([
                'role_id' => $roleId,
                'user_id' => $userId
            ]);

            if ($assigned) {
                $successCount++;
                audit_event('role.user.assign', ['role_id' => $roleId, 'user_id' => $userId]);
            } else {
                $errorCount++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Successfully assigned role to $successCount users. Failed: $errorCount"
        ]);
    }

    public function bulkRemoveUsers($roleId)
    {
        $guardResult = $this->guard('admin.roles.manage');
        if ($guardResult !== null) {
            return $guardResult;
        }

        $db = \Config\Database::connect();
        
        $role = $db->table('roles')->where('id', $roleId)->get()->getRowArray();
        if (!$role) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Role not found']);
        }

        $userIds = $this->request->getPost('user_ids');
        if (!is_array($userIds) || empty($userIds)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'No users selected']);
        }

        $removed = $db->table('user_roles')
            ->where('role_id', $roleId)
            ->whereIn('user_id', $userIds)
            ->delete();

        if ($removed) {
            audit_event('role.user.bulk_remove', ['role_id' => $roleId, 'user_count' => count($userIds)]);
            return $this->response->setJSON(['success' => true, 'removed_count' => $removed]);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to remove users from role']);
        }
    }
    
    public function testAuth()
    {
        $auth = service('auth');
        $user = $auth->user();
        
        $response = [
            'auth_service' => $auth ? 'created' : 'failed',
            'current_user' => $user ? $user['username'] : 'no user logged in',
            'user_id' => $user ? $user['id'] : null,
            'session_id' => session_id(),
            'csrf_token' => csrf_hash()
        ];
        
        if ($user) {
            $permissions = service('permissions');
            $response['has_admin_roles_view'] = $permissions->userHas($user['id'], 'admin.roles.view');
            $response['has_admin_roles_manage'] = $permissions->userHas($user['id'], 'admin.roles.manage');
        }
        
        return $this->response->setJSON($response);
    }
    
    public function testSimple()
    {
        $auth = service('auth');
        $user = $auth->user();
        
        return $this->response->setJSON([
            'message' => 'Simple test route',
            'user_logged_in' => $user ? true : false,
            'username' => $user ? $user['username'] : null,
            'user_id' => $user ? $user['id'] : null,
            'session_id' => session_id()
        ]);
    }
}
