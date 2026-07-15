<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Admin Klub</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (! $club)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Klub belum dibuat</h3>
                        <p class="mt-2 text-sm text-gray-600">Buat data klub terlebih dahulu agar dashboard pemain, pelatih, turnamen, dan jadwal bisa tampil.</p>
                        <a href="{{ route('clubs.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Buat Klub
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $club->name }}</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ $club->city ?? 'Kota belum diisi' }} · Berdiri {{ $club->founded_year ?? '-' }}</p>
                            </div>
                            <a href="{{ route('clubs.edit', $club) }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Edit Klub
                            </a>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-5">
                            <div class="text-sm font-medium text-gray-500">Pemain</div>
                            <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $club->players_count }}</div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-5">
                            <div class="text-sm font-medium text-gray-500">Pelatih</div>
                            <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $club->coaches_count }}</div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-5">
                            <div class="text-sm font-medium text-gray-500">Pendaftaran Turnamen</div>
                            <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $club->registrations_count }}</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900">Pemain</h3>
                                <a href="{{ route('players.create') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Tambah</a>
                            </div>
                            <div class="space-y-3">
                                @forelse ($players as $player)
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                        <div class="font-medium text-gray-900">{{ $player->name }}</div>
                                        <div class="text-sm text-gray-500">#{{ $player->jersey_number }} · {{ $player->position }}</div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Belum ada pemain.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900">Pelatih</h3>
                                <a href="{{ route('coaches.create') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Tambah</a>
                            </div>
                            <div class="space-y-3">
                                @forelse ($coaches as $coach)
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                        <div class="font-medium text-gray-900">{{ $coach->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $coach->license ?? 'Lisensi belum diisi' }}</div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Belum ada pelatih.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="mb-4 text-base font-semibold text-gray-900">Status Turnamen</h3>
                            <div class="space-y-3">
                                @forelse ($registrations as $registration)
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $registration->tournament?->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $registration->tournament?->format }}</div>
                                        </div>
                                        <span class="text-sm text-gray-700">{{ $registration->status }}</span>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Belum ada pendaftaran turnamen.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="mb-4 text-base font-semibold text-gray-900">Top Pemain</h3>
                            <div class="space-y-3">
                                @forelse ($topPlayers as $stat)
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                        <div class="font-medium text-gray-900">{{ $stat->player?->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $stat->goals }} gol · {{ $stat->assists }} assist</div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Belum ada statistik pemain.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-base font-semibold text-gray-900">Jadwal Klub</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500">Turnamen</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500">Pertandingan</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500">Waktu</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500">Venue</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse ($upcomingMatches as $match)
                                        <tr>
                                            <td class="px-4 py-3 text-gray-700">{{ $match->tournament?->name }}</td>
                                            <td class="px-4 py-3 font-medium text-gray-900">{{ $match->homeClub?->name }} vs {{ $match->awayClub?->name }}</td>
                                            <td class="px-4 py-3 text-gray-700">{{ $match->match_date->format('d M Y H:i') }}</td>
                                            <td class="px-4 py-3 text-gray-700">{{ $match->venue ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada jadwal mendatang.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
