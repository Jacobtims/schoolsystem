<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('classrooms')->insert([
            [
                'id' => 1,
                'name' => 'S101'
            ],
            [
                'id' => 2,
                'name' => 'S202'
            ],
            [
                'id' => 3,
                'name' => 'S303'
            ]
        ]);
    }
}
