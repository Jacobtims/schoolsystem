<?php

namespace App\Exports;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TeachersExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithColumnFormatting
{
    use Exportable;

    private Role $teacherRole;

    public function __construct()
    {
        $this->teacherRole = Role::whereName('Teacher')->firstOrFail();
    }

    public function query(): Builder
    {
        return User::query()->whereRoleId($this->teacherRole->id)->with('teacher');
    }

    public function map($row): array
    {
        return [
            $row->teacher->abbreviation,
            $row->teacher->student_name,
            $row->firstname,
            $row->lastname,
            $row->email,
            $row->raw_sex,
            $row->phone_number,
            Date::stringToExcel($row->date_of_birth),
            $row->country,
            $row->state,
            $row->city,
            $row->zipcode,
            $row->street
        ];
    }

    public function headings(): array
    {
        return [
            'Afkorting',
            'Studenten naam',
            'Voornaam',
            'Achternaam',
            'E-mailadres',
            'Geslacht',
            'Telefoonnummer',
            'Geboortedatum',
            'Land',
            'Provincie',
            'Woonplaats',
            'Postcode',
            'Straatnaam + huisnummer'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}
