<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationOtpMail;
use App\Models\EmailVerificationOtp;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class EmailVerificationOtpController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $otp = EmailVerificationOtp::where('user_id', $user->id)
            ->whereNull('verified_at')
            ->where('expires_at', '>=', now())
            ->latest()
            ->first();

        if (! $otp || ! Hash::check($request->string('code')->toString(), $otp->code_hash)) {
            throw ValidationException::withMessages([
                'code' => 'Kode OTP tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        $otp->update(['verified_at' => now()]);

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }

    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        if (! self::sendOtp($user)) {
            return back()->withErrors([
                'code' => 'Kode OTP gagal dikirim. Periksa konfigurasi SMTP atau coba lagi beberapa saat.',
            ]);
        }

        return back()->with('status', 'verification-code-sent');
    }

    public static function sendOtp($user): bool
    {
        EmailVerificationOtp::where('user_id', $user->id)
            ->whereNull('verified_at')
            ->update(['verified_at' => now()]);

        $code = (string) random_int(100000, 999999);

        EmailVerificationOtp::create([
            'user_id' => $user->id,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($user->email)->send(new EmailVerificationOtpMail($code));
        } catch (Throwable $exception) {
            Log::warning('Failed to send email verification OTP.', [
                'user_id' => $user->id,
                'exception' => $exception->getMessage(),
            ]);

            return false;
        }

        return true;
    }
}