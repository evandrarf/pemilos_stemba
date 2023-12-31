<?php

namespace App\Services\Candidate;

use App\Models\Candidate;

class CandidateService
{
    public function getData($request)
    {
        $query = Candidate::paginate(10);

        return $query;
    }

    public function createCandidate($request)
    {
        $data = $request->validated();

        $query = Candidate::create([
            'name' => $data['name'],
            'nis' => $data['nis'],
            'class' => $data['class'],
        ]);

        return $query;
    }

    public function updateCandidate($request, $id)
    {
        $data = $request->validated();

        $query = Candidate::where('id', $id)->update([
            'name' => $data['name'],
            'nis' => $data['nis'],
            'class' => $data['class'],
        ]);

        return $query;
    }

    public function deleteCandidate($id)
    {
        $query = Candidate::where('id', $id)->delete();

        return $query;
    }
}
