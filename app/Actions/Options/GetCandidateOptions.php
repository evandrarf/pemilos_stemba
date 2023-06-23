<?php

namespace App\Actions\Options;

use App\Models\Candidate;

class GetCandidateOptions
{
    public function handle()
    {
        $candidates = Candidate::get()->pluck('name', 'id');

        return $candidates;
    }
}
