<div>
    <label for="tournament_id" class="block text-sm font-medium text-gray-700">Turnamen</label>
    <select id="tournament_id" name="tournament_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">Pilih turnamen</option>
        @foreach ($tournaments as $tournament)
            <option value="{{ $tournament->id }}" @selected(old('tournament_id', $match?->tournament_id) == $tournament->id)>{{ $tournament->name }}</option>
        @endforeach
    </select>
    @error('tournament_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label for="home_club_id" class="block text-sm font-medium text-gray-700">Klub Home</label>
        <select id="home_club_id" name="home_club_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih klub home</option>
            @foreach ($clubs as $club)
                <option value="{{ $club->id }}" @selected(old('home_club_id', $match?->home_club_id) == $club->id)>{{ $club->name }}</option>
            @endforeach
        </select>
        @error('home_club_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="away_club_id" class="block text-sm font-medium text-gray-700">Klub Away</label>
        <select id="away_club_id" name="away_club_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih klub away</option>
            @foreach ($clubs as $club)
                <option value="{{ $club->id }}" @selected(old('away_club_id', $match?->away_club_id) == $club->id)>{{ $club->name }}</option>
            @endforeach
        </select>
        @error('away_club_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label for="match_date" class="block text-sm font-medium text-gray-700">Waktu Pertandingan</label>
        <input id="match_date" type="datetime-local" name="match_date" value="{{ old('match_date', $match?->match_date?->format('Y-m-d\\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
