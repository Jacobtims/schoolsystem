<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teacherRole = Role::whereName('Teacher')->firstOrFail();

        User::factory(50)->create([
            'role_id' => $teacherRole->id
        ])->each(function ($user) {
            Teacher::factory(1)->create([
                'user_id' => $user->id
            ]);
        });
    }
}
