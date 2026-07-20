<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Turnamen</h2>
            @hasanyrole('Super Admin|Admin Turnamen')
                <a href="{{ route('tournaments.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Tambah Turnamen
                </a>
            @endhasanyrole
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
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Tanggal</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Format</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Pendaftar</th>
                                @role('Admin Klub')
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Status Club</th>
                                @endrole
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($tournaments as $tournament)
                                @php($clubRegistration = $tournament->registrations->first())
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $tournament->name }}</div>
                                        <div class="text-gray-500">{{ Str::limit($tournament->description, 55) }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">
                                        {{ $tournament->start_date->format('d M Y') }} - {{ $tournament->end_date->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $tournament->format }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $tournament->status }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $tournament->registrations_count }}</td>
                                    @role('Admin Klub')
                                        <td class="px-4 py-3 text-gray-700">{{ $clubRegistration?->status ?? 'Belum daftar' }}</td>
                                    @endrole
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('tournaments.show', $tournament) }}" class="text-gray-700 hover:text-gray-900">Detail</a>
                                        @hasanyrole('Super Admin|Admin Turnamen')
                                            <a href="{{ route('tournaments.edit', $tournament) }}" class="ml-3 text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('tournaments.destroy', $tournament) }}" method="POST" class="inline-block ml-3" onsubmit="return confirm('Yakin ingin menghapus data ini? Data yang terkait bisa ikut terhapus.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        @endhasanyrole
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada data turnamen.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>