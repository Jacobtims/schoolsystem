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

class StudentsExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithColumnFormatting
{
    use Exportable;

    private Role $studentRole;

    public function __construct()
    {
        $this->studentRole = Role::whereName('Student')->firstOrFail();
    }

    public function query(): Builder
    {
        return User::query()->whereRoleId($this->studentRole->id)->with('student');
    }

    public function map($row): array
    {
        return [
            $row->student->id,
            $row->firstname,
            $row->lastname,
            $row->email,
            $row->sex,
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
            'Leerlingnummer',
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
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}
