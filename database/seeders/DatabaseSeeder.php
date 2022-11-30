<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => 'xlnt83@gmail.com',
            'google_id' =>  '107431414027091197737',
            'lon' =>  '30.61',
            'lat' =>  '50.39',
        ]);
    }
}
