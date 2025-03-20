<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SchoolsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()

    {

        $schools = [

            [

                'name' => 'مدرسة الأمل',

                'description' => 'مدرسة تقدم تعليماً متميزاً للطلاب.',

                'bus_price' => 150.00,

                'email' => 'info@al-amal.com',

                'password' => Hash::make('password123'), // Hash the password

                'latitude' => 30.0444, // Latitude

                'longitude' => 31.2357, // Longitude

            ],

            [

                'name' => 'مدرسة النور',

                'description' => 'مدرسة متخصصة في التعليم الابتدائي.',

                'bus_price' => 100.00,

                'email' => 'info@al-noor.com',

                'password' => Hash::make('password123'), // Hash the password

                'latitude' => 31.2001, // Latitude

                'longitude' => 29.9187, // Longitude

            ],

            [

                'name' => 'مدرسة المستقبل',

                'description' => 'مدرسة تقدم برامج تعليمية متطورة.',

                'bus_price' => 200.00,

                'email' => 'info@al-mustaqbal.com',

                'password' => Hash::make('password123'), // Hash the password

                'latitude' => 29.9853, // Latitude

                'longitude' => 31.2543, // Longitude

            ],

        ];


        DB::table('schools')->insert($schools);

    }
}
