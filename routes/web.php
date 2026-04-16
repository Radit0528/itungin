<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatController;

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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tambahkan Rute Transaksi di sini
    Route::get('/transaksi', function () {
        return view('transaksi');
    })->name('transaksi');

    // Rute Transaksi Baru
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi');
    Route::post('/transaksi', [TransactionController::class, 'store'])->name('transaksi.store');
    Route::put('/transaksi/{id}', [TransactionController::class, 'update'])->name('transaksi.update');
    Route::delete('/transaksi/{id}', [TransactionController::class, 'destroy'])->name('transaksi.destroy');

    // Rute Target
    Route::resource('targets', TargetController::class)->except(['show', 'edit']);
    Route::post('/targets/{id}/add-fund', [TargetController::class, 'addFund'])->name('targets.add-fund');

    // Rute Chatbot
    Route::get('/ai-assistant', function () {
        return view('chat');
    })->name('ai.assistant');
    Route::post('/chat-process', [ChatController::class, 'sendMessage'])->name('chat.process');

    // Rute Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    
});