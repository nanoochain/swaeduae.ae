<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MobileApiController;

Route::post('login', [MobileApiController::class, 'login']);
Route::post('register', [MobileApiController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('events', [MobileApiController::class, 'events']);
    Route::post('events/register', [MobileApiController::class, 'registerForEvent']);
    Route::get('certificates', [MobileApiController::class, 'certificates']);
    Route::get('profile', [MobileApiController::class, 'profile']);
    Route::post('logout', [MobileApiController::class, 'logout']);
});
