<?php

namespace Database\Seeders;

use Backpack\PermissionManager\app\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //default admin
        $superAdminRole = Role::where('name', 'SuperAdmin')->first();
        if (!$superAdminRole){
            $superAdminRole = Role::create(['name' => 'SuperAdmin']);
        }

        $userRole = Role::where('name', 'User')->first();
        if (!$userRole){
            $userRole = Role::create(['name' => 'User']);
        }

        $admin = \App\Models\User::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Administrator',
            'password' => bcrypt('password'),
            'active_status' => true,
            'money' => 9999999
        ]);

        $user = \App\Models\User::firstOrCreate([
            'email' => 'usertest@gmail.com',
        ], [
            'name' => 'Hoang Nhat Minh',
            'password' => bcrypt('password'),
            'active_status' => true,
            'money' => 9999999
        ]);

        $admin->assignRole($superAdminRole);
        $user->assignRole($userRole);


        $this->call(CategorySeeder::class);
    }
}
