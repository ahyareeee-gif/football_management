<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;


class TournamentRegistration extends Model
{
    return $this->belongsTo(Tournament::class);
}

public function club()
{
    return $this->belongsTo(Club::class);
}
