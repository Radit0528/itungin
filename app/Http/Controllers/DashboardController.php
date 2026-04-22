<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Target;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $now = Carbon::now();

        // 1. Ambil saldo user saat ini
        $totalKekayaan = $user->saldo ?? 0;

        // 2. Hitung Ringkasan Bulan Ini
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

        // 3. Aktifitas Terakhir
        $aktifitasTerbaru = Transaction::where('user_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // ============================================================
        // 4. LOGIKA GRAFIK SALDO BERJALAN (1 BULAN)
        // ============================================================
        $chartLabels = [];
        $chartDataActual = [];
        $chartDataTarget = [];

        // Ambil total target bulan ini (untuk garis hijau)
        $targetBulanIni = Target::where('user_id', $userId)
            ->whereMonth('tanggal_target', $now->month)
            ->whereYear('tanggal_target', $now->year)
            ->sum('target_jumlah');

        $targetValue = $targetBulanIni / 1000000;

        // Cari Saldo Awal (Saldo sekarang dikurangi hasil bersih bulan ini)
        // Ini agar grafik mulai dari titik saldo kamu sebelum bulan ini dimulai
        $netBulanIni = $pemasukanBulanIni - $pengeluaranBulanIni;
        $saldoAwalBulan = ($totalKekayaan - $netBulanIni);

        $runningBalance = $saldoAwalBulan;

        $daysInMonth = $now->daysInMonth;

        // Kita ubah agar looping HANYA BERHENTI di tanggal hari ini
        $hariIni = $now->day;
        $allTransactions = Transaction::where('user_id', $userId)
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->get();

        for ($day = 1; $day <= $hariIni; $day++) {
            $chartLabels[] = $day;

            $pemasukanHariIni = $allTransactions->where('tipe_transaksi', 'pemasukan')
                ->filter(fn($trx) => Carbon::parse($trx->tanggal)->day == $day)->sum('jumlah');

            $pengeluaranHariIni = $allTransactions->where('tipe_transaksi', 'pengeluaran')
                ->filter(fn($trx) => Carbon::parse($trx->tanggal)->day == $day)->sum('jumlah');

            // Saldo bertambah jika ada pemasukan, berkurang jika ada pengeluaran
            $runningBalance += ($pemasukanHariIni - $pengeluaranHariIni);

            // Masukkan ke grafik (dalam satuan juta)
            $chartDataActual[] = $runningBalance / 1000000;
            $chartDataTarget[] = $targetValue;
        }

        return view('dashboard', compact(
            'totalKekayaan',
            'pemasukanBulanIni',
            'pengeluaranBulanIni',
            'aktifitasTerbaru',
            'chartLabels',
            'chartDataActual',
            'chartDataTarget'
        ));
    }
}
