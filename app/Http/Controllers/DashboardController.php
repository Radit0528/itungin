<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $now = Carbon::now();

        // 1. Ambil saldo user langsung
        $totalKekayaan = $user->saldo ?? 0;

        // 2. Hitung Pemasukan & Pengeluaran Bulan Ini saja
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

        // 3. Ambil 4 Aktifitas Terakhir
        $aktifitasTerbaru = Transaction::where('user_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // 4. Siapkan Data Grafik (6 Bulan Terakhir)
        $chartLabels = [];
        $chartDataActual = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chartLabels[] = strtoupper($date->translatedFormat('M')); // Format: JAN, FEB, MAR
            
            $pemasukanBulan = Transaction::where('user_id', $userId)
                ->where('tipe_transaksi', 'pemasukan')
                ->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('jumlah');

            // Kita bagi 1.000.000 agar angka di grafik lebih rapi (misal Rp 20.000.000 jadi angka 20)
            $chartDataActual[] = $pemasukanBulan / 1000000; 
        }

        return view('dashboard', compact(
            'totalKekayaan', 
            'pemasukanBulanIni', 
            'pengeluaranBulanIni', 
            'aktifitasTerbaru',
            'chartLabels',
            'chartDataActual'
        ));
    }
}