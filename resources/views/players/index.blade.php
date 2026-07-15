<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Pemain</h2>
            <a href="{{ route('players.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Tambah Pemain
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Nama</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Klub</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Posisi</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Nomor</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Tanggal Lahir</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($players as $player)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            @if ($player->photo)
                                                <img src="{{ Storage::url($player->photo) }}" alt="Foto {{ $player->name }}" class="h-10 w-10 rounded-md object-cover ring-1 ring-gray-200">
                                            @else
                                                <div class="flex h-10 w-10 items-center justify-center rounded-md bg-gray-100 text-xs font-semibold text-gray-500">{{ Str::substr($player->name, 0, 2) }}</div>
                                            @endif
                                            <span class="font-medium text-gray-900">{{ $player->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $player->club?->name }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $player->position }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $player->jersey_number }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $player->birth_date?->format('d M Y') ?? '-' }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('players.edit', $player) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form action="{{ route('players.destroy', $player) }}" method="POST" class="inline-block ml-3" onsubmit="return confirm('Yakin ingin menghapus data ini? Data yang terkait bisa ikut terhapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada data pemain.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
