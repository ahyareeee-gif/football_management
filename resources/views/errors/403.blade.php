<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Akses Ditolak</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-center">
                    <div class="text-sm font-semibold uppercase tracking-wide text-gray-500">403</div>
                    <h1 class="mt-2 text-2xl font-semibold text-gray-900">Kamu tidak punya akses ke halaman ini.</h1>
                    <p class="mt-3 text-sm text-gray-600">
                        Halaman atau data yang kamu buka dibatasi berdasarkan role dan kepemilikan data.
                    </p>
                    <a href="{{ route('dashboard') }}" class="mt-6 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
