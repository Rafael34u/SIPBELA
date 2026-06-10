<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perpus_peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('perpus_users')->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('perpus_bukus')->onDelete('cascade');
            $table->date('tanggal_pinjam');
            // batas_kembali = tanggal_pinjam + 7 hari (diset otomatis saat store)
            $table->date('batas_kembali');
            $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perpus_peminjamans');
    }
};
