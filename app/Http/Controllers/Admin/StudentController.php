<?php

namespace App\Http\Controllers\Admin;

use App\Exports\StudentsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportStudentsRequest;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Imports\StudentsImport;
use App\Mail\NewStudentCreated;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Intervention\Image\Facades\Image;
use Log;
use Mail;
use Redirect;
use Storage;
use Str;

class StudentController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $request->validate([
            'field' => 'in:firstname,lastname,student_id,email,phone_number,sex',
            'direction' => 'in:asc,desc'
        ]);

        $studentRole = Role::whereName('Student')->firstOrFail();
        $students = User::whereRoleId($studentRole->id)
            ->join('students', 'users.id', '=', 'students.user_id')
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('firstname', 'LIKE', '%' . $request->get('search') . '%');
            })
            ->when($request->has(['field', 'direction']), function ($query) use ($request) {
                $query->orderBy($request->get('field'), $request->get('direction'));
            })
            ->select(['users.id', 'students.id as student_id', 'firstname', 'lastname', 'sex', 'email', 'phone_number', 'street', 'zipcode', 'city', 'state', 'country', 'date_of_birth', 'profile_photo'])
            ->paginate(10);

        return Inertia::render('Admin/Students/Overview', [
            'students' => $students,
            'filters' => $request->all(['search', 'field', 'direction'])
        ]);
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $studentRole = Role::whereName('Student')->firstOrFail();

        // Create password hash
        if ($request->get('generatePassword')) {
            $password = Str::random(8);
        } else {
            $password = $request->get('password');
        }
        $passwordHash = Hash::make($password);

        // Create student
        $request->merge(['password' => $passwordHash, 'role_id' => $studentRole->id]);
        $user = User::create($request->only(['email', 'firstname', 'lastname', 'sex', 'phone_number', 'date_of_birth', 'country', 'state', 'city', 'zipcode', 'street', 'password', 'role_id']));
        Student::create(['user_id' => $user->id]);

        // Update profile photo
        $this->updateProfilePhoto($user, $request->file('profile_photo'));

        // Send mail to student
        if ($request->get('sendEmail')) {
            Mail::to($user->email)->send(new NewStudentCreated($user->email, $password));
        }

        return Redirect::back();
    }

    public function update(UpdateStudentRequest $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->fill($request->only(['email', 'firstname', 'lastname', 'sex', 'phone_number', 'date_of_birth', 'country', 'state', 'city', 'zipcode', 'street']));
        $user->save();

        $this->updateProfilePhoto($user, $request->file('profile_photo'));

        return Redirect::back();
    }

    public function destroy(int $id): RedirectResponse
    {
        // Soft-delete user
        $user = User::findOrFail($id);
        $user->delete();

        // Soft-delete student
        $student = $user->student;
        $student->delete();

        return Redirect::back();
    }

    public function import(ImportStudentsRequest $request): RedirectResponse
    {
        // Start importing file
        $import = new StudentsImport($request->boolean('generatePassword'), $request->boolean('sendEmail'), $request->get('password'));
        $import->import($request->file('file'));

        // Log validation failures
        if ($import->failures()->count() > 0) {
            Log::error('A validation error occurred during students import. Failures: ' . $import->failures());
        }

        // Log database errors
        if ($import->errors()->count() > 0) {
            Log::error('A database error occurred during students import. Errors: ' . $import->errors());
        }

        return back()->with('data', [
            'students_count' => $import->amount,
            'errors' => ($import->failures()->count() + $import->errors()->count())
        ]);
    }

    public function export(Request $request)
    {
        return (new StudentsExport())->download('students.xlsx');
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

    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json(['success' => true, 'status' => __($status)]);
        }

        return response()->json(['success' => false, 'status' => [trans($status)]], 422);
    }
}
