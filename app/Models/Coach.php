<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coach extends Model
{
    protected $fillable = [
        'club_id',
        'name',
        'license',
        'photo',
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
