<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // 1. Tampilkan halaman transaksi beserta datanya
    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::id();

        // Ambil saldo dari user
        $saldo = $user->saldo ?? 0;

        // Hitung total untuk filter (tetap dihitung semua untuk kartu ringkasan atas)
        $totalPemasukan = Transaction::where('user_id', $userId)->where('tipe_transaksi', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = Transaction::where('user_id', $userId)->where('tipe_transaksi', 'pengeluaran')->sum('jumlah');

        // 1. Tangkap parameter filter dari URL (default: 'semua')
        $filter = $request->query('filter', 'semua');

        // 2. Buat Query dasar
        $query = Transaction::where('user_id', $userId);

        // 3. Kelompokkan/Filter berdasarkan pilihan
        if ($filter === 'pemasukan') {
            $query->where('tipe_transaksi', 'pemasukan');
        } elseif ($filter === 'pengeluaran') {
            $query->where('tipe_transaksi', 'pengeluaran');
        }

        // 4. Eksekusi Query
        $transactions = $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->get();

        // Kirim variabel $filter ke view agar tombolnya bisa menyala sesuai pilihan
        return view('transaksi', compact('saldo', 'totalPemasukan', 'totalPengeluaran', 'transactions', 'filter'));
    }

    // 2. Simpan data transaksi baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tipe_transaksi' => 'required|in:pemasukan,pengeluaran',
            'jumlah'         => 'required|numeric|min:1',
            'kategori'       => 'required|string|max:255',
            'deskripsi'      => 'required|string|max:255',
            'tanggal'        => 'required|date',
        ]);

        // Ambil user yang login
        $user = Auth::user();

        // Jika pengeluaran, cek apakah saldo cukup
        if ($request->tipe_transaksi === 'pengeluaran') {
            if (($user->saldo ?? 0) < $request->jumlah) {
                return redirect()->route('transaksi')->with('error', 'Saldo tidak cukup untuk transaksi ini!');
            }
        }

        // Simpan ke database
        Transaction::create([
            'user_id'        => Auth::id(), // Assign ke user yang login
            'tipe_transaksi' => $request->tipe_transaksi,
            'jumlah'         => $request->jumlah,
            'kategori'       => $request->kategori,
            'deskripsi'      => $request->deskripsi,
            'tanggal'        => $request->tanggal,
        ]);

        // Update saldo user
        if ($request->tipe_transaksi === 'pemasukan') {
            // Pemasukan: tambahkan ke saldo
            $user->saldo = ($user->saldo ?? 0) + $request->jumlah;
        } else {
            // Pengeluaran: kurangi dari saldo
            $user->saldo = ($user->saldo ?? 0) - $request->jumlah;
        }
        $user->save();

        // Kembalikan ke halaman transaksi dengan pesan sukses
        return redirect()->route('transaksi')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    // 3. Update data transaksi
    public function update(Request $request, $id)
    {
        $request->validate([
            'tipe_transaksi' => 'required|in:pemasukan,pengeluaran',
            'jumlah'         => 'required|numeric|min:1',
            'kategori'       => 'required|string|max:255',
            'deskripsi'      => 'required|string|max:255',
            'tanggal'        => 'required|date',
        ]);

        // Cari transaksi berdasarkan ID dan pastikan itu milik user yang sedang login
        $transaction = Transaction::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        $transaction->update([
            'tipe_transaksi' => $request->tipe_transaksi,
            'jumlah'         => $request->jumlah,
            'kategori'       => $request->kategori,
            'deskripsi'      => $request->deskripsi,
            'tanggal'        => $request->tanggal,
        ]);

        return redirect()->route('transaksi')->with('success', 'Transaksi berhasil diperbarui!');
    }

    // 4. Hapus data transaksi
    public function destroy($id)
    {
        // Cari transaksi dan hapus
        $transaction = Transaction::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $transaction->delete();

        return redirect()->route('transaksi')->with('success', 'Transaksi berhasil dihapus!');
    }
}