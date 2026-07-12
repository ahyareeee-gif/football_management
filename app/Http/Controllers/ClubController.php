<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Club::latest()->get();

        return view('clubs.index', compact('clubs'));
    }

    public function create()
    {
        return view('clubs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'founded_year' => ['nullable', 'integer', 'digits:4'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['user_id'] = auth()->id();

        Club::create($validated);

        return redirect()->route('clubs.index')
            ->with('success', 'Club berhasil ditambahkan');
    }

    public function edit(Club $club)
    {
        return view('clubs.edit', compact('club'));
    }

    public function update(Request $request, Club $club)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'founded_year' => ['nullable', 'integer', 'digits:4'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $club->update($validated);

        return redirect()->route('clubs.index')
            ->with('success', 'Club berhasil diperbarui');
    }

    public function destroy(Club $club)
    {
        $club->delete();

        return redirect()->route('clubs.index')
            ->with('success', 'Club berhasil dihapus');
    }
}
