<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

/* ----- EMAIL VERIFICATION ----- */

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
     
        return redirect('/');
    })->middleware(['auth', 'signed'])->name('verification.verify');
    
    Route::post('/email/verification-notification', function (Request $request) {

        if($request->user()->hasVerifiedEmail()) {
            return redirect('/');
        }

        $request->user()->sendEmailVerificationNotification();
     
        return back()->with('message', 'Verification link sent!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
    
    Route::get('/email/verify', function () {
            
        $user = User::find(Auth::id());

        if($user->hasVerifiedEmail()) {
            return redirect('/');
        }

        return view('auth.verify-email');
    })->middleware('auth')->name('verification.notice');