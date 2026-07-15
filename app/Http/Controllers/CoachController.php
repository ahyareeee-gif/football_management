<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesScopedData;
use App\Http\Controllers\Concerns\HandlesUploads;
use App\Models\Club;
use App\Models\Coach;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    use AuthorizesScopedData;
    use HandlesUploads;

    public function index()
    {
        $coaches = Coach::with('club')
            ->when($this->isAdminKlub(), function ($query) {
                $query->whereHas('club', fn ($clubQuery) => $clubQuery->where('user_id', auth()->id()));
            })
            ->latest()
            ->get();

        return view('coaches.index', compact('coaches'));
    }

    public function create()
    {
        $clubs = $this->scopedClubsQuery()->orderBy('name')->get();

        return view('coaches.create', compact('clubs'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCoach($request);
        $validated['photo'] = $this->storeUploadedFile($request, 'photo', 'coaches/photos');

        Coach::create($validated);

        return redirect()->route('coaches.index')
            ->with('success', 'Pelatih berhasil ditambahkan');
    }

    public function edit(Coach $coach)
    {
        $this->authorizeClubAccess($coach->club);

        $clubs = $this->scopedClubsQuery()->orderBy('name')->get();

        return view('coaches.edit', compact('coach', 'clubs'));
    }

    public function update(Request $request, Coach $coach)
    {
        $this->authorizeClubAccess($coach->club);

        $validated = $this->validateCoach($request);
        $photo = $this->replaceUploadedFile($request, 'photo', 'coaches/photos', $coach->photo);

        if ($photo) {
            $validated['photo'] = $photo;
        } else {
            unset($validated['photo']);
        }

        $coach->update($validated);

        return redirect()->route('coaches.index')
            ->with('success', 'Pelatih berhasil diperbarui');
    }

    public function destroy(Coach $coach)
    {
        $this->authorizeClubAccess($coach->club);
        $this->deleteUploadedFile($coach->photo);

        $coach->delete();

        return redirect()->route('coaches.index')
            ->with('success', 'Pelatih berhasil dihapus');
    }

    private function validateCoach(Request $request): array
    {
        $validated = $request->validate([
            'club_id' => ['required', 'exists:clubs,id'],
            'name' => ['required', 'string', 'max:255'],
            'license' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $club = Club::findOrFail($validated['club_id']);
        $this->authorizeClubAccess($club);

        return $validated;
    }
}
