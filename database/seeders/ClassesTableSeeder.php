<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {

            $classes = [

                ['name' => 'الصف الأول'],

                ['name' => 'الصف الثاني'],

                ['name' => 'الصف الثالث'],

                ['name' => 'الصف الرابع'],

                ['name' => 'الصف الخامس'],

                ['name' => 'الصف السادس'],

            ];


            DB::table('classes')->insert($classes);

        }
    }
}
