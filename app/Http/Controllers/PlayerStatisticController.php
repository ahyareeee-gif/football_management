<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesScopedData;
use App\Models\FootballMatch;
use App\Models\Player;
use App\Models\PlayerStatistic;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PlayerStatisticController extends Controller
{
    use AuthorizesScopedData;

    public function index()
    {
        $statistics = PlayerStatistic::with(['player.club', 'match.tournament', 'match.homeClub', 'match.awayClub'])
            ->when($this->isAdminTurnamen(), function ($query) {
                $query->whereHas('match.tournament', fn ($tournamentQuery) => $tournamentQuery->where('created_by', auth()->id()));
            })
            ->latest()
            ->get();

        return view('statistics.index', compact('statistics'));
    }

    public function create()
    {
        return view('statistics.create', [
            'statistic' => null,
            'players' => Player::with('club')->orderBy('name')->get(),
            'matches' => $this->scopedMatchesQuery()->with(['tournament', 'homeClub', 'awayClub'])->latest('match_date')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateStatistic($request);

        PlayerStatistic::create($validated);

        return redirect()->route('statistics.index')
            ->with('success', 'Statistik pemain berhasil ditambahkan');
    }

    public function edit(PlayerStatistic $statistic)
    {
        $this->authorizeMatchAccess($statistic->match);

        return view('statistics.edit', [
            'statistic' => $statistic,
            'players' => Player::with('club')->orderBy('name')->get(),
            'matches' => $this->scopedMatchesQuery()->with(['tournament', 'homeClub', 'awayClub'])->latest('match_date')->get(),
        ]);
    }

    public function update(Request $request, PlayerStatistic $statistic)
    {
        $this->authorizeMatchAccess($statistic->match);

        $validated = $this->validateStatistic($request, $statistic);

        $statistic->update($validated);

        return redirect()->route('statistics.index')
            ->with('success', 'Statistik pemain berhasil diperbarui');
    }

    public function destroy(PlayerStatistic $statistic)
    {
        $this->authorizeMatchAccess($statistic->match);

        $statistic->delete();

        return redirect()->route('statistics.index')
            ->with('success', 'Statistik pemain berhasil dihapus');
    }

    private function validateStatistic(Request $request, ?PlayerStatistic $statistic = null): array
    {
        $uniqueRule = Rule::unique('player_statistics', 'player_id')
            ->where('football_match_id', $request->input('football_match_id'));

        if ($statistic) {
            $uniqueRule->ignore($statistic->id);
        }

        $validated = $request->validate([
            'player_id' => ['required', 'exists:players,id', $uniqueRule],
            'football_match_id' => ['required', 'exists:football_matches,id'],
            'goals' => ['required', 'integer', 'min:0'],
            'assists' => ['required', 'integer', 'min:0'],
            'yellow_cards' => ['required', 'integer', 'min:0', 'max:2'],
            'red_cards' => ['required', 'integer', 'min:0', 'max:1'],
        ]);

        $player = Player::findOrFail($validated['player_id']);
        $match = FootballMatch::findOrFail($validated['football_match_id']);
        $this->authorizeMatchAccess($match);

        if (! in_array($player->club_id, [$match->home_club_id, $match->away_club_id], true)) {
            throw ValidationException::withMessages([
                'player_id' => 'Pemain harus berasal dari salah satu klub yang bermain di pertandingan ini.',
            ]);
        }

        return $validated;
    }

    private function scopedMatchesQuery()
    {
        return FootballMatch::query()
            ->when($this->isAdminTurnamen(), function ($query) {
                $query->whereHas('tournament', fn ($tournamentQuery) => $tournamentQuery->where('created_by', auth()->id()));
            });
    }
}
