<?php

namespace App\Services\Voter\Student;

use App\Actions\Utility\GeneratePassword;
use App\Actions\Utility\PaginateCollection;
use App\Models\Voter;

class StudentService
{
    public function getData()
    {
        // $data = Voter::where('type', 'student')->get()->sortBy('class');

        $data = Voter::where('type', 'student')->orderBy('class')->get();

        $paginate = new PaginateCollection();

        $result = $paginate->handle($data, 10);

        return $result;
    }

    public function createStudentVoter($request)
    {
        $generatePassword  = new GeneratePassword();

        $data = $request->validated();

        $data['type'] = 'student';

        $data['status'] = false;

        $data['password'] = $data['password'] ?? $generatePassword->handle(10);

        $query = Voter::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'class' => $data['class'],
            'password' => $data['password'],
            'type' => $data['type'],
            'status' => $data['status'],
        ]);

        return $query;
    }
}
