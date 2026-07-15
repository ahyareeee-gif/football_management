<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Coach;
use App\Models\FootballMatch;
use App\Models\Player;
use App\Models\PlayerStatistic;
use App\Models\Tournament;
use App\Models\TournamentRegistration;
use App\Models\User;

class DashboardController extends Controller
{
    public function redirect()
    {
        $user = auth()->user();

        if ($user->hasRole('Super Admin')) {
            return redirect('/super-admin/dashboard');
        }

        if ($user->hasRole('Admin Turnamen')) {
            return redirect('/tournament/dashboard');
        }

        if ($user->hasRole('Admin Klub')) {
            return redirect('/club/dashboard');
        }

        abort(403);
    }

    public function superAdmin()
    {
        return view('super-admin.dashboard', [
            'totals' => [
                'users' => User::count(),
                'clubs' => Club::count(),
                'players' => Player::count(),
                'coaches' => Coach::count(),
                'tournaments' => Tournament::count(),
                'matches' => FootballMatch::count(),
            ],
            'latestTournaments' => Tournament::latest()->take(5)->get(),
            'upcomingMatches' => FootballMatch::with(['tournament', 'homeClub', 'awayClub'])
                ->where('status', 'Scheduled')
                ->orderBy('match_date')
                ->take(5)
                ->get(),
            'recentResults' => FootballMatch::with(['tournament', 'homeClub', 'awayClub', 'result'])
                ->where('status', 'Finished')
                ->whereHas('result')
                ->latest('match_date')
                ->take(5)
                ->get(),
        ]);
    }

    public function tournamentAdmin()
    {
        $user = auth()->user();
        $tournamentIds = Tournament::where('created_by', $user->id)->pluck('id');

        return view('admin-tournament.dashboard', [
            'totals' => [
                'tournaments' => $tournamentIds->count(),
                'registrations' => TournamentRegistration::whereIn('tournament_id', $tournamentIds)->count(),
                'approvedRegistrations' => TournamentRegistration::whereIn('tournament_id', $tournamentIds)->where('status', 'Approved')->count(),
                'matches' => FootballMatch::whereIn('tournament_id', $tournamentIds)->count(),
            ],
            'tournaments' => Tournament::withCount('registrations')
                ->where('created_by', $user->id)
                ->latest()
                ->take(6)
                ->get(),
            'pendingRegistrations' => TournamentRegistration::with(['tournament', 'club'])
                ->whereIn('tournament_id', $tournamentIds)
                ->where('status', 'Pending')
                ->latest()
                ->take(6)
                ->get(),
            'upcomingMatches' => FootballMatch::with(['tournament', 'homeClub', 'awayClub'])
                ->whereIn('tournament_id', $tournamentIds)
                ->where('status', 'Scheduled')
                ->orderBy('match_date')
                ->take(5)
                ->get(),
        ]);
    }

    public function clubAdmin()
    {
        $user = auth()->user();
        $club = $user->club()->withCount(['players', 'coaches', 'registrations'])->first();
        $clubId = $club?->id;

        return view('admin-club.dashboard', [
            'club' => $club,
            'players' => $club ? $club->players()->orderBy('name')->take(8)->get() : collect(),
            'coaches' => $club ? $club->coaches()->orderBy('name')->take(5)->get() : collect(),
            'registrations' => $club ? $club->registrations()->with('tournament')->latest()->take(5)->get() : collect(),
            'upcomingMatches' => $clubId ? FootballMatch::with(['tournament', 'homeClub', 'awayClub'])
                ->where('status', 'Scheduled')
                ->where(function ($query) use ($clubId) {
                    $query->where('home_club_id', $clubId)
                        ->orWhere('away_club_id', $clubId);
                })
                ->orderBy('match_date')
                ->take(5)
                ->get() : collect(),
            'topPlayers' => $clubId ? PlayerStatistic::with('player')
                ->whereHas('player', fn ($query) => $query->where('club_id', $clubId))
                ->get()
                ->groupBy('player_id')
                ->map(function ($rows) {
                    $first = $rows->first();

                    return (object) [
                        'player' => $first->player,
                        'goals' => $rows->sum('goals'),
                        'assists' => $rows->sum('assists'),
                    ];
                })
                ->sortByDesc('goals')
                ->take(5)
                ->values() : collect(),
        ]);
    }
}
