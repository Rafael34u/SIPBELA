<?php

namespace App\Models\Perpustakaan;

use Illuminate\Database\Eloquent\Model;

class PerpusMasterSiswa extends Model
{
    protected $table = 'perpus_master_siswas';

    protected $fillable = [
        'nis',
        'nama',
        'jurusan',
        'kelas',
    ];
}
