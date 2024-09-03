<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\NotificationController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return redirect()->route('login'); // Gantilah 'login' dengan nama route untuk halaman login Anda
});


Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/biodata/create', [DashboardController::class, 'create'])->name('biodata.create');
    Route::post('/biodata', [DashboardController::class, 'store'])->name('biodata.store');
    Route::get('/biodata/{id}/edit', [DashboardController::class, 'edit'])->name('biodata.edit');
    Route::put('/biodata/{id}', [DashboardController::class, 'update'])->name('biodata.update');

    // User search route
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/search', [UserController::class, 'search'])->name('user.search');
    Route::get('/user/{id}', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/my-profile', [UserController::class, 'myProfile'])->name('user.myprofile');
    Route::post('/follow/{id}', [UserController::class, 'follow'])->name('user.follow');
    Route::post('/unfollow/{id}', [UserController::class, 'unfollow'])->name('user.unfollow');
    Route::get('/followers', [UserController::class, 'followers'])->name('user.followers');
    Route::get('/following', [UserController::class, 'following'])->name('user.following');

    Route::post('/follow/{id}', [UserController::class, 'follow'])->name('user.follow');
    Route::get('/user/{id}/followers', [UserController::class, 'followers'])->name('user.followers');
    Route::get('/user/{id}/following', [UserController::class, 'following'])->name('user.following');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/follow', [NotificationController::class, 'follow'])->name('notifications.follow');
Route::post('/unfollow', [NotificationController::class, 'unfollow'])->name('notifications.unfollow');
});




