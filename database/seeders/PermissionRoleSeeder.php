<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission_role')->insert([
            [
                'role_id' => 1,
                'permission_id' => Permission::whereName('admin_dashboard')->firstOrFail()->id
            ],
            [
                'role_id' => 2,
                'permission_id' => Permission::whereName('teacher_dashboard')->firstOrFail()->id
            ],
            [
                'role_id' => 3,
                'permission_id' => Permission::whereName('student_dashboard')->firstOrFail()->id
            ],
        ]);
    }
}
