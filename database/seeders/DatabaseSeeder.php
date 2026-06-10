<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Admin Bengkel ──────────────────────────────────────────
        User::create([
            'name'     => 'Admin Bengkel',
            'username' => 'admin',
            'email'    => 'admin@smkbengkel.sch.id',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // ── 2. Data Siswa ─────────────────────────────────────────────
        $siswaData = [
            ['name' => 'Budi Santoso',    'username' => 'budi2024'],
            ['name' => 'Siti Rahayu',     'username' => 'siti2024'],
            ['name' => 'Ahmad Fauzi',     'username' => 'ahmad2024'],
            ['name' => 'Dewi Lestari',    'username' => 'dewi2024'],
            ['name' => 'Rizky Pratama',   'username' => 'rizky2024'],
        ];

        foreach ($siswaData as $s) {
            User::create([
                'name'     => $s['name'],
                'username' => $s['username'],
                'email'    => $s['username'] . '@siswa.sch.id',
                'password' => Hash::make('siswa123'),
                'role'     => 'siswa',
            ]);
        }

        // ── 3. Data Barang Bengkel ────────────────────────────────────
        $barangData = [
            ['kode_barang' => 'BRG-001', 'nama_barang' => 'Kunci Ring Set',        'stok' => 5,  'kondisi' => 'baik',       'deskripsi' => 'Set kunci ring ukuran 8-32mm'],
            ['kode_barang' => 'BRG-002', 'nama_barang' => 'Kunci Sok Set',         'stok' => 3,  'kondisi' => 'baik',       'deskripsi' => 'Set kunci sok dengan ratchet 1/2 inch'],
            ['kode_barang' => 'BRG-003', 'nama_barang' => 'Obeng Plus Minus Set',  'stok' => 8,  'kondisi' => 'baik',       'deskripsi' => 'Set obeng plus dan minus berbagai ukuran'],
            ['kode_barang' => 'BRG-004', 'nama_barang' => 'Tang Kombinasi',        'stok' => 6,  'kondisi' => 'baik',       'deskripsi' => 'Tang kombinasi 8 inch'],
            ['kode_barang' => 'BRG-005', 'nama_barang' => 'Palu Besi 500gr',       'stok' => 4,  'kondisi' => 'baik',       'deskripsi' => 'Palu besi kepala 500 gram'],
            ['kode_barang' => 'BRG-006', 'nama_barang' => 'Multimeter Digital',    'stok' => 2,  'kondisi' => 'baik',       'deskripsi' => 'Multimeter digital auto-range'],
            ['kode_barang' => 'BRG-007', 'nama_barang' => 'Kunci L Set (Hex)',     'stok' => 4,  'kondisi' => 'baik',       'deskripsi' => 'Set kunci L hexagonal 1.5-10mm'],
            ['kode_barang' => 'BRG-008', 'nama_barang' => 'Feeler Gauge',          'stok' => 3,  'kondisi' => 'baik',       'deskripsi' => 'Feeler gauge 0.05-1.0mm'],
            ['kode_barang' => 'BRG-009', 'nama_barang' => 'Treker Bearing',        'stok' => 2,  'kondisi' => 'diperbaiki', 'deskripsi' => 'Treker bearing 3 rahang'],
            ['kode_barang' => 'BRG-010', 'nama_barang' => 'Dongkrak Buaya 2 Ton',  'stok' => 1,  'kondisi' => 'baik',       'deskripsi' => 'Dongkrak buaya kapasitas 2 ton'],
        ];

        foreach ($barangData as $b) {
            Barang::create($b);
        }

        // ── 4. Data Peminjaman Contoh ─────────────────────────────────
        $siswa = User::where('role', 'siswa')->get();

        Peminjaman::create([
            'user_id'        => $siswa[0]->id,
            'barang_id'      => 1,
            'tanggal_pinjam' => now()->subDays(3),
            'status'         => 'dipinjam',
            'catatan'        => 'Untuk praktikum mesin semester 2',
        ]);

        Peminjaman::create([
            'user_id'        => $siswa[1]->id,
            'barang_id'      => 3,
            'tanggal_pinjam' => now()->subDays(5),
            'tanggal_kembali'=> now()->subDays(2),
            'status'         => 'dikembalikan',
            'catatan'        => 'Sudah dikembalikan dalam kondisi baik',
        ]);

        Peminjaman::create([
            'user_id'        => $siswa[2]->id,
            'barang_id'      => 6,
            'tanggal_pinjam' => now()->subDay(),
            'status'         => 'dipinjam',
            'catatan'        => 'Untuk praktikum kelistrikan',
        ]);
    }
}
