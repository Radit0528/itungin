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

            // 1. AMBIL HISTORY CHAT (5 Pesan Terakhir)
            $history = Chat::where('user_id', $user->id)
                        ->latest()
                        ->limit(5)
                        ->get()
                        ->reverse();

            // 2. SUSUN FORMAT CONTENTS UNTUK GEMINI
            $contents = [];
            foreach ($history as $chat) {
                $contents[] = ['role' => 'user', 'parts' => [['text' => $chat->message]]];
                $contents[] = ['role' => 'model', 'parts' => [['text' => $chat->reply]]];
            }

            // 3. SYSTEM PROMPT & PESAN BARU
            $systemPrompt = "Kamu adalah Itungin AI. Tugasmu membantu {$user->name} mencatat keuangan.
            Selalu gunakan status 'aktif' untuk target baru.
            Selalu sertakan kode JSON di AKHIR jawaban jika user ingin mencatat:
            1. TRANSAKSI: [ACTION_TRANSACTION]{\"tipe\":\"pengeluaran\",\"jumlah\":10000,\"kategori\":\"Makanan\",\"deskripsi\":\"Makan\"}[/ACTION]
            2. TARGET BARU: [ACTION_TARGET_NEW]{\"nama\":\"Motor\",\"jumlah\":5000000,\"kategori\":\"Kendaraan\"}[/ACTION]
            3. NABUNG KE TARGET: [ACTION_TARGET_UPDATE]{\"nama\":\"Motor\",\"jumlah\":100000}[/ACTION]
            Jawab dengan singkat, ramah, dan memotivasi.";

            // Tambahkan pesan user saat ini ke array contents
            $contents[] = ['role' => 'user', 'parts' => [['text' => $systemPrompt . "\n\nUser: " . $request->message]]];

            $finalReply = null;
            $success = false;

            // 4. LOGIKA ROTASI API
            foreach ($apiKeys as $key) {
                if (!$key) continue;

                // Pakai v1/gemini-1.5-flash (Versi paling stabil & jatah banyak di 2026)
                $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent?key={$key}", [
                    'contents' => $contents
                ]);

                if ($response->successful()) {
                    $finalReply = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';
                    $success = true;
                    break; 
                }

                if ($response->status() == 429) continue;

                return response()->json(['status' => 'error', 'message' => 'Gemini Error: ' . $response->body()], $response->status());
            }

            if ($response->successful()) {
                $finalReply = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';
                $success = true;
            
                // A. JALANKAN LOGIKA DATABASE DULU (Pakai teks asli yang ada JSON-nya)
                $this->handleDatabaseActions($finalReply, $user);
            
                // B. BERSIHKAN TEKS (Hapus tag [ACTION]...[/ACTION])
                $cleanReply = preg_replace('/\[ACTION_.*?\].*?\[\/ACTION\]/s', '', $finalReply);
                $cleanReply = trim($cleanReply);
            
                // C. SIMPAN KE HISTORY (Simpan teks yang sudah BERSIH)
                Chat::create([
                    'user_id' => $user->id,
                    'message' => $request->message,
                    'reply'   => $cleanReply // <-- Ini kuncinya, simpan yang bersih saja
                ]);
            
                // D. KIRIM RESPON KE VIEW
                return response()->json([
                    'status' => 'success',
                    'reply' => $cleanReply
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Laravel Error: ' . $e->getMessage()], 500);
        }
    }

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
                    'tanggal_target'   => now()->addYear(), 
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