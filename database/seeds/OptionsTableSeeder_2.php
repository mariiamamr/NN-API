<?php

use Illuminate\Database\Seeder;
use App\Option;

class OptionsTableSeeder_2 extends Seeder
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
                'name' => 'phone',
                'value' => "",
                'type' => 'string',
                'label' => 'site_config',
            ],
            [
                'name' => 'info_email',
                'value' => "",
                'type' => 'string',
                'label' => 'site_config',
            ],
            [
                'name' => 'address',
                'value' => "",
                'type' => 'string',
                'label' => 'site_config',
            ],
            [
                'name' => 'social_urls',
                'value' => json_encode([]),
                'type' => 'json',
                'label' => 'site_config',
            ],
            [
                'name' => 'about_us',
                'value' => "",
                'type' => 'text',
                'label' => 'site_config',
            ],[
                'name' => 'session_duration',
                'value' => 60,
                'type' => 'number',
                'label' => 'site_config',
            ],
            [
                'name' => 'group_number',
                'value' => "",
                'type' => 'number',
                'label' => 'site_config',
            ],
        ]);
    }
}
