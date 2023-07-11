<?php

namespace Database\Seeders;

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
        \App\Models\User::firstOrCreate([
            'email' => 'admin@admin.com',
        ],[
            'name' => 'Administrator',
            'password' => bcrypt('password'),
            'active_status' => true,
        ]);

        $this->call(CategorySeeder::class);
    }
}
