<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute Default: Otomatis arahkan pengunjung ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// ==========================================
// RUTE PUBLIK (Tanpa Middleware Guest)
// ==========================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


// ==========================================
// RUTE TERPROTEKSI (Wajib Login)
// ==========================================
Route::middleware('auth')->group(function () {
    
    // Rute Dashboard: Memanggil resources/views/dashboard.blade.php
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rute Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
});