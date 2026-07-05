<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function redirect()
    {
        $user = auth()->user();

        if ($user->hasRole('Super Admin')) {
            return redirect('/super-admin/dashboard');
        }

        if ($user->hasRole('Admin Turnamen')) {
            return redirect('/tournament/dashboard');
        }

        if ($user->hasRole('Admin Klub')) {
            return redirect('/club/dashboard');
        }

        abort(403);
    }
}
