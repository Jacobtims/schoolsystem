<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbsenceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('absence_types')->insert([
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
