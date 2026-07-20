<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->status === 'active') {
            return $next($request);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->withErrors([
            'email' => match ($user->status) {
                'pending' => 'Akun Anda masih menunggu persetujuan admin.',
                'suspended' => 'Akun Anda sedang dinonaktifkan. Hubungi admin.',
                default => 'Akun Anda belum aktif.',
            },
        ]);
    }
}