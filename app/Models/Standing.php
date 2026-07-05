<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

public function tournament()
{
    return $this->belongsTo(Tournament::class);
}

public function club()
{
    return $this->belongsTo(Club::class);
}
