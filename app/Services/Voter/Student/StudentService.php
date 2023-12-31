<?php

namespace App\Services\Voter\Student;

use App\Actions\Utility\GeneratePassword;
use App\Actions\Utility\PaginateCollection;
use App\Exports\StudentVoterExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentVoterImport;
use App\Models\Voter;

class StudentService
{
    public function getData($request)
    {
        $filter_class = $request->filter_class;
        $filter_status = $request->filter_status;
        $search = $request->search;

        $query = Voter::where('type', 'student');

        $query->when(request('filter_status', false), function ($q) use ($filter_status) {
            $q->where('status', $filter_status === 'done' ? true : false);
        });

        $query->when(request('filter_class', false), function ($q) use ($filter_class) {
            $q->where('class', 'like', '%' . $filter_class . '%');
        });

        $query->when(request('search', false), function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        });

        $paginate = new PaginateCollection();

        $result = $paginate->handle($query->orderBy('class')->orderBy('name')->get(), 25);

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

    public function updateStudentVoter($request, $id)
    {
        $generatePassword  = new GeneratePassword();

        $data = $request->validated();

        $data['password'] = $data['password'] ?? $generatePassword->handle(10);

        $query = Voter::where('id', $id)->update([
            'name' => $data['name'],
            'username' => $data['username'],
            'class' => $data['class'],
            // 'status' => $data['status'],
            'password' => $data['password']
        ]);

        return $query;
    }

    public function deleteStudentVoter($id)
    {
        $query = Voter::where('id', $id)->delete();

        return $query;
    }

    public function importStudentVoter($request)
    {
        $file = $request->file('file');

        Excel::import(new StudentVoterImport, $file);

        return true;
    }

    public function exportStudentVoter($request)
    {
        $with_password = $request->with_password;
        $status = $request->student_status;

        if ($status === 'all') {
            $status = null;
        }

        $data = new StudentVoterExport($with_password, $status);

        return $data;
    }

    public function deleteAllStudentVoter()
    {
        $query = Voter::where('type', 'student')->delete();

        return $query;
    }
}
