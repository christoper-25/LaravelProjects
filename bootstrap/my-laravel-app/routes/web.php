<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\RiderAuthController;

Route::get('/rider/login', [RiderAuthController::class, 'showLoginForm']);
Route::post('/rider/login', [RiderAuthController::class, 'login']);
Route::get('/rider/dashboard', [RiderAuthController::class, 'dashboard']);
Route::get('/rider/logout', [RiderAuthController::class, 'logout']);
