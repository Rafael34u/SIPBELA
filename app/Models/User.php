<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'nis',
        'username',
        'email',
        'jurusan',
        'kelas',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // Relasi: satu user bisa punya banyak peminjaman alat bengkel
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Relasi: satu user bisa punya banyak peminjaman buku perpustakaan
    public function perpusPeminjamans()
    {
        return $this->hasMany(\App\Models\Perpustakaan\PerpusPeminjaman::class, 'user_id');
    }

    /**
     * Auto-sync missing student accounts from perpus_master_siswas.
     */
    public static function syncFromMaster()
    {
        $masters = \App\Models\Perpustakaan\PerpusMasterSiswa::all();
        foreach ($masters as $master) {
            $user = self::where('nis', $master->nis)
                        ->orWhere('username', $master->nis)
                        ->first();
            
            if (!$user) {
                self::create([
                    'name'     => $master->nama,
                    'nis'      => $master->nis,
                    'username' => $master->nis,
                    'email'    => $master->nis . '@siswa.sch.id',
                    'jurusan'  => $master->jurusan ?? '-',
                    'kelas'    => $master->kelas ?? '-',
                    'password' => \Illuminate\Support\Facades\Hash::make($master->nis),
                    'role'     => 'siswa',
                ]);
            } else {
                $updated = false;
                if ($user->name !== $master->nama) {
                    $user->name = $master->nama;
                    $updated = true;
                }
                if ($user->jurusan !== ($master->jurusan ?? '-')) {
                    $user->jurusan = $master->jurusan ?? '-';
                    $updated = true;
                }
                if ($user->kelas !== ($master->kelas ?? '-')) {
                    $user->kelas = $master->kelas ?? '-';
                    $updated = true;
                }
                if ($user->nis !== $master->nis) {
                    $user->nis = $master->nis;
                    $updated = true;
                }
                if ($updated) {
                    $user->save();
                }
            }
        }
    }

    // Helper: cek apakah admin bengkel
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'admin_bengkel', 'superadmin']);
    }

    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isPerpusAdmin(): bool
    {
        return $this->role === 'admin_perpus';
    }

    // Helper: cek apakah siswa
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }
}
