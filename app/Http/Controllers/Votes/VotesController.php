<?php

namespace App\Http\Controllers\Votes;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateVotesRequest;
use App\Http\Resources\CandidatePairDetailResource;
use App\Services\Votes\VotesService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VotesController extends Controller
{
    private $votesService;
    public function __construct(VotesService $votesService)
    {
        $this->votesService = $votesService;
    }

    public function index()
    {
        return Inertia::render('voter/home/index', ['title' => 'Beranda | Pemilos Stemba']);
    }

    public function detail($id)
    {
        return Inertia::render('voter/detail/index', ['title' => 'Detail Paslon | Pemilos Stemba', 'additional' => [
            'candidate_detail' => new CandidatePairDetailResource($this->votesService->getCandidateDetail($id))
        ]]);

        // return $this->votesService->getCandidateDetail($id);
    }

    public function vote(CreateVotesRequest $request)
    {
        try {
            $this->votesService->vote($request);

            return response()->json([
                "status" => 200,
                "message" => "Success",
            ], 200);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function thanks()
    {
        return Inertia::render('voter/thanks/index', ['title' => 'Terima Kasih | Pemilos Stemba']);
    }
}
