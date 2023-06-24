<?php

namespace App\Services\CandidatePair;

use App\Models\CandidatePair;
use App\Services\FileService;

class CandidatePairService
{
    public function getData()
    {
        $query = CandidatePair::with('chairman', 'vice_chairman')->paginate(10);

        return $query;
    }

    public function createCandidatePair($request)
    {
        $fileService = new FileService();

        $request_file = $request->file('file');

        $data = $request->validated();

        $file = $request_file ? $fileService->uploadFile($data['file']) : null;

        $query = CandidatePair::create([
            'chairman_id' => $data['chairman_id'],
            'vice_chairman_id' => $data['vice_chairman_id'],
            'image' => $file->id ?? null,
            'vision' => $data['vision'] ?? null,
            'mission' => $data['mission'] ?? null,
            'number' => $data['number'],
        ]);

        return $query;
    }

    public function updateCandidatePair($request, $id)
    {
        $fileService = new FileService();

        $request_file = $request->file('file');

        $data = $request->validated();

        $file = $request_file ? $fileService->uploadFile($data['file']) : null;

        $query = CandidatePair::where('id', $id)->update([
            'chairman_id' => $data['chairman_id'],
            'vice_chairman_id' => $data['vice_chairman_id'],
            'image' => $file->id ?? $data['file_id'] ?? null,
            'vision' => $data['vision'] ?? null,
            'mission' => $data['mission'] ?? null,
            'number' => $data['number'],
        ]);

        return $query;
    }
}
