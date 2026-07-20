<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubStaff extends Model
{
    protected $table = 'club_staff';

    protected $fillable = [
        'club_id',
        'name',
        'role',
        'photo',
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}