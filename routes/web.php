<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    
    Route::resource('tasks', TaskController::class);
    Route::resource('tasks.comments', CommentController::class)->only([
        'store', 'update', 'destroy'
    ]);
    Route::resource('tasks.members', MembersController::class)->only([
        'store', 'destroy'
    ]);
    
    Route::get('/user/{id}/tasks/common', [TaskController::class, 'userCommon']);
    Route::get('/user/{id}/tasks/shared', [TaskController::class, 'userShared']);
    Route::get('/user/{id}/tasks', [TaskController::class, 'userIndex']);

    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::get('/profile', [UserController::class, 'profile']);

    Route::get('/change-password', [UserController::class, 'edit']);
    Route::patch('/change-password', [UserController::class, 'update']);

    Route::get('/invite/{id}/accept', [InvitationController::class, 'accept']);
    Route::delete('/invite/{id}/decline', [InvitationController::class, 'decline']);
    Route::delete('/notification/{id}/clear', [NotificationController::class, 'clear']);

});

require __DIR__.'/auth.php';