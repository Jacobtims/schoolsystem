<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Admin Account',
                'email' => 'admin@schoolsystem.nl',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('test123test')
            ],
            [
                'id' => 2,
                'name' => 'Teacher Account',
                'email' => 'teacher@schoolsystem.nl',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('test123test')
            ],
            [
                'id' => 3,
                'name' => 'Student Account',
                'email' => 'student@schoolsystem.nl',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('test123test')
            ]
        ]);
    }
}
