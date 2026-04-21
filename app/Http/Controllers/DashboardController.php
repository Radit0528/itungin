<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Target; // Import model Target
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $now = Carbon::now();

        // 1. Ambil saldo user langsung (Total Kekayaan)
        $totalKekayaan = $user->saldo ?? 0;

        // 2. Hitung Pemasukan & Pengeluaran Bulan Ini saja untuk ringkasan kartu
        $pemasukanBulanIni = Transaction::where('user_id', $userId)
            ->where('tipe_transaksi', 'pemasukan')
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->sum('jumlah');

        $pengeluaranBulanIni = Transaction::where('user_id', $userId)
            ->where('tipe_transaksi', 'pengeluaran')
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->sum('jumlah');

        // 3. Ambil 4 Aktifitas Transaksi Terakhir
        $aktifitasTerbaru = Transaction::where('user_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // 4. Siapkan Data Grafik (6 Bulan Terakhir)
        $chartLabels = [];
        $chartDataActual = [];
        $chartDataTarget = []; // Inisialisasi array untuk data target
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chartLabels[] = strtoupper($date->translatedFormat('M')); // Format: JAN, FEB, MAR
            
            // Hitung Pemasukan Bulanan (Actual)
            $pemasukanBulan = Transaction::where('user_id', $userId)
                ->where('tipe_transaksi', 'pemasukan')
                ->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('jumlah');

            // Hitung Total Target Bulanan (Berdasarkan tanggal_target)
            $targetBulan = Target::where('user_id', $userId)
                ->whereMonth('tanggal_target', $date->month)
                ->whereYear('tanggal_target', $date->year)
                ->sum('target_jumlah');

            // Konversi ke angka jutaan agar skala grafik lebih rapi (misal: 1.500.000 menjadi 1.5)
            $chartDataActual[] = $pemasukanBulan / 1000000;
            $chartDataTarget[] = $targetBulan / 1000000;
        }

        return view('dashboard', compact(
            'totalKekayaan', 
            'pemasukanBulanIni', 
            'pengeluaranBulanIni', 
            'aktifitasTerbaru',
            'chartLabels',
            'chartDataActual',
            'chartDataTarget' // Variabel target sekarang ikut dikirim ke view
        ));
    }
}