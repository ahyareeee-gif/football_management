<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Admin Turnamen</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    'Turnamen Saya' => $totals['tournaments'],
                    'Pending Review' => $totals['pendingRegistrations'],
                    'Peserta Approved' => $totals['approvedRegistrations'],
                    'Match Belum Selesai' => $totals['unfinishedMatches'],
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
                            <h3 class="text-base font-semibold text-gray-900">Turnamen Saya</h3>
                            <a href="{{ route('tournaments.create') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Tambah</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($tournaments as $tournament)
                                <div class="flex items-center justify-between gap-4 border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $tournament->name }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ $tournament->approved_registrations_count }} approved dari {{ $tournament->registrations_count }} pendaftar · {{ $tournament->finished_matches_count }}/{{ $tournament->matches_count }} match selesai · {{ $tournament->status }}
                                        </div>
                                    </div>
                                    <a href="{{ route('tournaments.show', $tournament) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Kelola</a>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada turnamen yang dibuat.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-base font-semibold text-gray-900">Pendaftaran Pending</h3>
                        <div class="space-y-3">
                            @forelse ($pendingRegistrations as $registration)
                                <div class="flex items-center justify-between gap-4 border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $registration->club?->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $registration->tournament?->name }} · {{ $registration->contact_person ?? '-' }}</div>
                                    </div>
                                    <a href="{{ route('tournaments.show', $registration->tournament) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Review</a>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Tidak ada pendaftaran pending.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-base font-semibold text-gray-900">Siap Generate Jadwal</h3>
                        <div class="space-y-3">
                            @forelse ($scheduleReadyTournaments as $tournament)
                                <div class="flex items-center justify-between gap-4 border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $tournament->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $tournament->approved_registrations_count }} club approved · {{ $tournament->matches_count }} jadwal dibuat</div>
                                    </div>
                                    <a href="{{ route('tournaments.show', $tournament) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Buka</a>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada turnamen League dengan minimal dua peserta approved.</p>
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
                                <p class="text-sm text-gray-500">Tidak ada pertandingan yang perlu input skor saat ini.</p>
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
                            <a href="{{ route('matches.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Kelola Jadwal</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($upcomingMatches as $match)
                                <div class="border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div class="font-medium text-gray-900">{{ $match->homeClub?->name }} vs {{ $match->awayClub?->name }}</div>
                                    <div class="mt-1 text-sm text-gray-500">{{ $match->tournament?->name }} · {{ $match->match_date->format('d M Y H:i') }} · {{ $match->venue ?? '-' }}</div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada jadwal mendatang.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-base font-semibold text-gray-900">Hasil Terbaru</h3>
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
