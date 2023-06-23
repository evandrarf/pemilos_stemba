<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Actions\Options\GetCandidateOptions;
use App\Http\Requests\CreateCandidatePairRequest;
use App\Http\Resources\CandidatePairListResource;
use App\Http\Resources\SubmitCandidatePairResource;
use App\Services\CandidatePair\CandidatePairService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CandidatePairsController extends Controller
{
    private $getCandidateOptions;
    private $candidatePairService;
    private $fileService;

    public function __construct(GetCandidateOptions $getCandidateOptions, CandidatePairService $candidatePairService, FileService $fileService)
    {
        $this->getCandidateOptions = $getCandidateOptions;
        $this->candidatePairService = $candidatePairService;
        $this->fileService = $fileService;
    }

    public function index()
    {
        return Inertia::render('admin/candidate-pairs/index', [
            'title' => 'Candidate Pairs | Pemilos',
            'additional' => [
                'candidate_list' => $this->getCandidateOptions->handle()
            ]
        ]);
    }

    public function getData()
    {
        try {
            $data = $this->candidatePairService->getData();

            $result = new CandidatePairListResource($data);

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function createCandidatePair(CreateCandidatePairRequest $request)
    {
        try {
            // $file = $this->fileService->uploadFile($request->file('file'));
            $data = $this->candidatePairService->createCandidatePair($request);

            $result = new SubmitCandidatePairResource($data, 'Candidate Pair created successfully');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
