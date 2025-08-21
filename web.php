<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\EventController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Auth::routes();

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::get('users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [VolunteerController::class, 'index'])->name('dashboard');
    Route::get('/profile', [VolunteerController::class, 'profile'])->name('profile');
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
});
