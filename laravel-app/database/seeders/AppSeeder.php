<?php

namespace Database\Seeders;

use App\Models\Cabenh;
use App\Models\CabenhCn;
use App\Models\CachlyPg;
use App\Models\Diadiem;
use App\Models\HcPhuong;
use App\Models\HcQuan;
use App\Models\HcTp;
use App\Models\PhongtoaPt;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // php artisan db:seed --class=AppSeeder
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $tables = collect([
            Cabenh::class,
            CabenhCn::class,
            PhongtoaPt::class,
            CachlyPg::class,
            Diadiem::class,
            Tag::class,

            HcQuan::class,
            HcPhuong::class,
            HcTp::class,
        ])->map(fn($c) => (new $c)->getTable());

        $permName = fn ($actions, $prepend = false) => fn($tb) => collect(array_merge([$prepend ? $tb : null], $actions->map(fn($a) => "{$tb}.{$a}")->all()))->filter()->all();

        $editorActs = collect(['create', 'update', 'view', 'view-any', 'delete', 'restore', 'force-delete', 'import', 'export']);
        $managerActs = collect(['view', 'view-any', 'export']);
        $guestActs = collect(['view', 'view-any']);

        $acts = collect([$editorActs, $managerActs, $guestActs])->collapse()->unique();
        $perms = $tables->map($permName($acts))->collapse();
//        $perms->each(fn($p) => Permission::create(['name' => $p]));

        /* GUEST -------------------------------------------------------- */
//        $guestRole = Role::findByName('guest');
//        $tables->map($permName($guestActs))->collapse()->each(fn($name) => Permission::findOrCreate($name)->assignRole($guestRole));

        /* MANAGER -------------------------------------------------------- */
//        $managerRole = Role::findByName('manager');
//        $tables->map($permName($managerActs))->collapse()->each(fn($name) => Permission::findOrCreate($name)->assignRole($managerRole));

        /* EDITOR -------------------------------------------------------- */
//        $editorRole = Role::findByName('editor');
//        $tables->map($permName($editorActs))->collapse()->each(fn($name) => Permission::findOrCreate($name)->assignRole($editorRole));
    }
}
