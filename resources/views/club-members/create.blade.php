<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Anggota Club</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('club-members.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5" x-data="{ type: '{{ old('member_type', $defaultType) }}' }">
                    @csrf

                    <x-form-errors />

                    <div>
                        <label for="member_type" class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select id="member_type" name="member_type" x-model="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="player">Pemain</option>
                            <option value="coach">Pelatih</option>
                            <option value="staff">Staff</option>
                        </select>
                        @error('member_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="photo" class="block text-sm font-medium text-gray-700">Foto</label>
                        <input id="photo" type="file" name="photo" accept="image/*" class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-gray-800 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-gray-700">
                        @error('photo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div x-show="type === 'player'" class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700">Posisi</label>
                            <select id="position" name="position" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih posisi</option>
                                @foreach (['Goalkeeper', 'Defender', 'Midfielder', 'Forward'] as $position)
                                    <option value="{{ $position }}" @selected(old('position') === $position)>{{ $position }}</option>
                                @endforeach
                            </select>
                            @error('position') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="jersey_number" class="block text-sm font-medium text-gray-700">Nomor Punggung</label>
                            <input id="jersey_number" type="number" name="jersey_number" min="1" max="99" value="{{ old('jersey_number') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('jersey_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div x-show="type === 'player'">
                        <label for="birth_date" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input id="birth_date" type="date" name="birth_date" value="{{ old('birth_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('birth_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div x-show="type === 'coach'">
                        <label for="license" class="block text-sm font-medium text-gray-700">Lisensi</label>
                        <input id="license" type="text" name="license" value="{{ old('license') }}" placeholder="Contoh: AFC C, AFC B, UEFA A" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('license') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div x-show="type === 'staff'">
                        <label for="role" class="block text-sm font-medium text-gray-700">Jabatan Staff</label>
                        <input id="role" type="text" name="role" value="{{ old('role') }}" placeholder="Contoh: Manager, Media Officer, Kitman" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('club-members.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>