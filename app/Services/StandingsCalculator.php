<?php

namespace App\Services;

use App\Models\FootballMatch;
use App\Models\Standing;
use Illuminate\Support\Facades\DB;

class StandingsCalculator
{
    public function recalculate(int $tournamentId): void
    {
        $clubIds = DB::table('tournament_registrations')
            ->where('tournament_id', $tournamentId)
            ->where('status', 'Approved')
            ->pluck('club_id');

        Standing::where('tournament_id', $tournamentId)->delete();

        $standings = [];

        foreach ($clubIds as $clubId) {
            $standings[$clubId] = [
                'tournament_id' => $tournamentId,
                'club_id' => $clubId,
                'played' => 0,
                'win' => 0,
                'draw' => 0,
                'lose' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'goal_difference' => 0,
                'points' => 0,
            ];
        }

        $matches = FootballMatch::with('result')
            ->where('tournament_id', $tournamentId)
            ->where('status', 'Finished')
            ->whereHas('result')
            ->get();

        foreach ($matches as $match) {
            if (! isset($standings[$match->home_club_id], $standings[$match->away_club_id])) {
                continue;
            }

            $homeScore = $match->result->home_score;
            $awayScore = $match->result->away_score;

            $standings[$match->home_club_id]['played']++;
            $standings[$match->away_club_id]['played']++;
            $standings[$match->home_club_id]['goals_for'] += $homeScore;
            $standings[$match->home_club_id]['goals_against'] += $awayScore;
            $standings[$match->away_club_id]['goals_for'] += $awayScore;
            $standings[$match->away_club_id]['goals_against'] += $homeScore;

            if ($homeScore > $awayScore) {
                $standings[$match->home_club_id]['win']++;
                $standings[$match->away_club_id]['lose']++;
                $standings[$match->home_club_id]['points'] += 3;
            } elseif ($homeScore < $awayScore) {
                $standings[$match->away_club_id]['win']++;
                $standings[$match->home_club_id]['lose']++;
                $standings[$match->away_club_id]['points'] += 3;
            } else {
                $standings[$match->home_club_id]['draw']++;
                $standings[$match->away_club_id]['draw']++;
                $standings[$match->home_club_id]['points']++;
                $standings[$match->away_club_id]['points']++;
            }
        }

        foreach ($standings as $standing) {
            $standing['goal_difference'] = $standing['goals_for'] - $standing['goals_against'];
            Standing::create($standing);
        }
    }
}
