<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // php artisan db:seed --class=RolesAndPermissionsSeeder
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->truncateTable(['role_has_permissions', 'permissions', 'roles',]);

        $roles = collect([
            'superadmin',
            'admin',
            'manager',
            'editor',
            'guest',
        ]);

        $roles->each(fn($name) => Role::create(['name' => $name]));

        $permissions = collect([
            'users',
            'roles',
            'permissions',
        ]);

        $actions = collect(['create', 'update', 'view', 'view-any', 'delete', 'restore', 'force-delete', 'import', 'export']);

        // log
        // command-runner
        // route-viewer
        // filemanager.create_folder

        /* ADMIN -------------------------------------------------------- */
        $adminPerms = $permissions
            ->merge($permissions->map(fn($n) => $actions->map(fn($a) => "{$n}.{$a}"))->collapse())
            ->merge([
                'users.sync-roles',
                'users.assign-roles',
                'users.remove-roles',
                'users.sync-permissions',
                'users.assign-permissions',
                'users.remove-permissions',
                'users.change-status',
                'users.impersonate',
                'file-manager',
                'menu-builder',
                'settings',
                'backup',
            ]);

        $this->roleHasPermission('admin', $adminPerms);

        /* MANAGER -------------------------------------------------------- */

        /* EDITOR -------------------------------------------------------- */

        /* GUEST -------------------------------------------------------- */
        $guestPerms = collect([
            'users.reset-password'
        ]);
        $this->roleHasPermission('guest', $guestPerms);
        /* COMMON -------------------------------------------------------- */

        $user = User::whereEmail('admin@admin.com')->first();
        $user->assignRole('admin');
    }

    public function roleHasPermission($role, $permissions){
        $role = is_string($role) ? Role::findByName($role) : $role;

        $permissions->each(function ($name) use($role){
            $permission = Permission::create(['name' => $name]);
            $role->givePermissionTo($permission);
        });
    }

    public function truncateTable($table, $restartIdentity = true){
        $tables = collect(is_array($table) ? $table : [$table]);
        $tables->each(fn($tb) => DB::statement("TRUNCATE TABLE {$tb}".($restartIdentity ? ' RESTART IDENTITY CASCADE' : '')));
    }
}
