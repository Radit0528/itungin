<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('nama_target');           // Contoh: "Tabungan Motor", "Dana Darurat", "Liburan ke Bali"
            $table->bigInteger('target_jumlah');     // Nominal target (Rupiah)
            $table->bigInteger('jumlah_terkumpul')->default(0); // Sudah terkumpul saat ini
            
            $table->date('tanggal_target');          // Deadline / tanggal target harus tercapai
            
            $table->enum('status', ['aktif', 'tercapai', 'gagal', 'dibatalkan'])->default('aktif');
            $table->string('kategori');              // Contoh: "Tabungan", "Investasi", "Liburan", "Pendidikan", dll.
            
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};
