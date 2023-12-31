<?php

namespace App\Imports;

use App\Actions\Utility\GeneratePassword;
use App\Models\Voter;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class StudentVoterImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithCalculatedFormulas, SkipsEmptyRows
{
    use Importable;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $generatePassword  = new GeneratePassword();

        return new Voter([
            'name' => $row['name'],
            'class' => $row['class'],
            'username' => preg_replace('/\s+/', '', $row['username']),
            'password' => $row['password'] ?? $generatePassword->handle(10),
            'status' => false,
            'type' => 'student',
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'required', 'max:255'],
            'class' => ['string', 'required', 'max:255'],
            'username' => ['required', 'max:255', Rule::unique('voters', 'username')->where('type', 'student')],
            'password' => ['string', 'nullable', 'max:255'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'The name field is required.',
            'class.required' => 'The class field is required.',
            'username.required' => 'The username field is required.',
            'username.unique' => 'The username has already been taken.',
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
