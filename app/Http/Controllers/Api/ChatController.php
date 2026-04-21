<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Target;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Sesi habis, silakan login ulang.'], 401);
            }

            // 1. Ambil semua kunci dari config/services.php
            // Pastikan kamu sudah menambahkan kunci ini di config (cek langkah di bawah)
            $apiKeys = config('services.gemini.keys');
            
            if (empty($apiKeys)) {
                return response()->json(['status' => 'error', 'message' => 'Tidak ada API Key yang tersedia.'], 500);
            }

            $systemPrompt = "Kamu adalah Itungin AI. Tugasmu membantu {$user->name} mencatat keuangan.
            Selalu gunakan status 'aktif' untuk target baru.
            Selalu sertakan kode JSON di AKHIR jawaban jika user ingin mencatat:

            1. TRANSAKSI: [ACTION_TRANSACTION]{\"tipe\":\"pengeluaran\",\"jumlah\":10000,\"kategori\":\"Makanan\",\"deskripsi\":\"Makan\"}[/ACTION]
            2. TARGET BARU: [ACTION_TARGET_NEW]{\"nama\":\"Motor\",\"jumlah\":5000000,\"kategori\":\"Kendaraan\"}[/ACTION]
            3. NABUNG KE TARGET: [ACTION_TARGET_UPDATE]{\"nama\":\"Motor\",\"jumlah\":100000}[/ACTION]
            
            Jawab dengan singkat, ramah, dan memotivasi.";

            $finalReply = null;
            $success = false;

            // 2. LOGIKA ROTASI API (Mencoba satu per satu jika limit)
            foreach ($apiKeys as $key) {
                if (!$key) continue;

                // Gunakan gemini-1.5-flash untuk kuota lebih besar (1500/hari)
                $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent?key={$key}", [
                    'contents' => [
                        ['role' => 'user', 'parts' => [['text' => $systemPrompt . "\nUser: " . $request->message]]]
                    ]
                ]);

                if ($response->successful()) {
                    $finalReply = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';
                    $success = true;
                    break; // Berhenti looping jika sukses
                }

                // Jika error 429 (Limit habis), lanjut ke Key berikutnya
                if ($response->status() == 429) {
                    continue;
                }

                // Jika error lain, langsung stop
                return response()->json(['status' => 'error', 'message' => 'Gemini Error: ' . $response->body()], $response->status());
            }

            if (!$success) {
                return response()->json(['status' => 'error', 'message' => 'Semua API Key sedang limit. Coba lagi nanti ya!'], 429);
            }

            // 3. PROSES SIMPAN DATABASE (Memanggil fungsi bantuan di bawah)
            $this->handleDatabaseActions($finalReply, $user);

            // 4. BERSIHKAN TEKS DARI KODE JSON
            $cleanReply = preg_replace('/\[ACTION_.*?\].*?\[\/ACTION\]/s', '', $finalReply);

            return response()->json([
                'status' => 'success',
                'reply' => trim($cleanReply)
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Laravel Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Fungsi Terpisah untuk Mengurus Database
     */
    private function handleDatabaseActions($reply, $user)
    {
        // A. Simpan Transaksi
        if (preg_match('/\[ACTION_TRANSACTION\](.*?)\[\/ACTION\]/s', $reply, $match)) {
            $data = json_decode($match[1], true);
            if ($data) {
                Transaction::create([
                    'user_id'        => $user->id,
                    'tipe_transaksi' => $data['tipe'],
                    'jumlah'         => $data['jumlah'],
                    'kategori'       => $data['kategori'] ?? 'Lainnya',
                    'deskripsi'      => $data['deskripsi'] ?? 'Catatan AI',
                    'tanggal'        => now(),
                ]);
            }
        }

        // B. Simpan Target Baru
        if (preg_match('/\[ACTION_TARGET_NEW\](.*?)\[\/ACTION\]/s', $reply, $match)) {
            $data = json_decode($match[1], true);
            if ($data) {
                Target::create([
                    'user_id'          => $user->id,
                    'nama_target'      => $data['nama'], 
                    'target_jumlah'    => $data['jumlah'],
                    'jumlah_terkumpul' => 0,
                    
                    // SOLUSI TANGGAL: Karena di tabel wajib ada tanggal, 
                    // kita kasih default hari ini + 1 tahun jika AI tidak kasih tanggal.
                    'tanggal_target'   => now()->addYear(), 
                    
                    // SOLUSI STATUS: Karena tabel kamu ENUM, kita paksa jadi 'aktif'.
                    // Apapun yang dikirim AI (in_progress, dll) tetap masuk sebagai 'aktif'.
                    'status'           => 'aktif', 
                    
                    'kategori'         => $data['kategori'] ?? 'Tabungan',
                ]);
            }
        }

        // C. Update Tabungan
        if (preg_match('/\[ACTION_TARGET_UPDATE\](.*?)\[\/ACTION\]/s', $reply, $match)) {
            $data = json_decode($match[1], true);
            if ($data) {
                $target = Target::where('user_id', $user->id)
                                ->where('nama_target', 'like', '%' . $data['nama'] . '%')
                                ->first();
                if ($target) {
                    $target->increment('jumlah_terkumpul', $data['jumlah']);
                }
            }
        }
    }
}