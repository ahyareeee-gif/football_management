<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerStatistic extends Model
{
    protected $fillable = [
        'player_id',
        'football_match_id',
        'goals',
        'assists',
        'yellow_cards',
        'red_cards',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(FootballMatch::class, 'football_match_id');
    }
}
