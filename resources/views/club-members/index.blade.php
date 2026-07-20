<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Anggota Club</h2>
            <a href="{{ route('club-members.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Tambah Anggota
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <div class="text-sm font-medium text-gray-500">Pemain</div>
                    <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $club->players_count }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <div class="text-sm font-medium text-gray-500">Pelatih</div>
                    <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $club->coaches_count }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <div class="text-sm font-medium text-gray-500">Staff</div>
                    <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $club->staff_count }}</div>
                </div>
            </div>

            @foreach ([
                'Pemain' => $players,
                'Pelatih' => $coaches,
                'Staff' => $staff,
            ] as $title => $members)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-base font-semibold text-gray-900">{{ $title }}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500">Nama</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500">Detail</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse ($members as $member)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    @if ($member->photo)
                                                        <img src="{{ Storage::url($member->photo) }}" alt="Foto {{ $member->name }}" class="h-10 w-10 rounded-md object-cover ring-1 ring-gray-200">
                                                    @else
                                                        <div class="flex h-10 w-10 items-center justify-center rounded-md bg-gray-100 text-xs font-semibold text-gray-500">{{ Str::substr($member->name, 0, 2) }}</div>
                                                    @endif
                                                    <span class="font-medium text-gray-900">{{ $member->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-gray-700">
                                                @if ($title === 'Pemain')
                                                    #{{ $member->jersey_number }} · {{ $member->position }}
                                                @elseif ($title === 'Pelatih')
                                                    {{ $member->license ?? 'Lisensi belum diisi' }}
                                                @else
                                                    {{ $member->role ?? 'Staff' }}
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-4 py-6 text-center text-gray-500">Belum ada {{ strtolower($title) }}.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>