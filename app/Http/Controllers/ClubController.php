<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Club::all();

        return view('clubs.index', compact('clubs'));
    }

    public function create()
    {
        return view('clubs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'founded_year' => 'nullable|integer',
            'city' => 'nullable|max:255',
            'address' => 'nullable',
            'description' => 'nullable',
        ]);

        Club::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'founded_year' => $request->founded_year,
            'city' => $request->city,
            'address' => $request->address,
            'description' => $request->description,
        ]);

        return redirect()->route('clubs.index')
            ->with('success', 'Club berhasil ditambahkan');
    }
}