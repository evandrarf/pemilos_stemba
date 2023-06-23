<?php

namespace App\Services\CandidatePair;

use App\Models\CandidatePair;

class CandidatePairService
{
    public function getData()
    {
        $query = CandidatePair::with('chairman', 'vice_chairman')->paginate(10);

        return $query;
    }

    public function createCandidatePair($request, $file)
    {
        $data = $request->validated();

        $query = CandidatePair::create([
            'chairman_id' => $data['chairman_id'],
            'vice_chairman_id' => $data['vice_chairman_id'],
            'image' => $file->id,
            'vision' => $data['vision'] ?? null,
            'mission' => $data['mission'] ?? null,
            'number' => $data['number'],
        ]);

        return $query;
    }
}
