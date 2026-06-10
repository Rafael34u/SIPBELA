<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'nama_file',
        'path_file',
        'tipe_file',
        'ukuran_file',
        'diunggah_oleh',
    ];

    // Relasi ke user yang mengunggah
    public function uploader()
    {
        return $this->belongsTo(User::class, 'diunggah_oleh');
    }

    // Helper: format ukuran file
    public function ukuranFormatted(): string
    {
        $bytes = $this->ukuran_file;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }
}
