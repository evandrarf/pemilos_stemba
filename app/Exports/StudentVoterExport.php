<?php

namespace App\Exports;

use App\Models\Voter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;


class StudentVoterExport extends StringValueBinder implements FromCollection, WithColumnWidths, WithHeadings, WithCustomValueBinder, WithMapping
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Voter::select('name', 'class', 'username', 'password', 'status')
            ->where('type', 'student')
            ->get();
    }

    public function map($student): array
    {
        return [
            strtoupper($student->name),
            $student->class,
            (string)$student->username,
            $student->password,
            $student->status ? 'sudah' : "belum"
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 22,
            'B' => 13,
            "C" => 13,
            'D' => 18,
            'E' => 22
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Kelas',
            'Username',
            'Password',
            'Status Memilih'
        ];
    }
}
