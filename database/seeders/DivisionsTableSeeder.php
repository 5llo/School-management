<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()

   {

       $sections = [

           ['name' => 'الشعبة الأولى'],

           ['name' => 'الشعبة الثانية'],

           ['name' => 'الشعبة الثالثة'],

           ['name' => 'الشعبة الرابعة'],

           ['name' => 'الشعبة الخامسة'],

       ];


       DB::table('divisions')->insert($sections);


}
}
