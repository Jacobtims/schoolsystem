<?php

namespace App\Imports;

use App\Mail\NewTeacherCreated;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\User;
use Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Mail;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Str;

class TeachersImport implements OnEachRow, SkipsEmptyRows, WithHeadingRow, WithBatchInserts, WithChunkReading, WithValidation, SkipsOnFailure, SkipsOnError
{
    use Importable, SkipsFailures, SkipsErrors;

    public int $amount = 0;
    private Role $teacherRole;

    public function __construct(
        private ?bool   $generatePassword,
        private bool    $sendMail,
        private ?string $password
    )
    {
        $this->teacherRole = Role::whereName('teacher')->firstOrFail();
    }

    public function onRow(Row $row)
    {
        // Hash password
        if ($this->generatePassword) {
            $password = Str::random(8);
        } else {
            $password = $this->password;
        }
        $passwordHash = Hash::make($password);

        // Create teacher
        $user = User::create([
            'firstname' => $row['voornaam'],
            'lastname' => $row['achternaam'],
            'email' => $row['e_mailadres'],
            'sex' => $row['geslacht'],
            'phone_number' => $row['telefoonnummer'],
            'date_of_birth' => $row['geboortedatum'],
            'country' => $row['land'],
            'state' => $row['provincie'],
            'city' => $row['woonplaats'],
            'zipcode' => $row['postcode'],
            'street' => $row['straatnaam_huisnummer'],
            'role_id' => $this->teacherRole->id,
            'password' => $passwordHash,
        ]);
        Teacher::create([
            'user_id' => $user->id,
            'abbreviation' => $row['afkorting'],
            'student_name' => $row['studenten_naam']
        ]);

        // Send email
        if ($this->sendMail) {
            Mail::to($user->email)->send(new NewTeacherCreated($user->email, $password));
        }

        $this->amount++;
    }

    public function rules(): array
    {
        return [
            'afkorting' => 'required|min:2|max:3|unique:teachers,abbreviation',
            'studenten_naam' => 'required|min:2|max:255',
            'voornaam' => 'required|string|min:2,max:255',
            'achternaam' => 'required|string|min:2,max:255',
            'e_mailadres' => 'required|email|unique:users,email|max:255',
            'geslacht' => 'required|string|in:m,v,o',
            'telefoonnummer' => 'required|max:255',
            'geboortedatum' => 'required|date',
            'land' => 'required|string|max:255',
            'provincie' => 'required|string|max:255',
            'woonplaats' => 'required|string|max:255',
            'postcode' => 'required|max:6',
            'straatnaam_huisnummer' => 'required|max:255',
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['geboortedatum'] = Date::excelToDateTimeObject($data['geboortedatum']);

        return $data;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
