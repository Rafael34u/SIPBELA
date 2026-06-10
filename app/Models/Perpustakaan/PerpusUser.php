<?php

namespace App\Models\Perpustakaan;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PerpusUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'perpus_users';

    protected $fillable = [
        'name',
        'nis',
        'username',
        'email',
        'password',
        'role',
        'no_anggota',
        'kelas',
        'jurusan',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // Relasi: satu anggota bisa punya banyak peminjaman buku
    public function peminjamans()
    {
        return $this->hasMany(PerpusPeminjaman::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }
}
