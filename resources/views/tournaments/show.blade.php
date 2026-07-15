<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Turnamen</h2>
            <a href="{{ route('tournaments.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Kembali</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $tournament->name }}</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ $tournament->description ?? 'Belum ada deskripsi.' }}</p>
                        </div>
                        <a href="{{ route('tournaments.edit', $tournament) }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Edit Turnamen
                        </a>
                    </div>

                    <dl class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-4">
                        <div class="rounded-md border border-gray-200 p-4">
                            <dt class="text-xs font-medium uppercase text-gray-500">Tanggal</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $tournament->start_date->format('d M Y') }} - {{ $tournament->end_date->format('d M Y') }}</dd>
                        </div>
                        <div class="rounded-md border border-gray-200 p-4">
                            <dt class="text-xs font-medium uppercase text-gray-500">Format</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $tournament->format }}</dd>
                        </div>
                        <div class="rounded-md border border-gray-200 p-4">
                            <dt class="text-xs font-medium uppercase text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $tournament->status }}</dd>
                        </div>
                        <div class="rounded-md border border-gray-200 p-4">
                            <dt class="text-xs font-medium uppercase text-gray-500">Pendaftar</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $tournament->registrations->count() }} klub</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form action="{{ route('tournaments.registrations.store', $tournament) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf

                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Daftarkan Klub</h3>
                            <p class="mt-1 text-sm text-gray-600">Pilih klub yang belum terdaftar pada turnamen ini.</p>
                        </div>

                        <div>
                            <label for="club_id" class="block text-sm font-medium text-gray-700">Klub</label>
                            <select id="club_id" name="club_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih klub</option>
                                @foreach ($clubs as $club)
                                    <option value="{{ $club->id }}" @selected(old('club_id') == $club->id)>{{ $club->name }}</option>
                                @endforeach
                            </select>
                            @error('club_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="payment_proof" class="block text-sm font-medium text-gray-700">Bukti Pembayaran</label>
                            <input id="payment_proof" type="file" name="payment_proof" accept="image/*,.pdf" class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-gray-800 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-gray-700">
                            @error('payment_proof') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="inline-flex w-full items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700" @disabled($clubs->isEmpty())>
                            Tambah Pendaftar
                        </button>
                    </form>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg lg:col-span-2">
                    <div class="p-6 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Klub</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Kota</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($tournament->registrations as $registration)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $registration->club?->name }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $registration->club?->city ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-700">
                                            <form action="{{ route('tournament-registrations.update', $registration) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    @foreach (['Pending', 'Approved', 'Rejected'] as $status)
                                                        <option value="{{ $status }}" @selected($registration->status === $status)>{{ $status }}</option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            @if ($registration->payment_proof)
                                                <a href="{{ Storage::url($registration->payment_proof) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">Lihat bukti</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <form action="{{ route('tournament-registrations.destroy', $registration) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini? Data yang terkait bisa ikut terhapus.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada klub yang terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-900">Jadwal dan Hasil</h3>
                        <a href="{{ route('matches.create') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Tambah Jadwal</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Tanggal</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Pertandingan</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Venue</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Skor</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($matches as $match)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-700">{{ $match->match_date->format('d M Y H:i') }}</td>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $match->homeClub?->name }} vs {{ $match->awayClub?->name }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $match->venue ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $match->status }}</td>
                                        <td class="px-4 py-3 text-gray-700">
                                            @if ($match->result)
                                                {{ $match->result->home_score }} - {{ $match->result->away_score }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada jadwal pertandingan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

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
                                    <th class="px-4 py-3 text-center font-medium text-gray-500">M</th>
                                    <th class="px-4 py-3 text-center font-medium text-gray-500">S</th>
                                    <th class="px-4 py-3 text-center font-medium text-gray-500">K</th>
                                    <th class="px-4 py-3 text-center font-medium text-gray-500">GM</th>
                                    <th class="px-4 py-3 text-center font-medium text-gray-500">GK</th>
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
                                        <td class="px-4 py-3 text-center text-gray-700">{{ $standing->win }}</td>
                                        <td class="px-4 py-3 text-center text-gray-700">{{ $standing->draw }}</td>
                                        <td class="px-4 py-3 text-center text-gray-700">{{ $standing->lose }}</td>
                                        <td class="px-4 py-3 text-center text-gray-700">{{ $standing->goals_for }}</td>
                                        <td class="px-4 py-3 text-center text-gray-700">{{ $standing->goals_against }}</td>
                                        <td class="px-4 py-3 text-center text-gray-700">{{ $standing->goal_difference }}</td>
                                        <td class="px-4 py-3 text-center font-semibold text-gray-900">{{ $standing->points }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-4 py-6 text-center text-gray-500">Klasemen akan muncul setelah skor pertandingan disimpan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-base font-semibold text-gray-900">Top Scorer</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500">Pemain</th>
                                        <th class="px-4 py-3 text-center font-medium text-gray-500">Gol</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse ($topScorers as $stat)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="font-medium text-gray-900">{{ $stat->player?->name }}</div>
                                                <div class="text-gray-500">{{ $stat->player?->club?->name }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-center font-semibold text-gray-900">{{ $stat->goals }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-4 py-6 text-center text-gray-500">Belum ada data gol pemain.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-base font-semibold text-gray-900">Top Assist</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500">Pemain</th>
                                        <th class="px-4 py-3 text-center font-medium text-gray-500">Assist</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse ($topAssists as $stat)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="font-medium text-gray-900">{{ $stat->player?->name }}</div>
                                                <div class="text-gray-500">{{ $stat->player?->club?->name }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-center font-semibold text-gray-900">{{ $stat->assists }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-4 py-6 text-center text-gray-500">Belum ada data assist pemain.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>






