<div>
    <label for="club_id" class="block text-sm font-medium text-gray-700">Klub</label>
    <select id="club_id" name="club_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">Pilih klub</option>
        @foreach ($clubs as $club)
            <option value="{{ $club->id }}" @selected(old('club_id', $player?->club_id) == $club->id)>{{ $club->name }}</option>
        @endforeach
    </select>
    @error('club_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="photo" class="block text-sm font-medium text-gray-700">Foto Pemain</label>
    @if ($player?->photo)
        <img src="{{ Storage::url($player->photo) }}" alt="Foto {{ $player->name }}" class="mt-2 h-16 w-16 rounded-md object-cover ring-1 ring-gray-200">
    @endif
    <input id="photo" type="file" name="photo" accept="image/*" class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-gray-800 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-gray-700">
    @error('photo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="name" class="block text-sm font-medium text-gray-700">Nama Pemain</label>
    <input id="name" type="text" name="name" value="{{ old('name', $player?->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label for="position" class="block text-sm font-medium text-gray-700">Posisi</label>
        <select id="position" name="position" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih posisi</option>
            @foreach (['Goalkeeper', 'Defender', 'Midfielder', 'Forward'] as $position)
                <option value="{{ $position }}" @selected(old('position', $player?->position) === $position)>{{ $position }}</option>
            @endforeach
        </select>
        @error('position') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="jersey_number" class="block text-sm font-medium text-gray-700">Nomor Punggung</label>
        <input id="jersey_number" type="number" name="jersey_number" min="1" max="99" value="{{ old('jersey_number', $player?->jersey_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('jersey_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label for="birth_date" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
    <input id="birth_date" type="date" name="birth_date" value="{{ old('birth_date', $player?->birth_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('birth_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>
