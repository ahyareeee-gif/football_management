<div>
    <label for="name" class="block text-sm font-medium text-gray-700">Nama Klub</label>
    <input id="name" type="text" name="name" value="{{ old('name', $club?->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="logo" class="block text-sm font-medium text-gray-700">Logo Klub</label>
    @if ($club?->logo)
        <img src="{{ Storage::url($club->logo) }}" alt="Logo {{ $club->name }}" class="mt-2 h-16 w-16 rounded-md object-cover ring-1 ring-gray-200">
    @endif
    <input id="logo" type="file" name="logo" accept="image/*" class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-gray-800 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-gray-700">
    @error('logo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label for="city" class="block text-sm font-medium text-gray-700">Kota</label>
        <input id="city" type="text" name="city" value="{{ old('city', $club?->city) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="founded_year" class="block text-sm font-medium text-gray-700">Tahun Berdiri</label>
        <input id="founded_year" type="number" name="founded_year" value="{{ old('founded_year', $club?->founded_year) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('founded_year') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
    <textarea id="address" name="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $club?->address) }}</textarea>
    @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $club?->description) }}</textarea>
    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>
