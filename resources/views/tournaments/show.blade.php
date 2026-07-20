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
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('tournaments.report', $tournament) }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                                Laporan
                            </a>
                            @hasanyrole('Super Admin|Admin Turnamen')
                                <a href="{{ route('tournaments.edit', $tournament) }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Edit Turnamen
                                </a>
                            @endhasanyrole
                        </div>
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
                @role('Admin Klub')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <form action="{{ route('tournaments.registrations.store', $tournament) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                            @csrf

                            <div>
                                <h3 class="text-base font-semibold text-gray-900">Ajukan Pendaftaran</h3>
                                <p class="mt-1 text-sm text-gray-600">Daftarkan club Anda ke turnamen ini. Admin Turnamen akan meninjau pengajuan.</p>
                            </div>

                            @if ($clubRegistration)
                                <div class="rounded-md bg-gray-50 p-4 text-sm text-gray-700">
                                    Status pendaftaran club Anda: <span class="font-semibold">{{ $clubRegistration->status }}</span>
                                </div>
                            @else
                                <div>
                                    <label for="contact_person" class="block text-sm font-medium text-gray-700">Penanggung Jawab</label>
                                    <input id="contact_person" type="text" name="contact_person" value="{{ old('contact_person', auth()->user()->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('contact_person') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700">Nomor HP/WA</label>
                                    <input id="contact_phone" type="text" name="contact_phone" value="{{ old('contact_phone') }}" placeholder="Contoh: 08xxxxxxxxxx" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('contact_phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Catatan Pendaftaran</label>
                                    <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                                    @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="payment_proof" class="block text-sm font-medium text-gray-700">Bukti Pembayaran</label>
                                    <input id="payment_proof" type="file" name="payment_proof" accept="image/*,.pdf" class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-gray-800 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-gray-700">
                                    @error('payment_proof') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="registration_document" class="block text-sm font-medium text-gray-700">Dokumen Pendukung</label>
                                    <input id="registration_document" type="file" name="registration_document" accept="image/*,.pdf" class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-gray-800 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-gray-700">
                                    @error('registration_document') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <label class="flex items-start gap-3 rounded-md border border-gray-200 p-3 text-sm text-gray-700">
                                    <input type="checkbox" name="agreement_accepted" value="1" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked(old('agreement_accepted'))>
                                    <span>Saya memastikan data club benar, anggota club memenuhi aturan turnamen, dan menyetujui ketentuan turnamen.</span>
                                </label>
                                @error('agreement_accepted') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                                <button type="submit" class="inline-flex w-full items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Ajukan Pendaftaran
                                </button>
                            @endif
                        </form>
                    </div>
                @endrole

                <div class="space-y-6 {{ auth()->user()->hasRole('Admin Klub') ? 'lg:col-span-2' : 'lg:col-span-3' }}">
                    @foreach ([
                        'Pending' => ['title' => 'Pending Review', 'description' => 'Pengajuan yang masih perlu ditinjau Admin Turnamen.'],
                        'Approved' => ['title' => 'Approved Participants', 'description' => 'Club resmi yang sudah bisa masuk jadwal pertandingan.'],
                        'Rejected' => ['title' => 'Rejected', 'description' => 'Pengajuan yang ditolak atau perlu perbaikan.'],
                    ] as $registrationStatus => $group)
                        @php($registrations = $tournament->registrations->where('status', $registrationStatus))

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 overflow-x-auto">
                                <div class="mb-4 flex items-start justify-between gap-4">
                                    <div>
                                        <h3 class="text-base font-semibold text-gray-900">{{ $group['title'] }}</h3>
                                        <p class="mt-1 text-sm text-gray-600">{{ $group['description'] }}</p>
                                    </div>
                                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">{{ $registrations->count() }}</span>
                                </div>

                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-medium text-gray-500">Klub</th>
                                            <th class="px-4 py-3 text-left font-medium text-gray-500">Kota</th>
                                            <th class="px-4 py-3 text-left font-medium text-gray-500">PIC</th>
                                            <th class="px-4 py-3 text-left font-medium text-gray-500">Berkas</th>
                                            @hasanyrole('Super Admin|Admin Turnamen')
                                                <th class="px-4 py-3 text-right font-medium text-gray-500">Aksi</th>
                                            @endhasanyrole
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @forelse ($registrations as $registration)
                                            <tr>
                                                <td class="px-4 py-3 font-medium text-gray-900">{{ $registration->club?->name }}</td>
                                                <td class="px-4 py-3 text-gray-700">{{ $registration->club?->city ?? '-' }}</td>
                                                <td class="px-4 py-3 text-gray-700">
                                                    <div class="font-medium text-gray-900">{{ $registration->contact_person ?? '-' }}</div>
                                                    <div class="text-gray-500">{{ $registration->contact_phone ?? '-' }}</div>
                                                    @if ($registration->notes)
                                                        <div class="mt-1 text-xs text-gray-500">{{ Str::limit($registration->notes, 70) }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-gray-700 space-y-1">
                                                    @if ($registration->payment_proof)
                                                        <a href="{{ Storage::url($registration->payment_proof) }}" target="_blank" class="block text-indigo-600 hover:text-indigo-900">Bukti bayar</a>
                                                    @endif
                                                    @if ($registration->registration_document)
                                                        <a href="{{ Storage::url($registration->registration_document) }}" target="_blank" class="block text-indigo-600 hover:text-indigo-900">Dokumen</a>
                                                    @endif
                                                    @if (! $registration->payment_proof && ! $registration->registration_document)
                                                        -
                                                    @endif
                                                </td>
                                                @hasanyrole('Super Admin|Admin Turnamen')
                                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                                        <form action="{{ route('tournament-registrations.update', $registration) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            @method('PATCH')
                                                            <select name="status" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                                @foreach (['Pending', 'Approved', 'Rejected'] as $status)
                                                                    <option value="{{ $status }}" @selected($registration->status === $status)>{{ $status }}</option>
                                                                @endforeach
                                                            </select>
                                                        </form>
                                                        <form action="{{ route('tournament-registrations.destroy', $registration) }}" method="POST" class="ml-3 inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini? Data yang terkait bisa ikut terhapus.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                                        </form>
                                                    </td>
                                                @endhasanyrole
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">Tidak ada data.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Jadwal dan Hasil</h3>
                            <p class="mt-1 text-sm text-gray-600">Generate otomatis memakai club yang sudah Approved.</p>
                        </div>
                        @hasanyrole('Super Admin|Admin Turnamen')
                            <a href="{{ route('matches.create') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Tambah Manual</a>
                        @endhasanyrole
                    </div>

                    @hasanyrole('Super Admin|Admin Turnamen')
                        @php($approvedCount = $tournament->registrations->where('status', 'Approved')->count())
                        <form action="{{ route('tournaments.matches.generate', $tournament) }}" method="POST" class="mb-5 grid grid-cols-1 gap-4 rounded-md border border-gray-200 bg-gray-50 p-4 md:grid-cols-4">
                            @csrf
                            <div>
                                <label for="start_at" class="block text-sm font-medium text-gray-700">Mulai Jadwal</label>
                                <input id="start_at" type="datetime-local" name="start_at" value="{{ old('start_at') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('start_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="interval_days" class="block text-sm font-medium text-gray-700">Jeda Hari</label>
                                <input id="interval_days" type="number" name="interval_days" min="0" max="30" value="{{ old('interval_days', 1) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('interval_days') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="venue" class="block text-sm font-medium text-gray-700">Venue</label>
                                <input id="venue" type="text" name="venue" value="{{ old('venue') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('venue') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex items-end">
                                <button type="submit" @disabled($tournament->format !== 'League' || $approvedCount < 2) class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700 disabled:cursor-not-allowed disabled:bg-gray-300">
                                    Generate Jadwal
                                </button>
                            </div>
                            @if ($tournament->format !== 'League')
                                <p class="md:col-span-4 text-sm text-gray-600">Generate otomatis saat ini hanya tersedia untuk format League.</p>
                            @elseif ($approvedCount < 2)
                                <p class="md:col-span-4 text-sm text-gray-600">Minimal harus ada dua club Approved sebelum jadwal bisa digenerate.</p>
                            @endif
                        </form>
                    @endhasanyrole

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








