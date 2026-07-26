<?php

use App\Http\Controllers\Api\SubscriptionCheckController;
use App\Http\Middleware\AuthenticateAgenceApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware([AuthenticateAgenceApi::class])->group(function () {
    Route::post('/v1/subscription/check', [SubscriptionCheckController::class, 'checkStatus']);
});
