<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Jadwal Pertandingan</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                

                    <x-form-errors />
                    @method('PUT')

                    @include('matches.partials.form', ['match' => $match])

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('matches.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

