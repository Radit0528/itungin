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

            $apiKey = config('services.gemini.key');
            if (!$apiKey) {
                return response()->json(['status' => 'error', 'message' => 'API Key belum di-set di .env'], 500);
            }

            // PROMPT: Memberitahu AI cara mencatat sesuai struktur tabel kamu
            $systemPrompt = "Kamu adalah Itungin AI. Tugasmu membantu {$user->name} mencatat keuangan.
            Selalu sertakan kode JSON di AKHIR jawaban jika user ingin mencatat:

            1. TRANSAKSI (Contoh: 'makan 10rb'):
            [ACTION_TRANSACTION]{\"tipe\":\"pengeluaran\",\"jumlah\":10000,\"kategori\":\"Makanan\",\"deskripsi\":\"Makan\"}[/ACTION]
            (Tipe: 'pengeluaran' atau 'pemasukan')

            2. TARGET BARU (Contoh: 'Mau nabung 5jt buat Motor'):
            [ACTION_TARGET_NEW]{\"nama\":\"Motor\",\"jumlah\":5000000,\"kategori\":\"Kendaraan\"}[/ACTION]

            3. NABUNG KE TARGET (Contoh: 'Tabung 100rb ke Motor'):
            [ACTION_TARGET_UPDATE]{\"nama\":\"Motor\",\"jumlah\":100000}[/ACTION]
            
            Gunakan double quote (\") untuk JSON. Jawab dengan singkat dan ramah.";

            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-pro:generateContent?key={$apiKey}", [
                'contents' => [
                    ['role' => 'user', 'parts' => [['text' => $systemPrompt . "\nUser: " . $request->message]]]
                ]
            ]);

            if ($response->successful()) {
                $reply = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';

                // --- LOGIKA DATABASE ---
                
                // 1. Simpan Transaksi
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

                // 2. Simpan Target Baru
                if (preg_match('/\[ACTION_TARGET_NEW\](.*?)\[\/ACTION\]/s', $reply, $match)) {
                    $data = json_decode($match[1], true);
                    if ($data) {
                        Target::create([
                            'user_id'          => $user->id,
                            'nama_target'      => $data['nama'],
                            'target_jumlah'    => $data['jumlah'],
                            'jumlah_terkumpul' => 0,
                            'tanggal_target'   => now()->addMonths(6),
                            'status'           => 'in_progress',
                            'kategori'         => $data['kategori'] ?? 'Impian',
                        ]);
                    }
                }

                // 3. Update Progres Target
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

                // Bersihkan teks dari kode JSON agar tidak terlihat oleh user
                $cleanReply = preg_replace('/\[ACTION_.*?\].*?\[\/ACTION\]/s', '', $reply);

                return response()->json([
                    'status' => 'success',
                    'reply' => trim($cleanReply)
                ]);
            }

            return response()->json(['status' => 'error', 'message' => 'Gemini Error: ' . $response->body()], 500);

        } catch (\Exception $e) {
            // Ini akan memunculkan pesan error asli di console F12 jika gagal
            return response()->json(['status' => 'error', 'message' => 'Laravel Error: ' . $e->getMessage()], 500);
        }
    }
}