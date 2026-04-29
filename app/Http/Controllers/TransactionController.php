<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // 1. Tampilkan halaman transaksi beserta datanya
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $userId = Auth::id();

        $saldo = $user->saldo ?? 0;

        $totalPemasukan = Transaction::where('user_id', $userId)->where('tipe_transaksi', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = Transaction::where('user_id', $userId)->where('tipe_transaksi', 'pengeluaran')->sum('jumlah');

        $filter = $request->query('filter', 'semua');
        $query = Transaction::where('user_id', $userId);

        if ($filter === 'pemasukan') {
            $query->where('tipe_transaksi', 'pemasukan');
        } elseif ($filter === 'pengeluaran') {
            $query->where('tipe_transaksi', 'pengeluaran');
        }

        $transactions = $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->get();

        return view('transaksi', compact('saldo', 'totalPemasukan', 'totalPengeluaran', 'transactions', 'filter'));
    }

    // 2. Simpan data transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'tipe_transaksi' => 'required|in:pemasukan,pengeluaran',
            'jumlah'         => 'required|numeric|min:1',
            'kategori'       => 'required|string|max:255',
            'deskripsi'      => 'required|string|max:255',
            'tanggal'        => 'required|date',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Cek saldo jika pengeluaran
        if ($request->tipe_transaksi === 'pengeluaran' && ($user->saldo ?? 0) < $request->jumlah) {
            return redirect()->back()->with('error', 'Saldo tidak cukup!');
        }

        // Gunakan Database Transaction agar data aman jika terjadi error di tengah jalan
        DB::transaction(function () use ($request, $user) {
            Transaction::create([
                'user_id'        => $user->id,
                'tipe_transaksi' => $request->tipe_transaksi,
                'jumlah'         => $request->jumlah,
                'kategori'       => $request->kategori,
                'deskripsi'      => $request->deskripsi,
                'tanggal'        => $request->tanggal,
            ]);

            // Update Saldo
            if ($request->tipe_transaksi === 'pemasukan') {
                $user->saldo += $request->jumlah;
            } else {
                $user->saldo -= $request->jumlah;
            }
            $user->save();
        });

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

        /** @var User $user */
        $user = Auth::user();
        $transaction = Transaction::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        DB::transaction(function () use ($request, $user, $transaction) {
            // 1. Kembalikan saldo ke kondisi sebelum transaksi lama dibuat (Revert)
            if ($transaction->tipe_transaksi === 'pemasukan') {
                $user->saldo -= $transaction->jumlah;
            } else {
                $user->saldo += $transaction->jumlah;
            }

            // 2. Terapkan saldo baru berdasarkan data input
            if ($request->tipe_transaksi === 'pemasukan') {
                $user->saldo += $request->jumlah;
            } else {
                $user->saldo -= $request->jumlah;
            }

            // 3. Simpan perubahan
            $transaction->update($request->all());
            $user->save();
        });

        return redirect()->route('transaksi')->with('success', 'Transaksi berhasil diperbarui!');
    }

    // 4. Hapus data transaksi
    public function destroy($id)
    {
        /** @var User $user */
        $user = Auth::user();
        $transaction = Transaction::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        DB::transaction(function () use ($user, $transaction) {
            // Kembalikan saldo saat transaksi dihapus
            if ($transaction->tipe_transaksi === 'pemasukan') {
                $user->saldo -= $transaction->jumlah;
            } else {
                $user->saldo += $transaction->jumlah;
            }

            $user->save();
            $transaction->delete();
        });

        return redirect()->route('transaksi')->with('success', 'Transaksi berhasil dihapus dan saldo diperbarui!');
    }
}