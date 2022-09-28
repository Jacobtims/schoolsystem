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
            TeacherSeeder::class,
            SchoolClassSeeder::class,
            PermissionSeeder::class,
            PermissionRoleSeeder::class,
            StandardLessonSeeder::class,
            SubjectSeeder::class,
            AbsenceTypeSeeder::class,
            ClassroomSeeder::class,
            StudentSeeder::class
        ]);
    }
}
