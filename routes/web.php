<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AgencesController;
use App\Http\Controllers\Admin\TarifsController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/agences', [AgencesController::class, 'index'])->name('index.agences');
Route::post('/agences', [AgencesController::class, 'store'])->name('agencies.store');

Route::get('/tarifs', [TarifsController::class, 'index'])->name('index.tarifs');
Route::post('/tarifs', [TarifsController::class, 'store'])->name('tarifs.store');

Route::get('/subscriptions', function () {
    return view('pages/subscriptions/index');
})->name('index.subscriptions');

Route::get('/tickets', function () {
    return view('pages/tickets/index');
})->name('index.tickets');

Route::get('/profil', function () {
    return view('pages/profil/index');
})->name('index.profil');


Route::get('/login', function () {
    return view('pages/auth/login');
})->name('login');


Route::get('/register', function () {
    return view('pages/auth/register');
})->name('register');
