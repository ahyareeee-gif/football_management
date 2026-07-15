<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Super Admin</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-6">
                @foreach ([
                    'Users' => $totals['users'],
                    'Klub' => $totals['clubs'],
                    'Pemain' => $totals['players'],
                    'Pelatih' => $totals['coaches'],
                    'Turnamen' => $totals['tournaments'],
                    'Pertandingan' => $totals['matches'],
                ] as $label => $value)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-5">
                            <div class="text-sm font-medium text-gray-500">{{ $label }}</div>
                            <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $value }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-base font-semibold text-gray-900">Turnamen Terbaru</h3>
                            <a href="{{ route('tournaments.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Lihat semua</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($latestTournaments as $tournament)
                                <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $tournament->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $tournament->format }} · {{ $tournament->status }}</div>
                                    </div>
                                    <a href="{{ route('tournaments.show', $tournament) }}" class="text-sm text-gray-600 hover:text-gray-900">Detail</a>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada turnamen.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-base font-semibold text-gray-900">Jadwal Mendatang</h3>
                            <a href="{{ route('matches.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Lihat semua</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($upcomingMatches as $match)
                                <div class="border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div class="font-medium text-gray-900">{{ $match->homeClub?->name }} vs {{ $match->awayClub?->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $match->tournament?->name }} · {{ $match->match_date->format('d M Y H:i') }}</div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada jadwal mendatang.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="mb-4 text-base font-semibold text-gray-900">Hasil Terakhir</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Turnamen</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Pertandingan</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Skor</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($recentResults as $match)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-700">{{ $match->tournament?->name }}</td>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $match->homeClub?->name }} vs {{ $match->awayClub?->name }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $match->result?->home_score }} - {{ $match->result?->away_score }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $match->match_date->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada hasil pertandingan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
