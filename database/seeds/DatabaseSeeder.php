<?php

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
        $this->call(PermissionTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(OptionsTableSeeder_1::class);
        $this->call(OptionsTableSeeder_2::class);
        $this->call(OptionsTableSeeder_3::class);
    }
}
