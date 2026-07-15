<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesScopedData;
use App\Models\Club;
use App\Models\FootballMatch;
use App\Models\Tournament;
use App\Services\StandingsCalculator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class FootballMatchController extends Controller
{
    use AuthorizesScopedData;

    public function index()
    {
        $matches = FootballMatch::with(['tournament', 'homeClub', 'awayClub', 'result'])
            ->when($this->isAdminTurnamen(), function ($query) {
                $query->whereHas('tournament', fn ($tournamentQuery) => $tournamentQuery->where('created_by', auth()->id()));
            })
            ->latest('match_date')
            ->get();

        return view('matches.index', compact('matches'));
    }

    public function create()
    {
        return view('matches.create', [
            'match' => null,
            'tournaments' => $this->scopedTournamentsQuery()->orderBy('name')->get(),
            'clubs' => Club::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateMatch($request);

        FootballMatch::create($validated);

        return redirect()->route('matches.index')
            ->with('success', 'Jadwal pertandingan berhasil ditambahkan');
    }

    public function edit(FootballMatch $match)
    {
        $this->authorizeMatchAccess($match);

        return view('matches.edit', [
            'match' => $match,
            'tournaments' => $this->scopedTournamentsQuery()->orderBy('name')->get(),
            'clubs' => Club::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, FootballMatch $match, StandingsCalculator $standingsCalculator)
    {
        $this->authorizeMatchAccess($match);

        $validated = $this->validateMatch($request);
        $oldTournamentId = $match->tournament_id;
        $shouldRecalculate = $match->result()->exists() || $match->status === 'Finished' || $validated['status'] === 'Finished';

        $match->update($validated);

        if ($shouldRecalculate) {
            $standingsCalculator->recalculate($oldTournamentId);
            $standingsCalculator->recalculate($match->tournament_id);
        }

        return redirect()->route('matches.index')
            ->with('success', 'Jadwal pertandingan berhasil diperbarui');
    }

    public function destroy(FootballMatch $match, StandingsCalculator $standingsCalculator)
    {
        $this->authorizeMatchAccess($match);

        $tournamentId = $match->tournament_id;
        $shouldRecalculate = $match->result()->exists() || $match->status === 'Finished';

        $match->delete();

        if ($shouldRecalculate) {
            $standingsCalculator->recalculate($tournamentId);
        }

        return redirect()->route('matches.index')
            ->with('success', 'Pertandingan berhasil dihapus');
    }

    private function validateMatch(Request $request): array
    {
        $validated = $request->validate([
            'tournament_id' => ['required', 'exists:tournaments,id'],
            'home_club_id' => ['required', 'exists:clubs,id', 'different:away_club_id'],
            'away_club_id' => ['required', 'exists:clubs,id'],
            'match_date' => ['required', 'date'],
            'venue' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['Scheduled', 'Finished', 'Postponed'])],
        ]);

        $tournament = Tournament::findOrFail($validated['tournament_id']);
        $this->authorizeTournamentAccess($tournament);

        $approvedClubIds = $tournament->registrations()
            ->where('status', 'Approved')
            ->pluck('club_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $errors = [];

        if (! in_array((int) $validated['home_club_id'], $approvedClubIds, true)) {
            $errors['home_club_id'] = 'Klub home harus sudah Approved di turnamen ini.';
        }

        if (! in_array((int) $validated['away_club_id'], $approvedClubIds, true)) {
            $errors['away_club_id'] = 'Klub away harus sudah Approved di turnamen ini.';
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        return $validated;
    }
}
