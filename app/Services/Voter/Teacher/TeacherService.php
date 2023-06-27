<?php

namespace App\Services\Voter\Teacher;

use App\Actions\Utility\GeneratePassword;
use App\Actions\Utility\PaginateCollection;
use App\Exports\TeacherVoterExport;
use App\Imports\TeacherVoterImport;
use App\Models\Voter;

class TeacherService
{
    public function getData($request)
    {
        $query = Voter::where('type', 'teacher');

        $filter_status = $request->filter_status;

        $search = $request->search;

        $query->when(request('filter_status', false), function ($q) use ($filter_status) {
            $q->where('status', $filter_status);
        });

        $query->when(request('search', false), function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        });

        $paginate = new PaginateCollection();

        $result = $paginate->handle($query->orderBy('name')->get(), 25);

        return $result;
    }

    public function createTeacherVoter($request)
    {
        $generatePassword  = new GeneratePassword();

        $data = $request->validated();

        $data['type'] = 'teacher';

        $data['status'] = false;

        $data['password'] = $data['password'] ?? $generatePassword->handle(10);

        $voter = Voter::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => $data['password'],
            'type' => $data['type'],
            'status' => $data['status'],
        ]);

        return $voter;
    }

    public function updateTeacherVoter($request, $id)
    {
        $generatePassword  = new GeneratePassword();

        $data = $request->validated();

        $data['password'] = $data['password'] ?? $generatePassword->handle(10);

        $query = Voter::where('id', $id)->update([
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => $data['password']
        ]);

        return $query;
    }

    public function deleteTeacherVoter($id)
    {
        $query = Voter::where('id', $id)->delete();

        return $query;
    }

    public function importTeacherVoter($request)
    {
        $file = $request->file('file');

        $import = new TeacherVoterImport();

        $import->import($file);

        return $import;
    }

    public function exportTeacherVoter()
    {
        $data = new TeacherVoterExport();

        return $data;
    }

    public function deleteAllTeacherVoter()
    {
        $query = Voter::where('type', 'teacher')->delete();

        return $query;
    }
}
