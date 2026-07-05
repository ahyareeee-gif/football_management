<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

public function club(): BelongsTo
{
    return $this->belongsTo(Club::class);
}

public function statistics()
{
    return $this->hasMany(PlayerStatistic::class);
}