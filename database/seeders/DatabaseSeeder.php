<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
            PermissionRoleSeeder::class,
            StandardLessonSeeder::class,
            SubjectSeeder::class,
            AttendanceTypeSeeder::class,
            ClassroomSeeder::class
        ]);

        User::factory()
            ->has(Student::factory()->count(1))
            ->count(100)
            ->create();
    }
}
