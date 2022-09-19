<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10000)->create()->each(function ($user) {
            Student::factory(1)->create([
                'user_id' => $user->id
            ]);
        });
    }
}
