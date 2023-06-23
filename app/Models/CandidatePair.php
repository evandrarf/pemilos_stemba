<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidatePair extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function chairman()
    {
        return $this->belongsTo(Candidate::class, 'chairman_id');
    }

    public function vice_chairman()
    {
        return $this->belongsTo(Candidate::class, 'vice_chairman_id');
    }
}
