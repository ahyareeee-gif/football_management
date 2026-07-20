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
                'pendingUsers' => User::where('status', 'pending')->count(),
                'clubs' => Club::count(),
                'pendingClubs' => Club::where('status', 'pending')->count(),
                'players' => Player::count(),
                'coaches' => Coach::count(),
                'tournaments' => Tournament::count(),
                'matches' => FootballMatch::count(),
            ],
            'pendingClubs' => Club::with('user')
                ->where('status', 'pending')
                ->latest()
                ->take(6)
                ->get(),
            'latestUsers' => User::with('roles')
                ->latest()
                ->take(6)
                ->get(),
            'latestTournaments' => Tournament::withCount([
                    'registrations',
                    'registrations as approved_registrations_count' => fn ($query) => $query->where('status', 'Approved'),
                    'matches',
                ])
                ->latest()
                ->take(5)
                ->get(),
            'matchesNeedingScores' => FootballMatch::with(['tournament', 'homeClub', 'awayClub'])
                ->whereIn('status', ['Scheduled', 'Postponed'])
                ->where('match_date', '<=', now())
                ->orderBy('match_date')
                ->take(6)
                ->get(),
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
                'pendingRegistrations' => TournamentRegistration::whereIn('tournament_id', $tournamentIds)->where('status', 'Pending')->count(),
                'approvedRegistrations' => TournamentRegistration::whereIn('tournament_id', $tournamentIds)->where('status', 'Approved')->count(),
                'matches' => FootballMatch::whereIn('tournament_id', $tournamentIds)->count(),
                'unfinishedMatches' => FootballMatch::whereIn('tournament_id', $tournamentIds)->whereIn('status', ['Scheduled', 'Postponed'])->count(),
            ],
            'tournaments' => Tournament::withCount([
                    'registrations',
                    'registrations as approved_registrations_count' => fn ($query) => $query->where('status', 'Approved'),
                    'matches',
                    'matches as finished_matches_count' => fn ($query) => $query->where('status', 'Finished'),
                ])
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
            'scheduleReadyTournaments' => Tournament::withCount([
                    'registrations as approved_registrations_count' => fn ($query) => $query->where('status', 'Approved'),
                    'matches',
                ])
                ->where('created_by', $user->id)
                ->where('format', 'League')
                ->whereIn('status', ['Open', 'Running'])
                ->having('approved_registrations_count', '>=', 2)
                ->orderBy('start_date')
                ->take(5)
                ->get(),
            'matchesNeedingScores' => FootballMatch::with(['tournament', 'homeClub', 'awayClub'])
                ->whereIn('tournament_id', $tournamentIds)
                ->whereIn('status', ['Scheduled', 'Postponed'])
                ->where('match_date', '<=', now())
                ->orderBy('match_date')
                ->take(6)
                ->get(),
            'upcomingMatches' => FootballMatch::with(['tournament', 'homeClub', 'awayClub'])
                ->whereIn('tournament_id', $tournamentIds)
                ->where('status', 'Scheduled')
                ->where('match_date', '>', now())
                ->orderBy('match_date')
                ->take(5)
                ->get(),
            'recentResults' => FootballMatch::with(['tournament', 'homeClub', 'awayClub', 'result'])
                ->whereIn('tournament_id', $tournamentIds)
                ->where('status', 'Finished')
                ->whereHas('result')
                ->latest('match_date')
                ->take(5)
                ->get(),
        ]);
    }

    public function clubAdmin()
    {
        $user = auth()->user();
        $club = $user->club()->withCount(['players', 'coaches', 'staff', 'registrations'])->first();
        $clubId = $club?->id;

        return view('admin-club.dashboard', [
            'club' => $club,
            'players' => $club ? $club->players()->orderBy('name')->take(8)->get() : collect(),
            'coaches' => $club ? $club->coaches()->orderBy('name')->take(5)->get() : collect(),
            'staff' => $club ? $club->staff()->orderBy('name')->take(5)->get() : collect(),
            'registrations' => $club ? $club->registrations()->with('tournament')->latest()->take(5)->get() : collect(),
            'openTournaments' => $club && $club->isApproved() ? Tournament::where('status', 'Open')
                ->whereDoesntHave('registrations', fn ($query) => $query->where('club_id', $club->id))
                ->orderBy('start_date')
                ->take(5)
                ->get() : collect(),
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

