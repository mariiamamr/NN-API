<?php

use Illuminate\Database\Seeder;
use App\Language;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // factory(\App\Language::class, 2)->create();
        Language::insert([
            [
                'title' => 'English',
                'slug' => 'en',
            ], [
                'title' => 'Arabic',
                'slug' => 'ar',
            ]
        ]);
    }
}
