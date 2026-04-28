<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Target;
use App\Models\Chat; // Tambahkan ini

class ChatController extends Controller
{
    // app/Http/Controllers/Api/ChatController.php

public function index()
{
    $user = Auth::user();

    // Ambil history chat
    $history = Chat::where('user_id', $user->id)
                ->orderBy('created_at', 'asc')
                ->get();

    // Jika nama filenya ai-assistant.blade.php, pakai ini:
    return view('chat', compact('history'));
    
    // ATAU jika nama filenya chat.blade.php, pakai ini:
    // return view('chat', compact('history'));
}
  public function sendMessage(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Sesi habis, silakan login ulang.'], 401);
            }

            $apiKeys = config('services.gemini.keys');
            if (empty($apiKeys)) {
                return response()->json(['status' => 'error', 'message' => 'Tidak ada API Key tersedia.'], 500);
            }

            // 1. AMBIL DATA KEUANGAN REAL-TIME DARI DATABASE
            $saldo = $user->saldo ?? 0;
            $totalPengeluaran = Transaction::where('user_id', $user->id)
                                ->where('tipe_transaksi', 'pengeluaran')
                                ->sum('jumlah');
            $totalPemasukan = Transaction::where('user_id', $user->id)
                                ->where('tipe_transaksi', 'pemasukan')
                                ->sum('jumlah');
            
            // Ambil target yang sedang aktif
            $daftarTarget = Target::where('user_id', $user->id)
                            ->where('status', 'aktif')
                            ->get(['nama_target', 'target_jumlah', 'jumlah_terkumpul']);

            $teksTarget = $daftarTarget->map(function($t) {
                return "- {$t->nama_target}: Target Rp" . number_format($t->target_jumlah) . " (Terkumpul: Rp" . number_format($t->jumlah_terkumpul) . ")";
            })->implode("\n");

            // 2. AMBIL HISTORY CHAT (5 Pesan Terakhir)
            $history = Chat::where('user_id', $user->id)
                        ->latest()
                        ->limit(5)
                        ->get()
                        ->reverse();

            // 3. SUSUN FORMAT CONTENTS UNTUK GEMINI
            $contents = [];
            foreach ($history as $chat) {
                $contents[] = ['role' => 'user', 'parts' => [['text' => $chat->message]]];
                $contents[] = ['role' => 'model', 'parts' => [['text' => $chat->reply]]];
            }

            // 4. SYSTEM PROMPT DINAMIS (AI Memegang Laporan Keuangan)
            $systemPrompt = "Kamu adalah Itungin AI, asisten keuangan pribadi {$user->name}. 
            Gunakan data keuangan real-time berikut untuk memberikan saran yang akurat:

            DATA KEUANGAN USER:
            - Saldo Saat Ini: Rp" . number_format($saldo, 0, ',', '.') . "
            - Total Pemasukan: Rp" . number_format($totalPemasukan, 0, ',', '.') . "
            - Total Pengeluaran: Rp" . number_format($totalPengeluaran, 0, ',', '.') . "
            - Target Menabung Aktif: 
            " . ($teksTarget ?: 'Tidak ada target aktif.') . "

            TUGAS & ATURAN:
            1. Jika user bertanya 'Boleh beli tidak?', bandingkan harga barang dengan saldo. 
            2. Sarankan 'Tunda/Jangan' jika harga barang > 50% saldo atau jika ada target menabung yang lebih mendesak.
            3. JANGAN PERNAH gunakan format markdown seperti ```json.
            4. Selalu sertakan tag ACTION di akhir jika ingin mencatat:
               - [ACTION_TRANSACTION]{\"tipe\":\"pengeluaran\",\"jumlah\":10000,\"kategori\":\"...\",\"deskripsi\":\"...\"}[/ACTION]
               - [ACTION_TARGET_UPDATE]{\"nama\":\"...\",\"jumlah\":5000}[/ACTION]
            
            Jawab dengan ramah, cerdas secara finansial, dan jujur.";

            // Tambahkan pesan user saat ini
            $contents[] = ['role' => 'user', 'parts' => [['text' => $systemPrompt . "\n\nUser: " . $request->message]]];

            $finalReply = null;
            $success = false;

            // 5. LOGIKA ROTASI API
            foreach ($apiKeys as $key) {
                if (!$key) continue;

                $response = Http::post("[https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent?key=](https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent?key=){$key}", [
                    'contents' => $contents
                ]);

                if ($response->successful()) {
                    $finalReply = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';
                    $success = true;
                    break; 
                }

                if ($response->status() == 429) continue;
            }

            if (!$success) {
                return response()->json(['status' => 'error', 'message' => 'Semua API Key limit.'], 429);
            }

            // 6. PROSES ACTION DATABASE DULU (Pakai teks asli)
            $this->handleDatabaseActions($finalReply, $user);

            // 7. BERSIHKAN TEKS & SIMPAN HISTORY
            $cleanReply = preg_replace('/\[ACTION_.*?\].*?\[\/ACTION\]/s', '', $finalReply);
            $cleanReply = trim($cleanReply);

            Chat::create([
                'user_id' => $user->id,
                'message' => $request->message,
                'reply'   => $cleanReply
            ]);

            return response()->json([
                'status' => 'success',
                'reply' => $cleanReply
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Laravel Error: ' . $e->getMessage()], 500);
        }
    }

   private function handleDatabaseActions($reply, $user)
{
    // A. Simpan Transaksi (Pemasukan / Pengeluaran)
    if (preg_match('/\[ACTION_TRANSACTION\](.*?)\[\/ACTION\]/s', $reply, $match)) {
        $data = json_decode($match[1], true);
        if ($data) {
            Transaction::create([
                'user_id'        => $user->id,
                'tipe_transaksi' => $data['tipe'], // 'pemasukan' atau 'pengeluaran'
                'jumlah'         => $data['jumlah'],
                'kategori'       => $data['kategori'] ?? 'Lainnya',
                'deskripsi'      => $data['deskripsi'] ?? 'Catatan AI',
                'tanggal'        => now(),
            ]);

            // Update Saldo User Otomatis (Jika ada kolom saldo di tabel users)
            if ($data['tipe'] == 'pemasukan') {
                $user->increment('saldo', $data['jumlah']);
            } else {
                $user->decrement('saldo', $data['jumlah']);
            }
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
                'tanggal_target'   => now()->addYear(), // Default 1 tahun
                'status'           => 'aktif', 
                'kategori'         => $data['kategori'] ?? 'Tabungan',
            ]);
        }
    }

    // C. Update Tabungan (TAMBAH DANA KE TARGET) - SESUAI KODE KAMU
    if (preg_match('/\[ACTION_TARGET_UPDATE\](.*?)\[\/ACTION\]/s', $reply, $match)) {
        $data = json_decode($match[1], true);
        if ($data) {
            $target = Target::where('user_id', $user->id)
                            ->where('nama_target', 'like', '%' . $data['nama'] . '%')
                            ->first();
            
            if ($target) {
                // Tambah jumlah yang terkumpul di target tersebut
                $target->increment('jumlah_terkumpul', $data['jumlah']);
                
                // Opsional: Jika menabung ke target ingin memotong saldo utama
                // $user->decrement('saldo', $data['jumlah']);
            }
        }
    }
}
}