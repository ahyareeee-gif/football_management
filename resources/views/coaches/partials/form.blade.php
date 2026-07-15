<div>
    <label for="club_id" class="block text-sm font-medium text-gray-700">Klub</label>
    <select id="club_id" name="club_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">Pilih klub</option>
        @foreach ($clubs as $club)
            <option value="{{ $club->id }}" @selected(old('club_id', $coach?->club_id) == $club->id)>{{ $club->name }}</option>
        @endforeach
    </select>
    @error('club_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="photo" class="block text-sm font-medium text-gray-700">Foto Pelatih</label>
    @if ($coach?->photo)
        <img src="{{ Storage::url($coach->photo) }}" alt="Foto {{ $coach->name }}" class="mt-2 h-16 w-16 rounded-md object-cover ring-1 ring-gray-200">
    @endif
    <input id="photo" type="file" name="photo" accept="image/*" class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-gray-800 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-gray-700">
    @error('photo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="name" class="block text-sm font-medium text-gray-700">Nama Pelatih</label>
    <input id="name" type="text" name="name" value="{{ old('name', $coach?->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="license" class="block text-sm font-medium text-gray-700">Lisensi</label>
    <input id="license" type="text" name="license" value="{{ old('license', $coach?->license) }}" placeholder="Contoh: AFC C, AFC B, UEFA A" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('license') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>
