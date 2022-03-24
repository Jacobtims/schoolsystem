<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $counts = [
            "students" => Student::count(),
            "teachers" => Teacher::count()
        ];

        return Inertia::render('Admin/Dashboard', [
            "counts" => $counts
        ]);
    }
}
