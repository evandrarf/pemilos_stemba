<?php

namespace App\Services\Recapitulation;

use App\Models\CandidatePair;
use App\Models\Vote;
use App\Models\Voter;

class RecapitulationService
{
    public function getSummary()
    {

        $votes = Vote::query();
        $query = CandidatePair::with('chairman', 'vice_chairman')->get()->map(function ($items) use ($votes) {
            $vote = Vote::where('candidate_pairs_id', $items->id)->count();
            return [
                'candidate_pair' => $items,
                'count' => $vote,
                'persentage' => number_format(($vote / $votes->get()->count()) * 100, 2)
            ];
        });

        return $query;
    }

    public function getVoterSummary($request)
    {
        if (request('type') === 'all') {

            $voter = Voter::query();

            $query = $voter->orderBy('status', "desc")->get()->groupBy('status')->map(function ($items, $status) use ($voter) {
                $count = $items->count();
                return [
                    'status' => $status ? 'Done' : 'Not yet',
                    'count' => $count,
                    'persentage' => number_format(($count / $voter->count()) * 100, 2)
                ];
            })->values();

            $query[] = [
                'status' => 'All',
                'count' => $voter->count()
            ];
        } else {
            $voter = Voter::where('type', $request->type);

            $query = $voter->orderBy('status', "desc")->get()->groupBy('status')->map(function ($items, $status) use ($voter) {
                $count = $items->count();
                return [
                    'status' => $status ? 'Done' : 'Not yet',
                    'count' => $count,
                    'persentage' => number_format(($count / $voter->count()) * 100, 2)
                ];
            })->values();

            $query[] = [
                'status' => 'All',
                'count' => $voter->count()
            ];
        }

        return $query;
    }
}
