<?php

use App\Http\Controllers\ClubController;
use App\Http\Controllers\ClubMemberController;
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
    ->middleware(['auth', 'verified', 'active'])
    ->name('dashboard');

Route::get('/super-admin/dashboard', [DashboardController::class, 'superAdmin'])
    ->middleware(['auth', 'verified', 'active', 'role:Super Admin'])
    ->name('super-admin.dashboard');

Route::get('/tournament/dashboard', [DashboardController::class, 'tournamentAdmin'])
    ->middleware(['auth', 'verified', 'active', 'role:Admin Turnamen'])
    ->name('tournament.dashboard');

Route::get('/club/dashboard', [DashboardController::class, 'clubAdmin'])
    ->middleware(['auth', 'verified', 'active', 'role:Admin Klub'])
    ->name('club.dashboard');

Route::resource('users', UserController::class)
    ->except(['show'])
    ->middleware(['auth', 'verified', 'active', 'role:Super Admin']);

Route::resource('clubs', ClubController::class)
    ->except(['show'])
    ->middleware(['auth', 'verified', 'active', 'role:Super Admin|Admin Klub']);

Route::patch('/clubs/{club}/approve', [ClubController::class, 'approve'])
    ->middleware(['auth', 'verified', 'active', 'role:Super Admin'])
    ->name('clubs.approve');

Route::patch('/clubs/{club}/reject', [ClubController::class, 'reject'])
    ->middleware(['auth', 'verified', 'active', 'role:Super Admin'])
    ->name('clubs.reject');

Route::resource('club-members', ClubMemberController::class)
    ->only(['index', 'create', 'store'])
    ->middleware(['auth', 'verified', 'active', 'role:Admin Klub']);
Route::resource('players', PlayerController::class)
    ->except(['show'])
    ->middleware(['auth', 'verified', 'active', 'role:Super Admin|Admin Klub']);

Route::resource('coaches', CoachController::class)
    ->except(['show'])
    ->middleware(['auth', 'verified', 'active', 'role:Super Admin|Admin Klub']);

Route::get('/tournaments/{tournament}/report', [TournamentController::class, 'report'])
    ->middleware(['auth', 'verified', 'active', 'role:Super Admin|Admin Turnamen|Admin Klub'])
    ->name('tournaments.report');
Route::resource('tournaments', TournamentController::class)
    ->middleware(['auth', 'verified', 'active', 'role:Super Admin|Admin Turnamen|Admin Klub']);

Route::post('/tournaments/{tournament}/registrations', [TournamentRegistrationController::class, 'store'])
    ->middleware(['auth', 'verified', 'active', 'role:Super Admin|Admin Turnamen|Admin Klub'])
    ->name('tournaments.registrations.store');

Route::patch('/tournament-registrations/{registration}', [TournamentRegistrationController::class, 'update'])
    ->middleware(['auth', 'verified', 'active', 'role:Super Admin|Admin Turnamen'])
    ->name('tournament-registrations.update');

Route::delete('/tournament-registrations/{registration}', [TournamentRegistrationController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'active', 'role:Super Admin|Admin Turnamen'])
    ->name('tournament-registrations.destroy');

Route::post('/tournaments/{tournament}/matches/generate', [FootballMatchController::class, 'generate'])
    ->middleware(['auth', 'verified', 'active', 'role:Super Admin|Admin Turnamen'])
    ->name('tournaments.matches.generate');
Route::resource('matches', FootballMatchController::class)
    ->except(['show'])
    ->middleware(['auth', 'verified', 'active', 'role:Super Admin|Admin Turnamen']);

Route::patch('/matches/{match}/result', [MatchResultController::class, 'update'])
    ->middleware(['auth', 'verified', 'active', 'role:Super Admin|Admin Turnamen'])
    ->name('matches.result.update');

Route::resource('statistics', PlayerStatisticController::class)
    ->except(['show'])
    ->middleware(['auth', 'verified', 'active', 'role:Super Admin|Admin Turnamen']);

Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';



