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

    private $with_password;
    private $status;

    public function __construct(string $with_password,   $status)
    {
        $this->with_password = $with_password;
        $this->status =  $status;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = [
            'name',
            'class',
            'username',
            'status'
        ];

        if ($this->with_password === 'yes') {
            array_splice($data, 3, 0, 'password');
        }

        return Voter::select($data)
            ->where('type', 'student')->when($this->status, function ($query) {
                if ($this->status === 'done') {
                    $status = true;
                } else {
                    $status = false;
                }
                return $query->where('status', $status);
            })
            ->get();
    }

    public function map($student): array
    {
        $data = [
            strtoupper($student->name),
            $student->class,
            (string)$student->username,
            $student->status ? 'sudah' : "belum"
        ];

        if ($this->with_password === 'yes') {
            array_splice($data, 3, 0, $student->password);
        }

        return $data;
    }

    public function columnWidths(): array
    {
        if ($this->with_password === 'yes') {
            $data = [
                'A' => 22,
                'B' => 13,
                "C" => 13,
                'D' => 18,
                'E' => 22
            ];
        } else {
            $data = [
                'A' => 22,
                'B' => 13,
                "C" => 13,
                'D' => 22
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        $data = [
            'Nama',
            'Kelas',
            'NIS',
            'Status Memilih'
        ];

        if ($this->with_password === 'yes') {
            array_splice($data, 3, 0, 'Password');
        }

        return $data;
    }
}
