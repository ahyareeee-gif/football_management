<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesScopedData;
use App\Models\FootballMatch;
use App\Models\Tournament;
use App\Services\StandingsCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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
        $tournaments = $this->scopedTournamentsQuery()->with('registrations.club')->orderBy('name')->get();

        return view('matches.create', [
            'match' => null,
            'tournaments' => $tournaments,
            'approvedClubsByTournament' => $this->approvedClubsByTournament($tournaments),
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
        $tournaments = $this->scopedTournamentsQuery()->with('registrations.club')->orderBy('name')->get();

        return view('matches.edit', [
            'match' => $match,
            'tournaments' => $tournaments,
            'approvedClubsByTournament' => $this->approvedClubsByTournament($tournaments),
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

    public function generate(Request $request, Tournament $tournament)
    {
        $this->authorizeTournamentAccess($tournament);
        abort_unless($tournament->format === 'League', 422, 'Generate jadwal otomatis saat ini hanya tersedia untuk format League.');

        $validated = $request->validate([
            'start_at' => ['required', 'date'],
            'interval_days' => ['required', 'integer', 'min:0', 'max:30'],
            'venue' => ['nullable', 'string', 'max:255'],
        ]);

        $clubIds = $tournament->registrations()
            ->where('status', 'Approved')
            ->orderBy('club_id')
            ->pluck('club_id')
            ->map(fn ($id) => (int) $id)
            ->values();

        if ($clubIds->count() < 2) {
            throw ValidationException::withMessages([
                'start_at' => 'Minimal harus ada dua club Approved untuk generate jadwal.',
            ]);
        }

        $existingPairs = $tournament->matches()
            ->get(['home_club_id', 'away_club_id'])
            ->mapWithKeys(function (FootballMatch $match) {
                $pair = [(int) $match->home_club_id, (int) $match->away_club_id];
                sort($pair);

                return [implode('-', $pair) => true];
            });

        $matchDate = Carbon::parse($validated['start_at']);
        $created = 0;

        for ($i = 0; $i < $clubIds->count(); $i++) {
            for ($j = $i + 1; $j < $clubIds->count(); $j++) {
                $pair = [$clubIds[$i], $clubIds[$j]];
                sort($pair);

                if ($existingPairs->has(implode('-', $pair))) {
                    continue;
                }

                FootballMatch::create([
                    'tournament_id' => $tournament->id,
                    'home_club_id' => $clubIds[$i],
                    'away_club_id' => $clubIds[$j],
                    'match_date' => $matchDate->copy(),
                    'venue' => $validated['venue'] ?? null,
                    'status' => 'Scheduled',
                ]);

                $created++;
                $matchDate->addDays((int) $validated['interval_days']);
            }
        }

        return redirect()->route('tournaments.show', $tournament)
            ->with('success', $created > 0
                ? "{$created} jadwal pertandingan berhasil dibuat"
                : 'Tidak ada jadwal baru dibuat karena semua pasangan club sudah terjadwal');
    }

    private function approvedClubsByTournament(Collection $tournaments): array
    {
        return $tournaments->mapWithKeys(function (Tournament $tournament) {
            return [
                $tournament->id => $tournament->registrations
                    ->where('status', 'Approved')
                    ->map(fn ($registration) => [
                        'id' => $registration->club?->id,
                        'name' => $registration->club?->name,
                    ])
                    ->filter(fn ($club) => $club['id'] && $club['name'])
                    ->values()
                    ->all(),
            ];
        })->all();
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

        if (count($approvedClubIds) < 2) {
            $errors['tournament_id'] = 'Turnamen harus memiliki minimal dua klub Approved sebelum jadwal dibuat.';
        }

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