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
        $query = CandidatePair::with('chairman', 'vice_chairman')->orderBy('number')->get()->map(function ($items) use ($votes) {
            $vote = Vote::where('candidate_pairs_id', $items->id)->count();
            return [
                'candidate_pair' => $items,
                'count' => $vote,
                'persentage' => number_format(($vote  / $votes->get()->count()) * 100, 2)
            ];
        });

        return $query;
    }

    public function getVoterSummary($request)
    {
        if (request('type') === 'all') {

            $voter = Voter::query();

            $done = Voter::where('status', 1)->get();

            $query[] = [
                'status' => 'Done',
                'count' => $done->count(),
                'persentage' => $voter->count() > 0 ? number_format(($done->count() / $voter->count()) * 100, 2) : 0
            ];

            $not_yet = Voter::where('status', 0)->get();

            $query[] = [
                'status' => 'Not yet',
                'count' => $not_yet->count(),
                'persentage' => $voter->count() > 0 ? number_format(($not_yet->count() / $voter->count()) * 100, 2) : 0
            ];

            $query[] = [
                'status' => 'Total',
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
                'status' => 'Total',
                'count' => $voter->count()
            ];
        }

        return $query;
    }
}
