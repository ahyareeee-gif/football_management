<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

public function player()
{
    return $this->belongsTo(Player::class);
}

public function match()
{
    return $this->belongsTo(
        FootballMatch::class,
        'football_match_id'
    );
}
