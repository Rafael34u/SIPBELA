<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'stok',
        'kondisi',
        'deskripsi',
    ];

    // Relasi: satu barang bisa dipinjam berkali-kali
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Helper: peminjaman yang sedang aktif
    public function peminjamanAktif()
    {
        return $this->peminjamans()->whereIn('status', ['dipinjam', 'menunggu_konfirmasi']);
    }

    // Scope: barang yang tersedia (stok > 0 & kondisi baik)
    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0)->where('kondisi', 'baik');
    }
}
