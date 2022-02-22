<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StandardLessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('standard_lessons')->insert([
            [
                'id' => 1,
                'from' => '08:00:00',
                'to' => '08:55:00'
            ],
            [
                'id' => 2,
                'from' => '09:00:00',
                'to' => '09:55:00'
            ],
            [
                'id' => 3,
                'from' => '10:00:00',
                'to' => '10:55:00'
            ],
            [
                'id' => 4,
                'from' => '11:00:00',
                'to' => '11:55:00'
            ],
            [
                'id' => 5,
                'from' => '12:00:00',
                'to' => '12:55:00'
            ],
            [
                'id' => 6,
                'from' => '13:00:00',
                'to' => '13:55:00'
            ],
            [
                'id' => 7,
                'from' => '14:00:00',
                'to' => '14:55:00'
            ],
            [
                'id' => 8,
                'from' => '15:00:00',
                'to' => '15:55:00'
            ],
        ]);
    }
}
