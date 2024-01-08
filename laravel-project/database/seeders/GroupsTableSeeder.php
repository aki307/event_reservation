<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
     * HUMAN_RESOURCES:人事部
     * GENERAL_AFFAIRS：総務部
     * SALES:営業部
     * ENGINEERING:技術部
     */
        DB::table('groups')->insert([
            ['name' => 'HUMAN_RESOURCES'],
            ['name' => 'GENERAL_AFFAIRS'],
            ['name' => 'SALES'],
            ['name' => 'ENGINEERING'],
            ['name' => 'ALL'],
        ]);
    }
}
