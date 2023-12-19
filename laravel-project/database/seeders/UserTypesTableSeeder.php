<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
     * GENERAL:一般ユーザー
     * ADMIN:管理ユーザー
     */
        DB::table('user_types')->insert([
            ['name' => 'GENERAL'],
            ['name' => 'ADMIN'],
        ]);
    }
}
