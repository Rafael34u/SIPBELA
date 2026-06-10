<?php

namespace App\Models\Perpustakaan;

use Illuminate\Database\Eloquent\Model;

class PerpusJurusan extends Model
{
    protected $table = 'perpus_jurusans';
    
    protected $fillable = [
        'nama_jurusan',
    ];
}
