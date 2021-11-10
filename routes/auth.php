<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/* ----- REGISTRATION ----- */

//REGISTER PAGE
Route::get('/register', [RegisterController::class, 'create'])
        ->middleware('guest')
        ->name('register');

//HANDLE REGISTER
Route::post('/register', [RegisterController::class, 'store'])
        ->middleware('guest');

/* ----- LOGIN ----- */

//LOGIN PAGE
Route::get('/login', [LoginController::class, 'index'])
        ->middleware('guest')
        ->name('login');

//HANDLE LOGIN
Route::post('/login', [LoginController::class, 'store'])
        ->middleware('guest');

//LOGOUT
Route::post('/logout', [LoginController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');