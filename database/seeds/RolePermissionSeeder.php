<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::all();
        $admin = Role::whereName('Admin')->first();

        foreach ($permissions as $permission) {
            $this->setPermission($admin->id, $permission->id);
        }

        $editor = Role::whereName('Editor')->first();

        foreach ($permissions as $permission) {
            if (!in_array($permission->name, ['edit_roles'])) {
                $this->setPermission($editor->id, $permission->id);
            }
        }

        $viewer = Role::whereName('Viewer')->first();

        $viewerRoles = [
            'view_users',
            'view_roles',
            'view_products',
            'view_orders',
        ];

        foreach ($permissions as $permission) {
            if (!in_array($permission->name, $viewerRoles)) {
                $this->setPermission($viewer->id, $permission->id);
            }
        }
    }

    private function setPermission($roleId, $permissionId)
    {
        DB::table('role_permission')->insert([
            'role_id' => $roleId,
            'permission_id' => $permissionId,
        ]);
    }
}
