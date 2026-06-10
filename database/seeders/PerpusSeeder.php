<?php

namespace Database\Seeders;

use App\Models\Perpustakaan\PerpusBuku;
use App\Models\Perpustakaan\PerpusUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PerpusSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin Perpustakaan ────────────────────────────────────────────────
        PerpusUser::firstOrCreate(
            ['username' => 'admin_perpus'],
            [
                'name'       => 'Admin Perpustakaan',
                'username'   => 'admin_perpus',
                'email'      => 'admin@perpustakaan.sch.id',
                'password'   => Hash::make('password'),
                'role'       => 'admin',
                'no_anggota' => null,
                'kelas'      => null,
            ]
        );

        // ── Siswa Contoh ──────────────────────────────────────────────────────
        $siswaData = [
            ['name' => 'Ahmad Fauzi',    'username' => 'ahmadperpus',  'kelas' => 'XI TKJ 1', 'no_anggota' => 'P-2024-001'],
            ['name' => 'Siti Rahma',     'username' => 'sitiperpus',   'kelas' => 'X AKL 2',  'no_anggota' => 'P-2024-002'],
            ['name' => 'Budi Santoso',   'username' => 'budiperpus',   'kelas' => 'XII MM 1', 'no_anggota' => 'P-2024-003'],
        ];

        foreach ($siswaData as $s) {
            PerpusUser::firstOrCreate(
                ['username' => $s['username']],
                array_merge($s, ['password' => Hash::make('password'), 'role' => 'siswa'])
            );
        }

        // ── Koleksi Buku ──────────────────────────────────────────────────────
        $bukuData = [
            ['judul' => 'Laskar Pelangi',            'penulis' => 'Andrea Hirata',         'penerbit' => 'Bentang Pustaka',    'tahun' => 2005, 'kategori' => 'Fiksi',    'stok' => 5,  'deskripsi' => 'Novel tentang anak-anak Belitung yang bersemangat mengejar pendidikan.'],
            ['judul' => 'Bumi Manusia',               'penulis' => 'Pramoedya Ananta Toer', 'penerbit' => 'Hasta Mitra',        'tahun' => 1980, 'kategori' => 'Sastra',   'stok' => 3,  'deskripsi' => 'Kisah Minke di era kolonial Belanda, buku pertama Tetralogi Buru.'],
            ['judul' => 'Matematika SMA Kelas XI',    'penulis' => 'Tim Penulis Erlangga',  'penerbit' => 'Erlangga',           'tahun' => 2022, 'kategori' => 'Pelajaran', 'stok' => 8,  'deskripsi' => 'Buku pelajaran matematika untuk SMA kelas XI sesuai kurikulum Merdeka.'],
            ['judul' => 'Fisika Dasar',               'penulis' => 'Halliday & Resnick',    'penerbit' => 'Erlangga',           'tahun' => 2020, 'kategori' => 'Sains',    'stok' => 4,  'deskripsi' => 'Buku fisika fundamental untuk pelajar dan mahasiswa.'],
            ['judul' => 'Pemrograman Python',         'penulis' => 'Ahmad Rosidi',          'penerbit' => 'Andi Publisher',     'tahun' => 2023, 'kategori' => 'Teknologi', 'stok' => 6,  'deskripsi' => 'Panduan lengkap belajar Python dari dasar hingga mahir.'],
            ['judul' => 'Sejarah Nasional Indonesia', 'penulis' => 'Sartono Kartodirdjo',   'penerbit' => 'Balai Pustaka',      'tahun' => 1987, 'kategori' => 'Sejarah',  'stok' => 2,  'deskripsi' => 'Sejarah Indonesia dari masa prasejarah hingga kemerdekaan.'],
            ['judul' => 'Harry Potter dan Batu Bertuah', 'penulis' => 'J.K. Rowling',       'penerbit' => 'Gramedia',           'tahun' => 2000, 'kategori' => 'Fiksi',    'stok' => 3,  'deskripsi' => 'Petualangan Harry Potter di sekolah sihir Hogwarts.'],
            ['judul' => 'Ekonomi Makro',              'penulis' => 'Sadono Sukirno',        'penerbit' => 'Raja Grafindo',      'tahun' => 2019, 'kategori' => 'Ekonomi',  'stok' => 5,  'deskripsi' => 'Pengantar ilmu ekonomi makro untuk pelajar dan mahasiswa.'],
            ['judul' => 'Kimia Organik',              'penulis' => 'Fessenden & Fessenden', 'penerbit' => 'Erlangga',           'tahun' => 2015, 'kategori' => 'Sains',    'stok' => 3,  'deskripsi' => 'Buku kimia organik lengkap dengan reaksi dan mekanisme.'],
            ['judul' => 'Filosofi Teras',             'penulis' => 'Henry Manampiring',     'penerbit' => 'Kompas',             'tahun' => 2018, 'kategori' => 'Filsafat', 'stok' => 7,  'deskripsi' => 'Filsafat Stoa dari Yunani-Romawi untuk ketangguhan jiwa modern.'],
        ];

        foreach ($bukuData as $b) {
            PerpusBuku::firstOrCreate(['judul' => $b['judul']], $b);
        }

        $this->command->info('✅ PerpusSeeder: Data perpustakaan berhasil dibuat!');
        $this->command->info('   Admin: admin_perpus / password');
        $this->command->info('   Siswa: ahmadperpus, sitiperpus, budiperpus / password');
        $this->command->info('   10 buku telah ditambahkan.');
    }
}
