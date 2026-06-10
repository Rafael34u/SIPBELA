<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Alter status column in peminjamans (bengkel)
        DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status ENUM('dipinjam', 'menunggu_konfirmasi', 'dikembalikan') NOT NULL DEFAULT 'dipinjam'");

        // Alter status column in perpus_peminjamans (perpustakaan)
        DB::statement("ALTER TABLE perpus_peminjamans MODIFY COLUMN status ENUM('dipinjam', 'menunggu_konfirmasi', 'dikembalikan') NOT NULL DEFAULT 'dipinjam'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status column in peminjamans
        DB::table('peminjamans')->where('status', 'menunggu_konfirmasi')->update(['status' => 'dipinjam']);
        DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status ENUM('dipinjam', 'dikembalikan') NOT NULL DEFAULT 'dipinjam'");

        // Revert status column in perpus_peminjamans
        DB::table('perpus_peminjamans')->where('status', 'menunggu_konfirmasi')->update(['status' => 'dipinjam']);
        DB::statement("ALTER TABLE perpus_peminjamans MODIFY COLUMN status ENUM('dipinjam', 'dikembalikan') NOT NULL DEFAULT 'dipinjam'");
    }
};
