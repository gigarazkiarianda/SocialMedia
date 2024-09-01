<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BiodataController;

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

// Route untuk halaman beranda
Route::get('/', function () {
    return view('welcome');
});

// Route untuk halaman login
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');

// Proses login
Route::post('login', [AuthController::class, 'login']);

// Route untuk halaman register
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');

// Proses register
Route::post('register', [AuthController::class, 'register']);

// Proses logout
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Route untuk dashboard
Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

