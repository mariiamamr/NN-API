<?php

use Illuminate\Database\Seeder;
use App\Admin;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Admin::insert([
            'name' => 'super_admin',
            'email' => 'admin@admin.com',
            'role_id' => 1,
            'password' => bcrypt('admin123'), // secret
            'remember_token' => str_random(10),
        ]);
    }
}
