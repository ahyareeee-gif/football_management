<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Standing extends Model
{
    protected $fillable = [
        'tournament_id',
        'club_id',
        'played',
        'win',
        'draw',
        'lose',
        'goals_for',
        'goals_against',
        'goal_difference',
        'points',
    ];

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
