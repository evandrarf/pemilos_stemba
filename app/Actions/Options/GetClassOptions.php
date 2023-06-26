<?php

namespace App\Actions\Options;

use App\Models\Voter;

class GetClassOptions
{
    public function handle()
    {
        $classes = Voter::where('type', 'student')->select('class')->distinct()->orderBy('class')->get()->pluck('class');

        return $classes;
    }
}
