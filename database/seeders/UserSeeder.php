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
                'email' => 'admin@schoolsystem.nl',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('test123test'),
                'firstname' => 'Admin',
                'lastname' => 'Account',
                'phone_number' => '0612345678',
                'date_of_birth' => Carbon::now()->subYears(rand(1, 99)),
                'country' => 'Nederland',
                'state' => 'Zuid-Holland',
                'city' => 'Rotterdam',
                'zipcode' => '1234AB',
                'street' => 'Weetnietstraat',
                'role_id' => 1
            ],
            [
                'id' => 2,
                'email' => 'teacher@schoolsystem.nl',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('test123test'),
                'firstname' => 'Teacher',
                'lastname' => 'Account',
                'phone_number' => '0612345678',
                'date_of_birth' => Carbon::now()->subYears(rand(1, 99)),
                'country' => 'Nederland',
                'state' => 'Zuid-Holland',
                'city' => 'Rotterdam',
                'zipcode' => '1234AB',
                'street' => 'Weetnietstraat',
                'role_id' => 2
            ],
            [
                'id' => 3,
                'email' => 'student@schoolsystem.nl',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('test123test'),
                'firstname' => 'Student',
                'lastname' => 'Account',
                'phone_number' => '0612345678',
                'date_of_birth' => Carbon::now()->subYears(rand(1, 99)),
                'country' => 'Nederland',
                'state' => 'Zuid-Holland',
                'city' => 'Rotterdam',
                'zipcode' => '1234AB',
                'street' => 'Weetnietstraat',
                'role_id' => 3
            ]
        ]);

        // Seed teacher
        DB::table('teachers')->insert([
            [
                'id' => 1,
                'user_id' => 2,
                'abbreviation' => 'TEA',
                'student_name' => 'T. Account'
            ]
        ]);

        // Seed student
        DB::table('students')->insert([
            [
                'id' => 1,
                'user_id' => 3
            ]
        ]);
    }
}
