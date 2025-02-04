<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

/**
 * @tags Role & Permission Management
 */

/**
 * @group Role & Permission Management
 *
 * API endpoints for managing roles and permissions using Spatie Laravel Permissions.
 */
class RolePermissionController extends Controller
{
    /**
     * Create a new role
     *
     * @bodyParam name string required The name of the role. Example: admin
     *
     * @response 201 {
     *    "message": "Role created",
     *    "role": {
     *        "id": 1,
     *        "name": "admin",
     *        "guard_name": "web",
     *        "created_at": "2024-02-04T12:00:00.000000Z",
     *        "updated_at": "2024-02-04T12:00:00.000000Z"
     *    }
     * }
     */
    public function createRole(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles'
        ]);

        $role = Role::create(['name' => $request->name]);

        return response()->json(['message' => 'Role created', 'role' => $role], 201);
    }

    /**
     * Get all roles
     *
     * @response 200 [
     *    {"id": 1, "name": "admin", "guard_name": "web"},
     *    {"id": 2, "name": "editor", "guard_name": "web"}
     * ]
     */
    public function getRoles()
    {
        return response()->json(Role::all());
    }

    /**
     * Create a new permission
     *
     * @bodyParam name string required The name of the permission. Example: edit articles
     *
     * @response 201 {
     *    "message": "Permission created",
     *    "permission": {
     *        "id": 1,
     *        "name": "edit articles",
     *        "guard_name": "web",
     *        "created_at": "2024-02-04T12:00:00.000000Z",
     *        "updated_at": "2024-02-04T12:00:00.000000Z"
     *    }
     * }
     */
    public function createPermission(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions'
        ]);

        $permission = Permission::create(['name' => $request->name]);

        return response()->json(['message' => 'Permission created', 'permission' => $permission], 201);
    }

    /**
     * Get all permissions
     *
     * @response 200 [
     *    {"id": 1, "name": "edit articles", "guard_name": "web"},
     *    {"id": 2, "name": "delete articles", "guard_name": "web"}
     * ]
     */
    public function getPermissions()
    {
        return response()->json(Permission::all());
    }

    /**
     * Assign a permission to a role
     *
     * @bodyParam role_name string required The name of the role. Example: admin
     * @bodyParam permission_name string required The name of the permission. Example: edit articles
     *
     * @response 200 {
     *    "message": "Permission assigned to role"
     * }
     */
    public function assignPermissionToRole(Request $request)
    {
        $request->validate([
            'role_name' => 'required|exists:roles,name',
            'permission_name' => 'required|exists:permissions,name'
        ]);

        $role = Role::findByName($request->role_name);
        $role->givePermissionTo($request->permission_name);

        return response()->json(['message' => 'Permission assigned to role']);
    }

    /**
     * Assign a role to a user
     *
     * @bodyParam user_id int required The ID of the user. Example: 1
     * @bodyParam role_name string required The name of the role. Example: admin
     *
     * @response 200 {
     *    "message": "Role assigned to user"
     * }
     */
    public function assignRoleToUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_name' => 'required|exists:roles,name'
        ]);

        $user = User::findOrFail($request->user_id);
        $user->assignRole($request->role_name);

        return response()->json(['message' => 'Role assigned to user']);
    }

    /**
     * Get a user's roles
     *
     * @urlParam user_id int required The ID of the user. Example: 1
     *
     * @response 200 {
     *    "roles": ["admin", "editor"]
     * }
     */
    public function getUserRoles($user_id)
    {
        $user = User::findOrFail($user_id);
        return response()->json(['roles' => $user->getRoleNames()]);
    }

    /**
     * Assign a permission to a user
     *
     * @bodyParam user_id int required The ID of the user. Example: 1
     * @bodyParam permission_name string required The name of the permission. Example: edit articles
     *
     * @response 200 {
     *    "message": "Permission assigned to user"
     * }
     */
    public function assignPermissionToUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission_name' => 'required|exists:permissions,name'
        ]);

        $user = User::findOrFail($request->user_id);
        $user->givePermissionTo($request->permission_name);

        return response()->json(['message' => 'Permission assigned to user']);
    }

    /**
     * Get a user's permissions
     *
     * @urlParam user_id int required The ID of the user. Example: 1
     *
     * @response 200 {
     *    "permissions": ["edit articles", "delete articles"]
     * }
     */
    public function getUserPermissions($user_id)
    {
        $user = User::findOrFail($user_id);
        return response()->json(['permissions' => $user->getPermissionNames()]);
    }
}
