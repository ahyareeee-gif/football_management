<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

public function club(): BelongsTo
{
    return $this->belongsTo(Club::class);
}
