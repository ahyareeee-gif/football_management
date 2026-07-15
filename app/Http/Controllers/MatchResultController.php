<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesScopedData;
use App\Models\FootballMatch;
use App\Services\StandingsCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatchResultController extends Controller
{
    use AuthorizesScopedData;

    public function update(Request $request, FootballMatch $match, StandingsCalculator $standingsCalculator)
    {
        $this->authorizeMatchAccess($match);

        $validated = $request->validate([
            'home_score' => ['required', 'integer', 'min:0'],
            'away_score' => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($match, $validated, $standingsCalculator) {
            $match->result()->updateOrCreate(
                ['football_match_id' => $match->id],
                $validated
            );

            $match->update(['status' => 'Finished']);

            $standingsCalculator->recalculate($match->tournament_id);
        });

        return redirect()->route('matches.index')
            ->with('success', 'Skor pertandingan berhasil disimpan');
    }
}
