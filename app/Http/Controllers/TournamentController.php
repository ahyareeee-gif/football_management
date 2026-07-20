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
            ->when($this->isAdminKlub(), fn ($query) => $query->where('status', 'Open'))
            ->with(['creator', 'registrations' => fn ($query) => $query->where('club_id', $this->currentUserClub()?->id)])
            ->withCount('registrations')
            ->latest()
            ->get();

        return view('tournaments.index', compact('tournaments'));
    }

    public function create()
    {
        abort_if($this->isAdminKlub(), 403);

        return view('tournaments.create');
    }

    public function store(Request $request)
    {
        abort_if($this->isAdminKlub(), 403);

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
        if ($this->isAdminKlub()) {
            abort_unless($tournament->status === 'Open', 403);
        } else {
            $this->authorizeTournamentAccess($tournament);
        }

        $tournament->load([
            'creator',
            'registrations.club',
            'matches.homeClub',
            'matches.awayClub',
            'matches.result',
            'standings.club',
        ]);

        $registeredClubIds = $tournament->registrations->pluck('club_id');
        $clubs = Club::where('status', 'approved')->whereNotIn('id', $registeredClubIds)->orderBy('name')->get();
        $clubRegistration = $this->isAdminKlub()
            ? $tournament->registrations->firstWhere('club_id', $this->currentUserClub()?->id)
            : null;
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

        return view('tournaments.show', compact('tournament', 'clubs', 'clubRegistration', 'standings', 'matches', 'topScorers', 'topAssists'));
    }


    public function report(Tournament $tournament)
    {
        if ($this->isAdminKlub()) {
            $club = $this->currentUserClub();
            abort_unless(
                $club && $tournament->registrations()->where('club_id', $club->id)->where('status', 'Approved')->exists(),
                403
            );
        } else {
            $this->authorizeTournamentAccess($tournament);
        }

        $tournament->load([
            'creator',
            'registrations.club',
            'matches.homeClub',
            'matches.awayClub',
            'matches.result',
            'standings.club',
        ]);

        $matches = $tournament->matches->sortBy('match_date')->values();
        $finishedMatches = $matches->where('status', 'Finished')->filter(fn ($match) => $match->result)->values();
        $unfinishedMatches = $matches->reject(fn ($match) => $match->status === 'Finished' && $match->result)->values();
        $approvedRegistrations = $tournament->registrations->where('status', 'Approved')->values();
        $standings = $tournament->standings
            ->sortByDesc('goal_difference')
            ->sortByDesc('points')
            ->values();

        $playerStats = PlayerStatistic::with('player.club')
            ->whereIn('football_match_id', $matches->pluck('id'))
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
            })
            ->values();

        return view('tournaments.report', [
            'tournament' => $tournament,
            'summary' => [
                'approvedParticipants' => $approvedRegistrations->count(),
                'totalMatches' => $matches->count(),
                'finishedMatches' => $finishedMatches->count(),
                'unfinishedMatches' => $unfinishedMatches->count(),
                'totalGoals' => $finishedMatches->sum(fn ($match) => $match->result->home_score + $match->result->away_score),
                'yellowCards' => $playerStats->sum('yellow_cards'),
                'redCards' => $playerStats->sum('red_cards'),
            ],
            'approvedRegistrations' => $approvedRegistrations,
            'standings' => $standings,
            'recentResults' => $finishedMatches->sortByDesc('match_date')->take(8)->values(),
            'unfinishedMatches' => $unfinishedMatches->sortBy('match_date')->take(8)->values(),
            'topScorers' => $playerStats->sortByDesc('goals')->take(8)->values(),
            'topAssists' => $playerStats->sortByDesc('assists')->take(8)->values(),
            'cardLeaders' => $playerStats->sortByDesc(fn ($stat) => $stat->yellow_cards + $stat->red_cards)->take(8)->values(),
        ]);
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
