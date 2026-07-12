<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    protected $fillable = [
        'club_id',
        'name',
        'position',
        'jersey_number',
        'birth_date',
        'photo',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function statistics(): HasMany
    {
        return $this->hasMany(PlayerStatistic::class);
    }
}
