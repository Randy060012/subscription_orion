<?php

use App\Http\Controllers\Api\SubscriptionCheckController;
use App\Http\Controllers\Api\TicketApiController;
use App\Http\Middleware\AuthenticateAgenceApi;
use App\Http\Middleware\CheckAgenceHeaderForTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware([AuthenticateAgenceApi::class])->group(function () {
    Route::post('/v1/subscription/check', [SubscriptionCheckController::class, 'checkStatus']);

    Route::post('/v1/subscription/verify-agency', [SubscriptionCheckController::class, 'verifyAgency']);
});



Route::middleware([CheckAgenceHeaderForTicket::class])->prefix('v1/agence')->group(function () {
    // Création d'un ticket
    Route::post('/tickets', [TicketApiController::class, 'store']);
    // Récupération de la liste des tickets
    Route::get('/tickets', [TicketApiController::class, 'index']);
});
