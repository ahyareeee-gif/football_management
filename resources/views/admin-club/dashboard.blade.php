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
                        <p class="mt-2 text-sm text-gray-600">Buat data klub terlebih dahulu agar dashboard anggota, turnamen, dan jadwal bisa tampil.</p>
                        <a href="{{ route('clubs.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Buat Klub
                        </a>
                    </div>
                </div>
            @else
                @php
                    $clubStatusClass = match ($club->status) {
                        'approved' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        default => 'bg-yellow-100 text-yellow-800',
                    };

                    $clubStatusLabel = match ($club->status) {
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        default => 'Pending Approval',
                    };
                @endphp

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $club->name }}</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ $club->city ?? 'Kota belum diisi' }} � Berdiri {{ $club->founded_year ?? '-' }}</p>
                            </div>
                            <div class="flex flex-col items-start gap-2 md:items-end">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $clubStatusClass }}">{{ $clubStatusLabel }}</span>
                                <div class="flex gap-3">
                                    <a href="{{ route('clubs.edit', $club) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Edit Klub</a>
                                    @if ($club->isApproved())
                                        <a href="{{ route('club-members.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Kelola Anggota</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if (! $club->isApproved())
                    <div class="rounded-md bg-yellow-50 p-4 text-sm text-yellow-800">
                        @if ($club->status === 'rejected')
                            Club Anda ditolak. Perbarui data club lalu tunggu Super Admin meninjau ulang.
                        @else
                            Club Anda sedang menunggu persetujuan Super Admin. Fitur kelola anggota dan pendaftaran turnamen akan aktif setelah club disetujui.
                        @endif
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
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
                            <div class="text-sm font-medium text-gray-500">Staff</div>
                            <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $club->staff_count }}</div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-5">
                            <div class="text-sm font-medium text-gray-500">Pendaftaran</div>
                            <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $club->registrations_count }}</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900">Pemain</h3>
                                @if ($club->isApproved())
                                    <a href="{{ route('club-members.create', ['member_type' => 'player']) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Tambah</a>
                                @endif
                            </div>
                            <div class="space-y-3">
                                @forelse ($players as $player)
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                        <div class="font-medium text-gray-900">{{ $player->name }}</div>
                                        <div class="text-sm text-gray-500">#{{ $player->jersey_number }} � {{ $player->position }}</div>
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
                                @if ($club->isApproved())
                                    <a href="{{ route('club-members.create', ['member_type' => 'coach']) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Tambah</a>
                                @endif
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

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900">Staff</h3>
                                @if ($club->isApproved())
                                    <a href="{{ route('club-members.create', ['member_type' => 'staff']) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Tambah</a>
                                @endif
                            </div>
                            <div class="space-y-3">
                                @forelse ($staff as $member)
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                        <div class="font-medium text-gray-900">{{ $member->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $member->role }}</div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Belum ada staff.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="mb-4 text-base font-semibold text-gray-900">Turnamen Terbuka</h3>
                            <div class="space-y-3">
                                @forelse ($openTournaments as $tournament)
                                    <div class="flex items-center justify-between gap-4 border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $tournament->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $tournament->format }} � {{ $tournament->start_date->format('d M Y') }}</div>
                                        </div>
                                        <a href="{{ route('tournaments.show', $tournament) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Daftar</a>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Tidak ada turnamen terbuka yang bisa didaftari saat ini.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="mb-4 text-base font-semibold text-gray-900">Status Pendaftaran</h3>
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
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="mb-4 text-base font-semibold text-gray-900">Jadwal Klub</h3>
                            <div class="space-y-3">
                                @forelse ($upcomingMatches as $match)
                                    <div class="border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                        <div class="font-medium text-gray-900">{{ $match->homeClub?->name }} vs {{ $match->awayClub?->name }}</div>
                                        <div class="mt-1 text-sm text-gray-500">{{ $match->tournament?->name }} � {{ $match->match_date->format('d M Y H:i') }} � {{ $match->venue ?? '-' }}</div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Belum ada jadwal mendatang.</p>
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
                                        <div class="text-sm text-gray-500">{{ $stat->goals }} gol � {{ $stat->assists }} assist</div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Belum ada statistik pemain.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
