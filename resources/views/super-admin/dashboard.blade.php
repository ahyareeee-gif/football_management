<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Super Admin</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    'Users' => $totals['users'],
                    'User Pending' => $totals['pendingUsers'],
                    'Klub Pending' => $totals['pendingClubs'],
                    'Turnamen' => $totals['tournaments'],
                    'Klub' => $totals['clubs'],
                    'Pemain' => $totals['players'],
                    'Pelatih' => $totals['coaches'],
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
                            <h3 class="text-base font-semibold text-gray-900">Club Pending Approval</h3>
                            <a href="{{ route('clubs.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Kelola Club</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($pendingClubs as $club)
                                <div class="flex items-center justify-between gap-4 border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $club->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $club->city ?? '-' }} · {{ $club->user?->name ?? 'Owner belum ada' }}</div>
                                    </div>
                                    <a href="{{ route('clubs.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Review</a>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Tidak ada club pending.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-base font-semibold text-gray-900">User Terbaru</h3>
                            <a href="{{ route('users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Kelola User</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($latestUsers as $user)
                                <div class="flex items-center justify-between gap-4 border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }} · {{ $user->roles->pluck('name')->join(', ') ?: 'Belum ada role' }}</div>
                                    </div>
                                    <span class="text-sm text-gray-700">{{ $user->status }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada user.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-base font-semibold text-gray-900">Turnamen Terbaru</h3>
                            <a href="{{ route('tournaments.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Lihat Semua</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($latestTournaments as $tournament)
                                <div class="flex items-center justify-between gap-4 border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $tournament->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $tournament->format }} · {{ $tournament->approved_registrations_count }}/{{ $tournament->registrations_count }} peserta approved · {{ $tournament->matches_count }} jadwal · {{ $tournament->status }}</div>
                                    </div>
                                    <a href="{{ route('tournaments.show', $tournament) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Detail</a>
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
                            <h3 class="text-base font-semibold text-gray-900">Butuh Input Skor</h3>
                            <a href="{{ route('matches.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Kelola Skor</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($matchesNeedingScores as $match)
                                <div class="flex items-center justify-between gap-4 border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $match->homeClub?->name }} vs {{ $match->awayClub?->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $match->tournament?->name }} · {{ $match->match_date->format('d M Y H:i') }}</div>
                                    </div>
                                    <span class="text-sm text-gray-700">{{ $match->status }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Tidak ada pertandingan yang perlu input skor.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-base font-semibold text-gray-900">Jadwal Mendatang</h3>
                            <a href="{{ route('matches.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Lihat Semua</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($upcomingMatches as $match)
                                <div class="border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div class="font-medium text-gray-900">{{ $match->homeClub?->name }} vs {{ $match->awayClub?->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $match->tournament?->name }} · {{ $match->match_date->format('d M Y H:i') }} · {{ $match->venue ?? '-' }}</div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada jadwal mendatang.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-base font-semibold text-gray-900">Hasil Terakhir</h3>
                        <div class="space-y-3">
                            @forelse ($recentResults as $match)
                                <div class="flex items-center justify-between gap-4 border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $match->homeClub?->name }} vs {{ $match->awayClub?->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $match->tournament?->name }} · {{ $match->match_date->format('d M Y') }}</div>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $match->result?->home_score }} - {{ $match->result?->away_score }}</div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada hasil pertandingan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
