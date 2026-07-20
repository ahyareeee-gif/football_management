<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentRegistration extends Model
{
    protected $fillable = [
        'tournament_id',
        'club_id',
        'status',
        'contact_person',
        'contact_phone',
        'notes',
        'payment_proof',
        'registration_document',
        'agreement_accepted',
    ];

    protected function casts(): array
    {
        return [
            'agreement_accepted' => 'boolean',
        ];
    }

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
