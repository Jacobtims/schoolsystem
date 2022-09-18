<?php

namespace App\Imports;

use App\Models\Role;
use App\Models\Student;
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
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Str;

class StudentsImport implements OnEachRow, SkipsEmptyRows, WithHeadingRow, WithBatchInserts, WithChunkReading, WithValidation, SkipsOnFailure, SkipsOnError
{
    use Importable, SkipsFailures, SkipsErrors;

    public int $amount = 0;

    public function __construct(
        private ?bool   $generatePassword,
        private bool    $sendMail,
        private ?string $password
    )
    {
    }

    public function onRow(Row $row)
    {
        if ($this->generatePassword) {
            $password = Str::random(8);
            $passwordHash = Hash::make($password);
        } else {
            $passwordHash = Hash::make($this->password);
        }

        if ($this->sendMail) {
            //TODO: Send e-mail to email-address...
        }

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
            'role_id' => Role::whereName('student')->firstOrFail()->id,
            'password' => $passwordHash,
        ]);
        Student::create(['user_id' => $user->id]);

        $this->amount++;
    }

    public function rules(): array
    {
        return [
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
