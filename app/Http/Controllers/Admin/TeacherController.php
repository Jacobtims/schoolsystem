<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TeachersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportTeachersRequest;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Imports\TeachersImport;
use App\Mail\NewTeacherCreated;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\User;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Intervention\Image\Facades\Image;
use Log;
use Mail;
use Redirect;
use Storage;
use Str;

class TeacherController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $request->validate([
            'field' => 'in:firstname,lastname,abbreviation,student_name,email,phone_number,sex',
            'direction' => 'in:asc,desc'
        ]);

        $teacherRole = Role::whereName('Teacher')->firstOrFail();
        $teachers = User::whereRoleId($teacherRole->id)
            ->join('teachers', 'users.id', '=', 'teachers.user_id')
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('firstname', 'LIKE', '%' . $request->get('search') . '%');
            })
            ->when($request->has(['field', 'direction']), function ($query) use ($request) {
                $query->orderBy($request->get('field'), $request->get('direction'));
            })
            ->select(['users.id', 'firstname', 'lastname', 'sex', 'email', 'phone_number', 'street', 'zipcode', 'city', 'state', 'country', 'date_of_birth', 'profile_photo', 'teachers.abbreviation as abbreviation', 'teachers.student_name as student_name'])
            ->paginate(10);

        return Inertia::render('Admin/Teachers/Overview', [
            'teachers' => $teachers,
            'filters' => $request->all(['search', 'field', 'direction'])
        ]);
    }

    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        $teacherRole = Role::whereName('Teacher')->firstOrFail();

        if ($request->get('generatePassword')) {
            $password = Str::random(8);
        } else {
            $password = $request->get('password');
        }
        $passwordHash = Hash::make($password);

        $request->merge(['password' => $passwordHash, 'role_id' => $teacherRole->id]);
        $user = User::create($request->only(['email', 'firstname', 'lastname', 'sex', 'phone_number', 'date_of_birth', 'country', 'state', 'city', 'zipcode', 'street', 'password', 'role_id']));
        Teacher::create(['user_id' => $user->id, 'abbreviation' => $request->get('abbreviation'), 'student_name' => $request->get('student_name')]);

        $this->updateProfilePhoto($user, $request->file('profile_photo'));

        if ($request->get('sendEmail')) {
            Mail::to($user->email)->send(new NewTeacherCreated($user->email, $password));
        }

        return Redirect::back();
    }

    public function update(UpdateTeacherRequest $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->fill($request->only(['email', 'firstname', 'lastname', 'sex', 'phone_number', 'date_of_birth', 'country', 'state', 'city', 'zipcode', 'street']));
        $user->save();

        $teacher = Teacher::findOrFail($user->teacher->id);
        $teacher->fill($request->only(['abbreviation', 'student_name']));
        $teacher->save();

        $this->updateProfilePhoto($user, $request->file('profile_photo'));

        return Redirect::back();
    }

    public function destroy(int $id): RedirectResponse
    {
        // Soft-delete user
        $user = User::findOrFail($id);
        $user->delete();

        // Soft-delete teacher
        $teacher = $user->teacher;
        $teacher->delete();

        return Redirect::back();
    }

    public function import(ImportTeachersRequest $request): RedirectResponse
    {
        // Start importing file
        $import = new TeachersImport($request->boolean('generatePassword'), $request->boolean('sendEmail'), $request->get('password'));
        $import->import($request->file('file'));

        // Log validation failures
        if ($import->failures()->count() > 0) {
            Log::error('A validation error occurred during teachers import. Failures: ' . $import->failures());
        }

        // Log database errors
        if ($import->errors()->count() > 0) {
            Log::error('A database error occurred during teachers import. Errors: ' . $import->errors());
        }

        return back()->with('data', [
            'teachers_count' => $import->amount,
            'errors' => ($import->failures()->count() + $import->errors()->count())
        ]);
    }

    public function export(Request $request)
    {
        return (new TeachersExport)->download('teachers.xlsx');
    }

    private function updateProfilePhoto(User $user, UploadedFile $file = null): void
    {
        if ($file !== null) {
            $image = Image::make($file)->resize(120, 120);
            $name = $user->id . '-' . now()->timestamp . '.' . $file->getClientOriginalExtension();

            Storage::disk('public')->put('/profiles/' . $name, (string)$image->encode());

            $user->update([
                'profile_photo' => $name
            ]);
        }
    }
}
