@php
    $selectedTournamentId = (string) old('tournament_id', $match?->tournament_id);
    $selectedHomeClubId = (string) old('home_club_id', $match?->home_club_id);
    $selectedAwayClubId = (string) old('away_club_id', $match?->away_club_id);
@endphp

<div x-data="matchForm({
    approvedClubsByTournament: @js($approvedClubsByTournament),
    tournamentId: '{{ $selectedTournamentId }}',
    homeClubId: '{{ $selectedHomeClubId }}',
    awayClubId: '{{ $selectedAwayClubId }}'
})" x-init="syncClubs()">
    <div>
        <label for="tournament_id" class="block text-sm font-medium text-gray-700">Turnamen</label>
        <select id="tournament_id" name="tournament_id" x-model="tournamentId" @change="syncClubs()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih turnamen</option>
            @foreach ($tournaments as $tournament)
                <option value="{{ $tournament->id }}">{{ $tournament->name }}</option>
            @endforeach
        </select>
        @error('tournament_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="mt-5 rounded-md bg-yellow-50 p-4 text-sm text-yellow-800" x-show="tournamentId && clubs.length < 2">
        Turnamen ini belum memiliki minimal dua club Approved. Approve pendaftaran club terlebih dahulu sebelum membuat jadwal.
    </div>

    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="home_club_id" class="block text-sm font-medium text-gray-700">Klub Home</label>
            <select id="home_club_id" name="home_club_id" x-model="homeClubId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Pilih klub home</option>
                <template x-for="club in clubs" :key="club.id">
                    <option :value="club.id" x-text="club.name"></option>
                </template>
            </select>
            @error('home_club_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="away_club_id" class="block text-sm font-medium text-gray-700">Klub Away</label>
            <select id="away_club_id" name="away_club_id" x-model="awayClubId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Pilih klub away</option>
                <template x-for="club in clubs" :key="club.id">
                    <option :value="club.id" x-text="club.name"></option>
                </template>
            </select>
            @error('away_club_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label for="match_date" class="block text-sm font-medium text-gray-700">Waktu Pertandingan</label>
        <input id="match_date" type="datetime-local" name="match_date" value="{{ old('match_date', $match?->match_date?->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('match_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
        <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @foreach (['Scheduled', 'Finished', 'Postponed'] as $status)
                <option value="{{ $status }}" @selected(old('status', $match?->status ?? 'Scheduled') === $status)>{{ $status }}</option>
            @endforeach
        </select>
        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label for="venue" class="block text-sm font-medium text-gray-700">Venue</label>
    <input id="venue" type="text" name="venue" value="{{ old('venue', $match?->venue) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('venue') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<script>
    function matchForm({ approvedClubsByTournament, tournamentId, homeClubId, awayClubId }) {
        return {
            approvedClubsByTournament,
            tournamentId,
            homeClubId,
            awayClubId,
            clubs: [],
            syncClubs() {
                this.clubs = this.approvedClubsByTournament[this.tournamentId] || [];
                const ids = this.clubs.map((club) => String(club.id));

                if (!ids.includes(String(this.homeClubId))) {
                    this.homeClubId = '';
                }

                if (!ids.includes(String(this.awayClubId))) {
                    this.awayClubId = '';
                }
            }
        };
    }
</script>