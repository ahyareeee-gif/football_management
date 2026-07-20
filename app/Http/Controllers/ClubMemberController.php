<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesScopedData;
use App\Http\Controllers\Concerns\HandlesUploads;
use App\Models\Club;
use App\Models\ClubStaff;
use App\Models\Coach;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClubMemberController extends Controller
{
    use AuthorizesScopedData;
    use HandlesUploads;

    public function index()
    {
        $club = $this->ensureClubIsApproved();

        return view('club-members.index', [
            'club' => $club->loadCount(['players', 'coaches', 'staff']),
            'players' => $club->players()->latest()->get(),
            'coaches' => $club->coaches()->latest()->get(),
            'staff' => $club->staff()->latest()->get(),
        ]);
    }

    public function create(Request $request)
    {
        $club = $this->ensureClubIsApproved();
        $defaultType = in_array($request->query('member_type'), ['player', 'coach', 'staff'], true)
            ? $request->query('member_type')
            : 'player';

        return view('club-members.create', compact('club', 'defaultType'));
    }

    public function store(Request $request)
    {
        $club = $this->ensureClubIsApproved();

        $validated = $request->validate([
            'member_type' => ['required', Rule::in(['player', 'coach', 'staff'])],
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'position' => ['required_if:member_type,player', 'nullable', Rule::in(['Goalkeeper', 'Defender', 'Midfielder', 'Forward'])],
            'jersey_number' => ['required_if:member_type,player', 'nullable', 'integer', 'min:1', 'max:99'],
            'birth_date' => ['nullable', 'date'],
            'license' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
        ]);

        $photo = $this->storeUploadedFile($request, 'photo', 'club-members/photos');

        match ($validated['member_type']) {
            'player' => Player::create([
                'club_id' => $club->id,
                'name' => $validated['name'],
                'position' => $validated['position'],
                'jersey_number' => $validated['jersey_number'],
                'birth_date' => $validated['birth_date'] ?? null,
                'photo' => $photo,
            ]),
            'coach' => Coach::create([
                'club_id' => $club->id,
                'name' => $validated['name'],
                'license' => $validated['license'] ?? null,
                'photo' => $photo,
            ]),
            'staff' => ClubStaff::create([
                'club_id' => $club->id,
                'name' => $validated['name'],
                'role' => $validated['role'] ?? null,
                'photo' => $photo,
            ]),
        };

        return redirect()->route('club-members.index')
            ->with('success', 'Anggota club berhasil ditambahkan');
    }
}