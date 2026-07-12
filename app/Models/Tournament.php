<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tournament extends Model
{
    protected $fillable = [
        'created_by',
        'name',
        'description',
        'start_date',
        'end_date',
        'format',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(TournamentRegistration::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(FootballMatch::class);
    }

    public function standings(): HasMany
    {
        return $this->hasMany(Standing::class);
    }
}
