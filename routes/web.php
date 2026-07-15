<?php

use App\Http\Controllers\ClubController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FootballMatchController;
use App\Http\Controllers\MatchResultController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PlayerStatisticController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TournamentRegistrationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'redirect'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/super-admin/dashboard', [DashboardController::class, 'superAdmin'])
    ->middleware(['auth', 'role:Super Admin'])
    ->name('super-admin.dashboard');

Route::get('/tournament/dashboard', [DashboardController::class, 'tournamentAdmin'])
    ->middleware(['auth', 'role:Admin Turnamen'])
    ->name('tournament.dashboard');

Route::get('/club/dashboard', [DashboardController::class, 'clubAdmin'])
    ->middleware(['auth', 'role:Admin Klub'])
    ->name('club.dashboard');

Route::resource('users', UserController::class)
    ->except(['show'])
    ->middleware(['auth', 'role:Super Admin']);

Route::resource('clubs', ClubController::class)
    ->except(['show'])
    ->middleware(['auth', 'role:Super Admin|Admin Klub']);

Route::resource('players', PlayerController::class)
    ->except(['show'])
    ->middleware(['auth', 'role:Super Admin|Admin Klub']);

Route::resource('coaches', CoachController::class)
    ->except(['show'])
    ->middleware(['auth', 'role:Super Admin|Admin Klub']);

Route::resource('tournaments', TournamentController::class)
    ->middleware(['auth', 'role:Super Admin|Admin Turnamen']);

Route::post('/tournaments/{tournament}/registrations', [TournamentRegistrationController::class, 'store'])
    ->middleware(['auth', 'role:Super Admin|Admin Turnamen'])
    ->name('tournaments.registrations.store');

Route::patch('/tournament-registrations/{registration}', [TournamentRegistrationController::class, 'update'])
    ->middleware(['auth', 'role:Super Admin|Admin Turnamen'])
    ->name('tournament-registrations.update');

Route::delete('/tournament-registrations/{registration}', [TournamentRegistrationController::class, 'destroy'])
    ->middleware(['auth', 'role:Super Admin|Admin Turnamen'])
    ->name('tournament-registrations.destroy');

Route::resource('matches', FootballMatchController::class)
    ->except(['show'])
    ->middleware(['auth', 'role:Super Admin|Admin Turnamen']);

Route::patch('/matches/{match}/result', [MatchResultController::class, 'update'])
    ->middleware(['auth', 'role:Super Admin|Admin Turnamen'])
    ->name('matches.result.update');

Route::resource('statistics', PlayerStatisticController::class)
    ->except(['show'])
    ->middleware(['auth', 'role:Super Admin|Admin Turnamen']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
