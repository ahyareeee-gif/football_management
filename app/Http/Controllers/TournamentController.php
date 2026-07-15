<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesScopedData;
use App\Models\Club;
use App\Models\PlayerStatistic;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TournamentController extends Controller
{
    use AuthorizesScopedData;

    public function index()
    {
        $tournaments = $this->scopedTournamentsQuery()
            ->with('creator')
            ->withCount('registrations')
            ->latest()
            ->get();

        return view('tournaments.index', compact('tournaments'));
    }

    public function create()
    {
        return view('tournaments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'format' => ['required', Rule::in(['League', 'Knockout', 'Group+Knockout'])],
            'status' => ['required', Rule::in(['Draft', 'Open', 'Running', 'Finished'])],
        ]);

        $validated['created_by'] = auth()->id();

        Tournament::create($validated);

        return redirect()->route('tournaments.index')
            ->with('success', 'Turnamen berhasil ditambahkan');
    }

    public function show(Tournament $tournament)
    {
        $this->authorizeTournamentAccess($tournament);

        $tournament->load([
            'creator',
            'registrations.club',
            'matches.homeClub',
            'matches.awayClub',
            'matches.result',
            'standings.club',
        ]);

        $registeredClubIds = $tournament->registrations->pluck('club_id');
        $clubs = Club::whereNotIn('id', $registeredClubIds)->orderBy('name')->get();
        $standings = $tournament->standings
            ->sortByDesc('goal_difference')
            ->sortByDesc('points')
            ->values();
        $matches = $tournament->matches->sortBy('match_date')->values();
        $matchIds = $tournament->matches->pluck('id');

        $playerStats = PlayerStatistic::with('player.club')
            ->whereIn('football_match_id', $matchIds)
            ->get()
            ->groupBy('player_id')
            ->map(function ($rows) {
                $first = $rows->first();

                return (object) [
                    'player' => $first->player,
                    'goals' => $rows->sum('goals'),
                    'assists' => $rows->sum('assists'),
                    'yellow_cards' => $rows->sum('yellow_cards'),
                    'red_cards' => $rows->sum('red_cards'),
                ];
            });

        $topScorers = $playerStats->sortByDesc('goals')->take(5)->values();
        $topAssists = $playerStats->sortByDesc('assists')->take(5)->values();

        return view('tournaments.show', compact('tournament', 'clubs', 'standings', 'matches', 'topScorers', 'topAssists'));
    }

    public function edit(Tournament $tournament)
    {
        $this->authorizeTournamentAccess($tournament);

        return view('tournaments.edit', compact('tournament'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $this->authorizeTournamentAccess($tournament);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'format' => ['required', Rule::in(['League', 'Knockout', 'Group+Knockout'])],
            'status' => ['required', Rule::in(['Draft', 'Open', 'Running', 'Finished'])],
        ]);

        $tournament->update($validated);

        return redirect()->route('tournaments.index')
            ->with('success', 'Turnamen berhasil diperbarui');
    }

    public function destroy(Tournament $tournament)
    {
        $this->authorizeTournamentAccess($tournament);

        $tournament->delete();

        return redirect()->route('tournaments.index')
            ->with('success', 'Turnamen berhasil dihapus');
    }
}
