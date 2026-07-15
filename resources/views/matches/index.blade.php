<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Pertandingan</h2>
            <a href="{{ route('matches.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Tambah Jadwal
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
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Turnamen</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Pertandingan</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Waktu</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Skor</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($matches as $match)
                                <tr>
                                    <td class="px-4 py-3 text-gray-700">{{ $match->tournament?->name }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $match->homeClub?->name }} vs {{ $match->awayClub?->name }}</div>
                                        <div class="text-gray-500">{{ $match->venue ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $match->match_date->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $match->status }}</td>
                                    <td class="px-4 py-3">
                                        <form action="{{ route('matches.result.update', $match) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="home_score" min="0" value="{{ old('home_score', $match->result?->home_score ?? 0) }}" class="w-20 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="text-gray-500">-</span>
                                            <input type="number" name="away_score" min="0" value="{{ old('away_score', $match->result?->away_score ?? 0) }}" class="w-20 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <button type="submit" class="rounded-md bg-gray-800 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">Simpan</button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('matches.edit', $match) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form action="{{ route('matches.destroy', $match) }}" method="POST" class="inline-block ml-3" onsubmit="return confirm('Yakin ingin menghapus data ini? Data yang terkait bisa ikut terhapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada jadwal pertandingan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


