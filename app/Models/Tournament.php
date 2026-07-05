<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

public function creator(): BelongsTo
{
    return $this->belongsTo(User::class, 'created_by');
}
public function registrations()
{
    return $this->hasMany(TournamentRegistration::class);
}

public function matches()
{
    return $this->hasMany(FootballMatch::class);
}

public function standings()
{
    return $this->hasMany(Standing::class);
}