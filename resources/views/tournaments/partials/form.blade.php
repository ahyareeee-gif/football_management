<div>
    <label for="name" class="block text-sm font-medium text-gray-700">Nama Turnamen</label>
    <input id="name" type="text" name="name" value="{{ old('name', $tournament?->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $tournament?->description) }}</textarea>
    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
        <input id="start_date" type="date" name="start_date" value="{{ old('start_date', $tournament?->start_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
        <input id="end_date" type="date" name="end_date" value="{{ old('end_date', $tournament?->end_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label for="format" class="block text-sm font-medium text-gray-700">Format</label>
        <select id="format" name="format" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih format</option>
            @foreach (['League', 'Knockout', 'Group+Knockout'] as $format)
                <option value="{{ $format }}" @selected(old('format', $tournament?->format) === $format)>{{ $format }}</option>
            @endforeach
        </select>
        @error('format') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
        <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @foreach (['Draft', 'Open', 'Running', 'Finished'] as $status)
                <option value="{{ $status }}" @selected(old('status', $tournament?->status ?? 'Draft') === $status)>{{ $status }}</option>
            @endforeach
        </select>
        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
