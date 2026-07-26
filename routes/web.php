<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AgencesController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TarifsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SoubscriptionController;

Route::get('/login', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('pages/auth/login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// Routes Protégées (Nécessitent d'être connecté)
Route::middleware(['auth', 'prevent-back-history'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/agences', [AgencesController::class, 'index'])->name('index.agences');
    Route::post('/agences', [AgencesController::class, 'store'])->name('agencies.store');
    Route::get('/agences/{id}', [AgencesController::class, 'show'])->name('agencies.show');

    Route::get('/tarifs', [TarifsController::class, 'index'])->name('index.tarifs');
    Route::post('/tarifs', [TarifsController::class, 'store'])->name('tarifs.store');

    Route::get('/subscriptions', [SoubscriptionController::class, 'index'])->name('index.subscriptions');
    Route::post('/subscriptions', [SoubscriptionController::class, 'store'])->name('subscriptions.store');

    Route::get('/tickets', function () {
        return view('pages/tickets/index');
    })->name('index.tickets');

    Route::get('/profil', function () {
        return view('pages/profil/index');
    })->name('index.profil');
});
