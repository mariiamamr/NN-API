<?php

use Illuminate\Database\Seeder;

class OptionsTableSeeder_4 extends Seeder
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
                'name' => 'percent',
                'value' => "100",
                'type' => 'number',
                'label' => 'site_config',
            ]
        ]);
    }
}
