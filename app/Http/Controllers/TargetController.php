<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TargetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $targets = Target::where('user_id', Auth::id())->get();
        return view('targets.index', compact('targets', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_target' => 'required|string|max:255',
            'target_jumlah' => 'required|numeric|min:0',
            'tanggal_target' => 'required|date',
            'kategori' => 'required|string|max:255',
        ]);

        Target::create([
            'user_id' => Auth::id(),
            'nama_target' => $request->nama_target,
            'target_jumlah' => $request->target_jumlah,
            'tanggal_target' => $request->tanggal_target,
            'jumlah_terkumpul' => 0,
            'status' => 'aktif',
            'kategori' => $request->kategori,
        ]);

        return redirect()->route('targets.index')->with('success', 'Target berhasil ditambahkan!');
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_target' => 'required|string|max:255',
            'target_jumlah' => 'required|numeric|min:0',
            'tanggal_target' => 'required|date',
            'kategori' => 'required|string|max:255',
        ]);

        $target = Target::findOrFail($id);
        $target->update([
            'nama_target' => $request->nama_target,
            'target_jumlah' => $request->target_jumlah,
            'tanggal_target' => $request->tanggal_target,
            'kategori' => $request->kategori,
        ]);

        return redirect()->route('targets.index')->with('success', 'Target berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $target = Target::findOrFail($id);
        $target->delete();

        return redirect()->route('targets.index')->with('success', 'Target berhasil dihapus!');
    }

    public function addFund(Request $request, string $id)
    {
        $request->validate([
            'jumlah_fund' => 'required|numeric|min:0',
        ]);

        $target = Target::findOrFail($id);
        $user = Auth::user();
        $jumlahFund = $request->jumlah_fund;

        // Cek apakah saldo cukup
        if ($user->saldo < $jumlahFund) {
            return redirect()->route('targets.index')->with('error', 'Saldo tidak cukup untuk menambahkan dana ke target!');
        }

        // Kurangi saldo user
        $user->saldo -= $jumlahFund;
        $user->save();

        // Simpan sebagai transaksi pengeluaran
        Transaction::create([
            'user_id' => $user->id,
            'tipe_transaksi' => 'pengeluaran',
            'jumlah' => $jumlahFund,
            'kategori' => 'Target: ' . ($target->nama_target ?? 'Tabungan'),
            'deskripsi' => 'Menambahkan dana ke target: ' . ($target->nama_target ?? ''),
            'tanggal' => now(),
        ]);

        // Tambah jumlah terkumpul di target
        $target->jumlah_terkumpul += $jumlahFund;

        // Update status jika target tercapai
        if ($target->jumlah_terkumpul >= $target->target_jumlah) {
            $target->status = 'tercapai';
        }

        $target->save();

        return redirect()->route('targets.index')->with('success', 'Dana berhasil ditambahkan ke target!');
    }   
}
