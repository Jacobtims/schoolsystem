<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attendance_types')->insert([
            [
                'id' => 1,
                'name' => "Afwezig"
            ],
            [
                'id' => 2,
                'name' => "Te laat"
            ],
            [
                'id' => 3,
                'name' => "Ziek"
            ],
        ]);
    }
}
