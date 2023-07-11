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
            'username',
            'status'
        ];

        if ($this->with_password === 'yes') {
            array_splice($data, 2, 0, 'password');
        }

        return Voter::select($data)
            ->where('type', 'teacher')->when($this->status, function ($query) {
                if ($this->status === 'done') {
                    $status = true;
                } else {
                    $status = false;
                }
                return $query->where('status', $status);
            })
            ->get();
    }

    public function map($teacher): array
    {
        $data = [
            strtoupper($teacher->name),
            (string)$teacher->username,
            $teacher->status ? 'sudah' : "belum"
        ];

        if ($this->with_password === 'yes') {
            array_splice($data, 2, 0, $teacher->password,);
        }

        return $data;
    }

    public function columnWidths(): array
    {
        $data = [
            'A' => 22,
            'B' => 22,
            'C' => 18,
        ];
        if ($this->with_password === 'yes') {
            $data = [
                'A' => 22,
                'B' => 22,
                'C' => 18,
                'D' => 18,
            ];
        }
        return $data;
    }

    public function headings(): array
    {
        $data = [
            'Nama',
            'NIP',
            'Status Memilih'
        ];

        if ($this->with_password === 'yes') {
            array_splice($data, 2, 0, 'Password');
        }

        return $data;
    }
}
