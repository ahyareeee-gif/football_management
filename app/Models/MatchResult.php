<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchResult extends Model
{
    protected $fillable = [
        'football_match_id',
        'home_score',
        'away_score',
    ];

    public function match(): BelongsTo
    {
        return $this->belongsTo(FootballMatch::class, 'football_match_id');
    }
}
