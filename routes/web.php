<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClubController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'redirect'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::view('/super-admin/dashboard', 'super-admin.dashboard')
    ->middleware('auth');

Route::view('/tournament/dashboard', 'admin-tournament.dashboard')
    ->middleware('auth');

Route::view('/club/dashboard', 'admin-club.dashboard')
    ->middleware('auth');

Route::resource('users', UserController::class)
    ->except(['show'])
    ->middleware('auth');

Route::resource('clubs', ClubController::class)
    ->except(['show'])
    ->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
