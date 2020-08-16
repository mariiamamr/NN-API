<?php

use Illuminate\Database\Seeder;
use \App\Option;

class OptionsTableSeeder_3 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Option::insert([
            [
                'name' => 'terms_conditions',
                'value' => "",
                'type' => 'text',
                'label' => 'site_config',
            ]
        ]);
    }
}
