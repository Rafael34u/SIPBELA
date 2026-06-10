<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id',
        'barang_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pinjam'  => 'date',
        'tanggal_kembali' => 'date',
    ];

    // Relasi ke User (siswa peminjam)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Barang yang dipinjam
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Scope: hanya yang sedang dipinjam atau menunggu konfirmasi
    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['dipinjam', 'menunggu_konfirmasi']);
    }

    // Scope: sudah dikembalikan
    public function scopeSelesai($query)
    {
        return $query->where('status', 'dikembalikan');
    }
}
