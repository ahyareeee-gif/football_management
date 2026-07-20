<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Club;
use App\Models\FootballMatch;
use App\Models\Tournament;
use App\Models\TournamentRegistration;
use Illuminate\Database\Eloquent\Builder;

trait AuthorizesScopedData
{
    protected function isSuperAdmin(): bool
    {
        return auth()->user()?->hasRole('Super Admin') ?? false;
    }

    protected function isAdminKlub(): bool
    {
        return auth()->user()?->hasRole('Admin Klub') ?? false;
    }

    protected function isAdminTurnamen(): bool
    {
        return auth()->user()?->hasRole('Admin Turnamen') ?? false;
    }

    protected function currentUserClub(): ?Club
    {
        return auth()->user()?->club;
    }

    protected function ensureClubIsApproved(?Club $club = null): Club
    {
        $club ??= $this->currentUserClub();

        abort_unless($club, 403, 'Admin Klub harus membuat klub terlebih dahulu.');
        abort_unless($club->isApproved(), 403, 'Klub Anda belum disetujui Super Admin.');

        return $club;
    }

    protected function scopedClubsQuery(): Builder
    {
        $query = Club::query();

        if ($this->isAdminKlub()) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    protected function scopedTournamentsQuery(): Builder
    {
        $query = Tournament::query();

        if ($this->isAdminTurnamen()) {
            $query->where('created_by', auth()->id());
        }

        return $query;
    }

    protected function authorizeClubAccess(Club $club): void
    {
        if ($this->isSuperAdmin()) {
            return;
        }

        abort_unless($this->isAdminKlub() && $club->user_id === auth()->id(), 403);
    }

    protected function authorizeTournamentAccess(Tournament $tournament): void
    {
        if ($this->isSuperAdmin()) {
            return;
        }

        abort_unless($this->isAdminTurnamen() && $tournament->created_by === auth()->id(), 403);
    }

    protected function authorizeRegistrationAccess(TournamentRegistration $registration): void
    {
        $this->authorizeTournamentAccess($registration->tournament);
    }

    protected function authorizeMatchAccess(FootballMatch $match): void
    {
        $this->authorizeTournamentAccess($match->tournament);
    }
}