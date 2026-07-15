<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesScopedData;
use App\Http\Controllers\Concerns\HandlesUploads;
use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    use AuthorizesScopedData;
    use HandlesUploads;

    public function index()
    {
        $clubs = $this->scopedClubsQuery()
            ->withCount(['players', 'coaches'])
            ->latest()
            ->get();

        return view('clubs.index', compact('clubs'));
    }

    public function create()
    {
        abort_if($this->isAdminKlub() && $this->currentUserClub(), 403, 'Admin Klub hanya boleh memiliki satu klub.');

        return view('clubs.create');
    }

    public function store(Request $request)
    {
        abort_if($this->isAdminKlub() && $this->currentUserClub(), 403, 'Admin Klub hanya boleh memiliki satu klub.');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'founded_year' => ['nullable', 'integer', 'digits:4'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['user_id'] = auth()->id();
        $validated['logo'] = $this->storeUploadedFile($request, 'logo', 'clubs/logos');

        Club::create($validated);

        return redirect()->route('clubs.index')
            ->with('success', 'Club berhasil ditambahkan');
    }

    public function edit(Club $club)
    {
        $this->authorizeClubAccess($club);

        return view('clubs.edit', compact('club'));
    }

    public function update(Request $request, Club $club)
    {
        $this->authorizeClubAccess($club);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'founded_year' => ['nullable', 'integer', 'digits:4'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $logo = $this->replaceUploadedFile($request, 'logo', 'clubs/logos', $club->logo);

        if ($logo) {
            $validated['logo'] = $logo;
        } else {
            unset($validated['logo']);
        }

        $club->update($validated);

        return redirect()->route('clubs.index')
            ->with('success', 'Club berhasil diperbarui');
    }

    public function destroy(Club $club)
    {
        $this->authorizeClubAccess($club);
        $this->deleteUploadedFile($club->logo);

        $club->delete();

        return redirect()->route('clubs.index')
            ->with('success', 'Club berhasil dihapus');
    }
}
