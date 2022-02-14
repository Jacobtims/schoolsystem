<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subjects')->insert([
            [
                'id' => 1,
                'name' => 'Wiskunde',
                'abbreviation' => 'WIS'
            ],
            [
                'id' => 2,
                'name' => 'Nederlands',
                'abbreviation' => 'NED'
            ],
            [
                'id' => 3,
                'name' => 'Engels',
                'abbreviation' => 'ENG'
            ],
            [
                'id' => 4,
                'name' => 'Scheikunde',
                'abbreviation' => 'SCK'
            ],
            [
                'id' => 5,
                'name' => 'Duits',
                'abbreviation' => 'DUI'
            ],
        ]);
    }
}
