<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Statistik Pemain</h2>
            <a href="{{ route('statistics.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Tambah Statistik
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
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Pemain</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Pertandingan</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500">Gol</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500">Assist</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500">Kuning</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500">Merah</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($statistics as $statistic)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $statistic->player?->name }}</div>
                                        <div class="text-gray-500">{{ $statistic->player?->club?->name }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">
                                        <div>{{ $statistic->match?->homeClub?->name }} vs {{ $statistic->match?->awayClub?->name }}</div>
                                        <div class="text-gray-500">{{ $statistic->match?->tournament?->name }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-700">{{ $statistic->goals }}</td>
                                    <td class="px-4 py-3 text-center text-gray-700">{{ $statistic->assists }}</td>
                                    <td class="px-4 py-3 text-center text-gray-700">{{ $statistic->yellow_cards }}</td>
                                    <td class="px-4 py-3 text-center text-gray-700">{{ $statistic->red_cards }}</td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('statistics.edit', $statistic) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form action="{{ route('statistics.destroy', $statistic) }}" method="POST" class="inline-block ml-3" onsubmit="return confirm('Yakin ingin menghapus data ini? Data yang terkait bisa ikut terhapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada statistik pemain.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

