<?php

use Illuminate\Database\Seeder;
use App\Option;

class OptionsTableSeeder_1 extends Seeder
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
                'name' => 'prices',
                'value' => json_encode([
                    'individual'=>[50,150],
                    'group'=>[50,150],
                ]),
                'type' => 'json',
                'label' => 'teacher-prices',
            ]
        ]);
    }
}
