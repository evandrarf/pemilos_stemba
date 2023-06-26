<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Voter;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class TeacherVoterExport extends StringValueBinder  implements FromCollection, WithColumnWidths, WithHeadings, WithMapping, WithCustomValueBinder
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Voter::select('name', 'username', 'password', 'status')
            ->where('type', 'teacher')
            ->get();
    }

    public function map($teacher): array
    {
        return [
            strtoupper($teacher->name),
            (string)$teacher->username,
            $teacher->password,
            $teacher->status ? 'sudah' : "belum"
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 22,
            'B' => 22,
            'C' => 18
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Username',
            'Password',
            'Status'
        ];
    }
}
