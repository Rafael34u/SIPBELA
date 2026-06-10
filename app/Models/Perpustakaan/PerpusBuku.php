<?php

namespace App\Models\Perpustakaan;

use Illuminate\Database\Eloquent\Model;

class PerpusBuku extends Model
{
    protected $table = 'perpus_bukus';

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun',
        'kategori',
        'stok',
        'deskripsi',
        'gambar',
    ];

    // Relasi: satu buku bisa dipinjam berkali-kali
    public function peminjamans()
    {
        return $this->hasMany(PerpusPeminjaman::class, 'buku_id');
    }

    // Helper: apakah buku masih tersedia
    public function tersedia(): bool
    {
        return $this->stok > 0;
    }
}
