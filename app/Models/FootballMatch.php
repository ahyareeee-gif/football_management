<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FootballMatch extends Model
{
    protected $fillable = [
        'tournament_id',
        'home_club_id',
        'away_club_id',
        'match_date',
        'venue',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'match_date' => 'datetime',
        ];
    }

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function homeClub(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'home_club_id');
    }

    public function awayClub(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'away_club_id');
    }

    public function result(): HasOne
    {
        return $this->hasOne(MatchResult::class);
    }

    public function playerStatistics(): HasMany
    {
        return $this->hasMany(PlayerStatistic::class);
    }
}
