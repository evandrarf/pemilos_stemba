<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCandidateRequest;
use App\Http\Resources\CandidateListResource;
use App\Http\Resources\SubmitCandidateResource;
use App\Services\Candidate\CandidateService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CandidatesController extends Controller
{
    private $candidateService;

    public function __construct(CandidateService $candidateService)
    {
        $this->candidateService = $candidateService;
    }

    public function index()
    {
        return Inertia::render('admin/candidates/index', ['title' => 'Pemilos | Candidates']);
    }

    public function getData(Request $request)
    {
        try {

            $data = $this->candidateService->getData($request);

            $result = new CandidateListResource($data);

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function createCandidate(CreateCandidateRequest $request)
    {
        try {
            $data = $this->candidateService->createCandidate($request);

            $result = new SubmitCandidateResource($data, 'Success create candidate');

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
