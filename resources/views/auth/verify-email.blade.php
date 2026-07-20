<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Masukkan kode OTP 6 digit yang sudah kami kirim ke email Anda. Kode ini berlaku selama 10 menit.
    </div>

    @if (session('status') == 'verification-code-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            Kode OTP baru sudah dikirim ke email Anda.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.verify') }}">
        @csrf

        <div>
            <x-input-label for="code" value="Kode OTP" />
            <x-text-input id="code" class="block mt-1 w-full text-center tracking-widest" type="text" name="code" :value="old('code')" required autofocus inputmode="numeric" pattern="[0-9]{6}" maxlength="6" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-between gap-4">
            <x-primary-button>
                Verifikasi Email
            </x-primary-button>
        </div>
    </form>

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Kirim ulang kode OTP
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>