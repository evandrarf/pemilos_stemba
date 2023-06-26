<?php

namespace App\Exports;

use App\Http\Resources\Voter\Student\ExportStudentVoterListResource;
use App\Models\Voter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;


class StudentVoterExport implements FromCollection, WithColumnWidths, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return ExportStudentVoterListResource::collection(Voter::select('name', 'class', 'username', 'password', 'status')
            ->where('type', 'student')
            ->get());
    }

    public function columnWidths(): array
    {
        return [
            'A' => 22,
            'B' => 13,
            "C" => 13,
            'D' => 18
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Class',
            'Username',
            'Password',
            'Status'
        ];
    }
}
