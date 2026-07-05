<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

public function tournament()
{
    return $this->belongsTo(Tournament::class);
}

public function homeClub()
{
    return $this->belongsTo(Club::class, 'home_club_id');
}

public function awayClub()
{
    return $this->belongsTo(Club::class, 'away_club_id');
}

public function result()
{
    return $this->hasOne(MatchResult::class);
}

public function playerStatistics()
{
    return $this->hasMany(PlayerStatistic::class);
}