<?php

namespace App\Services\CandidatePair;

use App\Actions\Utility\PaginateCollection;
use App\Models\CandidatePair;
use App\Services\FileService;

class CandidatePairService
{
    public function getData()
    {
        $query = CandidatePair::with('chairman', 'vice_chairman')->get()->sortBy('number');

        $paginate = new PaginateCollection();

        $result = $paginate->handle($query, 10);

        return $result;
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

    public function deleteCandidatePair($id)
    {
        $fileService = new FileService();

        $query = CandidatePair::findOrFail($id);

        if ($query->image) $fileService->deleteFileById($query->image);

        $query->delete();

        return $query;
    }
}
