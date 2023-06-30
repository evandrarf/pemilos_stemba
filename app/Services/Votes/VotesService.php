<?php

namespace App\Services\Votes;

use App\Models\CandidatePair;
use App\Models\Vote;
use App\Models\Voter;
use Inertia\Inertia;

class VotesService
{
    public function getCandidateDetail($id)
    {
        $query = CandidatePair::where('id', $id)->with(['chairman', 'vice_chairman'])->first();

        return $query;
    }

    public function vote($request)
    {
        $data = $request->validated();

        $vote = Vote::where('voter_id', $data['voter_id'])->first();

        if ($vote) {
            return false;
        }

        $query = Vote::create([
            'candidate_pairs_id' => $data['candidate_pair_id'],
            'voter_id' => $data['voter_id']
        ]);

        Voter::where('id', $data['voter_id'])->update(['status' => true]);

        return $query;
    }
}
