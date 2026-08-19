<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AgencesController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TarifsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SoubscriptionController;
use App\Http\Controllers\Admin\TicketController;

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
    Route::put('/agencies/{id}', [AgencesController::class, 'update'])->name('agencies.update');
    Route::delete('/agencies/{id}', [AgencesController::class, 'destroy'])->name('agencies.destroy');

    Route::get('/tarifs', [TarifsController::class, 'index'])->name('index.tarifs');
    Route::post('/tarifs', [TarifsController::class, 'store'])->name('tarifs.store');
    Route::get('/tarifs/{id}', [TarifsController::class, 'show'])->name('tarifs.show');
    Route::put('/tarifs/{id}', [TarifsController::class, 'update'])->name('tarifs.update');
    Route::delete('/tarifs/{id}', [TarifsController::class, 'destroy'])->name('tarifs.destroy');

    Route::get('/subscriptions', [SoubscriptionController::class, 'index'])->name('index.subscriptions');
    Route::post('/subscriptions', [SoubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/subscriptions/{id}', [SoubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::patch('/subscriptions/{id}/desactiver', [SoubscriptionController::class, 'desactiver'])->name('subscriptions.desactiver');

    Route::get('/tickets', [TicketController::class, 'index'])->name('index.tickets');
    // Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::patch('/tickets/{ticket}/resoudre', [TicketController::class, 'resoudre'])->name('tickets.resoudre');

    Route::get('/profil', function () {
        return view('pages/profil/index');
    })->name('index.profil');
});
