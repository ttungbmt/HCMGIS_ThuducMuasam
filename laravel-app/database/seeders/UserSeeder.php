<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // php artisan db:seed --class=UserSeeder
        User::insert([
            [
                'name' => 'SuperAdmin',
                'email' => 'superadmin@superadmin.com',
                'password' => Hash::make('superadmin'),
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin'),
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@manager.com',
                'password' => Hash::make('manager'),
            ],
            [
                'name' => 'Editor',
                'email' => 'editor@editor.com',
                'password' => Hash::make('editor'),
            ],
            [
                'name' => 'Guest',
                'email' => 'guest@guest.com',
                'password' => Hash::make('guest'),
            ],
        ]);
    }
}
