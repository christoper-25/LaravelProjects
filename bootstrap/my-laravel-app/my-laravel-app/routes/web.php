<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiderAuthController;


// Redirect root to rider login
Route::get('/', function () {
    return redirect('/rider/login');
});

// Rider Authentication Routes
Route::get('/rider/login', [RiderAuthController::class, 'showLoginForm'])->name('rider.login');
Route::post('/rider/login', [RiderAuthController::class, 'login'])->name('rider.login.submit');
Route::get('/rider/dashboard', [RiderAuthController::class, 'dashboard'])->name('rider.dashboard');
Route::get('/rider/logout', [RiderAuthController::class, 'logout'])->name('rider.logout');
Route::get('/history', [RiderAuthController::class, 'history']);




