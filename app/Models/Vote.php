<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function candidate_pair()
    {
        return $this->belongsTo(CandidatePair::class, 'candidate_pairs_id');
    }
}
