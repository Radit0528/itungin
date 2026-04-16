<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatController;
// Import controller lainnya di sini nanti
// use App\Http\Controllers\Api\AuthController;
// use App\Http\Controllers\Api\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sini adalah tempat kamu mendaftarkan rute API untuk aplikasi kamu.
| Rute-rute ini dimuat oleh RouteServiceProvider dan semuanya akan
| memiliki prefix "api" secara otomatis.
|
*/

// --- RUTE PUBLIK (Bisa diakses tanpa login) ---
Route::post('/login', function() {
    return response()->json(['message' => 'Halaman Login']);
});

// --- RUTE CHATBOT (Sementara di luar auth agar mudah test di Postman) ---
Route::post('/chat', [ChatController::class, 'sendMessage']);


// --- RUTE PRIVATE (Harus Login / Pakai Token Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Mendapatkan data user yang sedang login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Contoh rute untuk fitur lainnya nanti:
    // Route::apiResource('transactions', TransactionController::class);
    // Route::apiResource('goals', GoalController::class);
    
});