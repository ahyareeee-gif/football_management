<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesScopedData;
use App\Models\FootballMatch;
use App\Models\Player;
use App\Models\PlayerStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
        $matches = $this->finishedMatchesForStatistics();

        return view('statistics.create', [
            'statistic' => null,
            'players' => Player::with('club')->orderBy('name')->get(),
            'matches' => $matches,
            'playersByMatch' => $this->playersByMatch($matches),
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
        $matches = $this->finishedMatchesForStatistics($statistic->football_match_id);

        return view('statistics.edit', [
            'statistic' => $statistic,
            'players' => Player::with('club')->orderBy('name')->get(),
            'matches' => $matches,
            'playersByMatch' => $this->playersByMatch($matches),
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

        if ($match->status !== 'Finished' || ! $match->result()->exists()) {
            throw ValidationException::withMessages([
                'football_match_id' => 'Statistik pemain hanya bisa ditambahkan setelah skor pertandingan disimpan.',
            ]);
        }

        if (! in_array((int) $player->club_id, [(int) $match->home_club_id, (int) $match->away_club_id], true)) {
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

    private function finishedMatchesForStatistics(?int $includeMatchId = null): Collection
    {
        return $this->scopedMatchesQuery()
            ->with(['tournament', 'homeClub', 'awayClub', 'result'])
            ->where(function ($query) use ($includeMatchId) {
                $query->where(function ($finishedQuery) {
                    $finishedQuery->where('status', 'Finished')
                        ->whereHas('result');
                });

                if ($includeMatchId) {
                    $query->orWhere('id', $includeMatchId);
                }
            })
            ->latest('match_date')
            ->get();
    }

    private function playersByMatch(Collection $matches): array
    {
        $clubIds = $matches
            ->flatMap(fn (FootballMatch $match) => [$match->home_club_id, $match->away_club_id])
            ->filter()
            ->unique()
            ->values();

        $playersByClub = Player::with('club')
            ->whereIn('club_id', $clubIds)
            ->orderBy('name')
            ->get()
            ->groupBy('club_id');

        return $matches->mapWithKeys(function (FootballMatch $match) use ($playersByClub) {
            $players = collect([$match->home_club_id, $match->away_club_id])
                ->flatMap(fn ($clubId) => $playersByClub->get($clubId, collect()))
                ->map(fn (Player $player) => [
                    'id' => $player->id,
                    'name' => $player->name,
                    'club' => $player->club?->name,
                ])
                ->values()
                ->all();

            return [$match->id => $players];
        })->all();
    }
}
