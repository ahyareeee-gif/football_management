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
        if ($this->isAdminKlub()) {
            $club = $this->ensureClubIsApproved();
            abort_unless($tournament->status === 'Open', 403, 'Pendaftaran turnamen belum dibuka.');

            $validated = $request->validate([
                'contact_person' => ['required', 'string', 'max:255'],
                'contact_phone' => ['required', 'string', 'max:30'],
                'notes' => ['nullable', 'string', 'max:1000'],
                'payment_proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
                'registration_document' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
                'agreement_accepted' => ['accepted'],
            ]);

            abort_if(
                TournamentRegistration::where('tournament_id', $tournament->id)->where('club_id', $club->id)->exists(),
                422,
                'Club Anda sudah terdaftar pada turnamen ini.'
            );

            $tournament->registrations()->create([
                'club_id' => $club->id,
                'status' => 'Pending',
                'contact_person' => $validated['contact_person'],
                'contact_phone' => $validated['contact_phone'],
                'notes' => $validated['notes'] ?? null,
                'payment_proof' => $this->storeUploadedFile($request, 'payment_proof', 'registrations/payment-proofs'),
                'registration_document' => $this->storeUploadedFile($request, 'registration_document', 'registrations/documents'),
                'agreement_accepted' => true,
            ]);
        } else {
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
                'agreement_accepted' => true,
            ]);
        }

        $standingsCalculator->recalculate($tournament->id);

        return redirect()->route('tournaments.show', $tournament)
            ->with('success', 'Pendaftaran turnamen berhasil dikirim');
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
        $this->deleteUploadedFile($registration->registration_document);

        $registration->delete();
        $standingsCalculator->recalculate($tournament->id);

        return redirect()->route('tournaments.show', $tournament)
            ->with('success', 'Pendaftaran klub berhasil dihapus');
    }
}