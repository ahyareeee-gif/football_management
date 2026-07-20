<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesScopedData;
use App\Http\Controllers\Concerns\HandlesUploads;
use App\Models\Club;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlayerController extends Controller
{
    use AuthorizesScopedData;
    use HandlesUploads;

    public function index()
    {
        $players = Player::with('club')
            ->when($this->isAdminKlub(), function ($query) {
                $query->whereHas('club', fn ($clubQuery) => $clubQuery->where('user_id', auth()->id()));
            })
            ->latest()
            ->get();

        return view('players.index', compact('players'));
    }

    public function create()
    {
        abort_unless($this->isAdminKlub(), 403);
        $this->ensureClubIsApproved();

        $clubs = $this->scopedClubsQuery()->orderBy('name')->get();

        return view('players.create', compact('clubs'));
    }

    public function store(Request $request)
    {
        abort_unless($this->isAdminKlub(), 403);

        $validated = $this->validatePlayer($request);
        $validated['photo'] = $this->storeUploadedFile($request, 'photo', 'players/photos');

        Player::create($validated);

        return redirect()->route('players.index')
            ->with('success', 'Pemain berhasil ditambahkan');
    }

    public function edit(Player $player)
    {
        $this->authorizeClubAccess($player->club);

        if ($this->isAdminKlub()) {
            $this->ensureClubIsApproved($player->club);
        }

        $clubs = $this->scopedClubsQuery()->orderBy('name')->get();

        return view('players.edit', compact('player', 'clubs'));
    }

    public function update(Request $request, Player $player)
    {
        $this->authorizeClubAccess($player->club);

        if ($this->isAdminKlub()) {
            $this->ensureClubIsApproved($player->club);
        }

        $validated = $this->validatePlayer($request);
        $photo = $this->replaceUploadedFile($request, 'photo', 'players/photos', $player->photo);

        if ($photo) {
            $validated['photo'] = $photo;
        } else {
            unset($validated['photo']);
        }

        $player->update($validated);

        return redirect()->route('players.index')
            ->with('success', 'Pemain berhasil diperbarui');
    }

    public function destroy(Player $player)
    {
        $this->authorizeClubAccess($player->club);

        if ($this->isAdminKlub()) {
            $this->ensureClubIsApproved($player->club);
        }

        $this->deleteUploadedFile($player->photo);
        $player->delete();

        return redirect()->route('players.index')
            ->with('success', 'Pemain berhasil dihapus');
    }

    private function validatePlayer(Request $request): array
    {
        $validated = $request->validate([
            'club_id' => ['required', 'exists:clubs,id'],
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', Rule::in(['Goalkeeper', 'Defender', 'Midfielder', 'Forward'])],
            'jersey_number' => ['required', 'integer', 'min:1', 'max:99'],
            'birth_date' => ['nullable', 'date'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $club = Club::findOrFail($validated['club_id']);
        $this->authorizeClubAccess($club);

        if ($this->isAdminKlub()) {
            $this->ensureClubIsApproved($club);
        }

        return $validated;
    }
}