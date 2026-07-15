<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesScopedData;
use App\Http\Controllers\Concerns\HandlesUploads;
use App\Models\Tournament;
use App\Models\TournamentRegistration;
use App\Services\StandingsCalculator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TournamentRegistrationController extends Controller
{
    use AuthorizesScopedData;
    use HandlesUploads;

    public function store(Request $request, Tournament $tournament, StandingsCalculator $standingsCalculator)
    {
        $this->authorizeTournamentAccess($tournament);

        $validated = $request->validate([
            'club_id' => [
                'required',
                'exists:clubs,id',
                Rule::unique('tournament_registrations', 'club_id')
                    ->where('tournament_id', $tournament->id),
            ],
            'payment_proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
        ]);

        $tournament->registrations()->create([
            'club_id' => $validated['club_id'],
            'status' => 'Pending',
            'payment_proof' => $this->storeUploadedFile($request, 'payment_proof', 'registrations/payment-proofs'),
        ]);

        $standingsCalculator->recalculate($tournament->id);

        return redirect()->route('tournaments.show', $tournament)
            ->with('success', 'Klub berhasil didaftarkan ke turnamen');
    }

    public function update(Request $request, TournamentRegistration $registration, StandingsCalculator $standingsCalculator)
    {
        $this->authorizeRegistrationAccess($registration);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['Pending', 'Approved', 'Rejected'])],
        ]);

        $registration->update($validated);
        $standingsCalculator->recalculate($registration->tournament_id);

        return redirect()->route('tournaments.show', $registration->tournament)
            ->with('success', 'Status pendaftaran berhasil diperbarui');
    }

    public function destroy(TournamentRegistration $registration, StandingsCalculator $standingsCalculator)
    {
        $this->authorizeRegistrationAccess($registration);

        $tournament = $registration->tournament;
        $this->deleteUploadedFile($registration->payment_proof);

        $registration->delete();
        $standingsCalculator->recalculate($tournament->id);

        return redirect()->route('tournaments.show', $tournament)
            ->with('success', 'Pendaftaran klub berhasil dihapus');
    }
}
