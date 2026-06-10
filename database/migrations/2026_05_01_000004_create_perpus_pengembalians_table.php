<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perpus_pengembalians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')
                  ->constrained('perpus_peminjamans')
                  ->onDelete('cascade');
            $table->date('tanggal_kembali');
            // hari_terlambat = max(0, tanggal_kembali - batas_kembali)
            $table->unsignedInteger('hari_terlambat')->default(0);
            // denda = hari_terlambat * 1000
            $table->unsignedBigInteger('denda')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perpus_pengembalians');
    }
};
