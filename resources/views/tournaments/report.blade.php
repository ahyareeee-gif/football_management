<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Turnamen</h2>
                <p class="mt-1 text-sm text-gray-600">{{ $tournament->name }}</p>
            </div>
            <a href="{{ route('tournaments.show', $tournament) }}" class="text-sm text-gray-600 hover:text-gray-900">Kembali</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $tournament->name }}</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ $tournament->format }} � {{ $tournament->status }} � {{ $tournament->start_date->format('d M Y') }} - {{ $tournament->end_date->format('d M Y') }}</p>
                        </div>
                        <div class="text-sm text-gray-600">Admin: {{ $tournament->creator?->name ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    'Peserta Approved' => $summary['approvedParticipants'],
                    'Total Match' => $summary['totalMatches'],
                    'Match Selesai' => $summary['finishedMatches'],
                    'Belum Selesai' => $summary['unfinishedMatches'],
                    'Total Gol' => $summary['totalGoals'],
                    'Kartu Kuning' => $summary['yellowCards'],
                    'Kartu Merah' => $summary['redCards'],
                    'Status' => $tournament->status,
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
                        <h3 class="mb-4 text-base font-semibold text-gray-900">Klasemen</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500">#</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500">Klub</th>
                                        <th class="px-4 py-3 text-center font-medium text-gray-500">Main</th>
                                        <th class="px-4 py-3 text-center font-medium text-gray-500">SG</th>
                                        <th class="px-4 py-3 text-center font-medium text-gray-500">Poin</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse ($standings as $standing)
                                        <tr>
                                            <td class="px-4 py-3 text-gray-700">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3 font-medium text-gray-900">{{ $standing->club?->name }}</td>
                                            <td class="px-4 py-3 text-center text-gray-700">{{ $standing->played }}</td>
                                            <td class="px-4 py-3 text-center text-gray-700">{{ $standing->goal_difference }}</td>
                                            <td class="px-4 py-3 text-center font-semibold text-gray-900">{{ $standing->points }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">Klasemen belum tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-base font-semibold text-gray-900">Peserta Approved</h3>
                        <div class="space-y-3">
                            @forelse ($approvedRegistrations as $registration)
                                <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div class="font-medium text-gray-900">{{ $registration->club?->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $registration->club?->city ?? '-' }}</div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada peserta approved.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-base font-semibold text-gray-900">Top Scorer</h3>
                        <div class="space-y-3">
                            @forelse ($topScorers as $stat)
                                <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $stat->player?->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $stat->player?->club?->name }}</div>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $stat->goals }}</div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada data gol.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-base font-semibold text-gray-900">Top Assist</h3>
                        <div class="space-y-3">
                            @forelse ($topAssists as $stat)
                                <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $stat->player?->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $stat->player?->club?->name }}</div>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $stat->assists }}</div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada data assist.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-base font-semibold text-gray-900">Kartu</h3>
                        <div class="space-y-3">
                            @forelse ($cardLeaders as $stat)
                                <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $stat->player?->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $stat->player?->club?->name }}</div>
                                    </div>
                                    <div class="text-sm text-gray-700">{{ $stat->yellow_cards }} K � {{ $stat->red_cards }} M</div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada data kartu.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-base font-semibold text-gray-900">Hasil Terbaru</h3>
                        <div class="space-y-3">
                            @forelse ($recentResults as $match)
                                <div class="flex items-center justify-between gap-4 border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $match->homeClub?->name }} vs {{ $match->awayClub?->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $match->match_date->format('d M Y') }}</div>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $match->result?->home_score }} - {{ $match->result?->away_score }}</div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada hasil pertandingan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-base font-semibold text-gray-900">Match Belum Selesai</h3>
                        <div class="space-y-3">
                            @forelse ($unfinishedMatches as $match)
                                <div class="flex items-center justify-between gap-4 border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $match->homeClub?->name }} vs {{ $match->awayClub?->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $match->match_date->format('d M Y H:i') }} � {{ $match->venue ?? '-' }}</div>
                                    </div>
                                    <span class="text-sm text-gray-700">{{ $match->status }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Semua match sudah selesai.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
