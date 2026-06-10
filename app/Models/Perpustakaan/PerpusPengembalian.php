<?php

namespace App\Models\Perpustakaan;

use Illuminate\Database\Eloquent\Model;

class PerpusPengembalian extends Model
{
    protected $table = 'perpus_pengembalians';

    protected $fillable = [
        'peminjaman_id',
        'tanggal_kembali',
        'hari_terlambat',
        'denda',
    ];

    protected $casts = [
        'tanggal_kembali' => 'date',
        'hari_terlambat'  => 'integer',
        'denda'           => 'integer',
    ];

    // Relasi ke peminjaman
    public function peminjaman()
    {
        return $this->belongsTo(PerpusPeminjaman::class, 'peminjaman_id');
    }

    // Format denda sebagai Rupiah
    public function dendaFormatted(): string
    {
        return 'Rp ' . number_format($this->denda, 0, ',', '.');
    }
}
