<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Perpustakaan\PerpusBuku;
use App\Models\Perpustakaan\PerpusPeminjaman;
use App\Models\Perpustakaan\PerpusMasterSiswa;

class DashboardController extends Controller
{
    public function index()
    {
        // Auto-sync missing student accounts from master siswa list on load
        User::syncFromMaster();

        $totalSiswa = User::where('role', 'siswa')->count();
        $totalSiswaMaster = PerpusMasterSiswa::count();
        
        $totalBarangBengkel = Barang::sum('stok');
        $totalPeminjamanBengkel = Peminjaman::count();
        
        $totalBukuPerpus = PerpusBuku::sum('stok');
        $totalPeminjamanPerpus = PerpusPeminjaman::count();

        // Data for Chart.js distribution of majors, handling both abbreviations and full names
        $jurusanDistribution = [
            'TKR' => User::where('role', 'siswa')->whereIn('jurusan', ['TKR', 'Teknik Kendaraan Ringan'])->count(),
            'TKJ' => User::where('role', 'siswa')->whereIn('jurusan', ['TKJ', 'Teknik Komputer Jaringan'])->count(),
            'RPL' => User::where('role', 'siswa')->whereIn('jurusan', ['RPL', 'Rekayasa Perangkat Lunak'])->count(),
            'MM'  => User::where('role', 'siswa')->whereIn('jurusan', ['MM', 'Multimedia'])->count(),
            'DG'  => User::where('role', 'siswa')->whereIn('jurusan', ['DG', 'Desain Grafis'])->count(),
            'TEI' => User::where('role', 'siswa')->whereIn('jurusan', ['TEI', 'Teknik Audio Video'])->count(),
        ];

        $activities = collect();

        User::where('role', 'siswa')->latest()->take(3)->get()->each(function($user) use ($activities) {
            $activities->push([
                'color' => 'bg-indigo-500',
                'title' => "Siswa baru mendaftar: {$user->name}",
                'time' => $user->created_at
            ]);
        });

        User::whereIn('role', ['admin_bengkel', 'admin_perpus'])->latest()->take(2)->get()->each(function($user) use ($activities) {
            $activities->push([
                'color' => 'bg-pink-500',
                'title' => "Akun admin dibuat/diedit: {$user->name}",
                'time' => $user->created_at // Assuming we use created_at. Updated_at might be noisy
            ]);
        });

        Peminjaman::with('user')->latest()->take(3)->get()->each(function($p) use ($activities) {
            $name = $p->user ? $p->user->name : 'Siswa';
            $activities->push([
                'color' => 'bg-blue-500',
                'title' => "Bengkel: {$name} meminjam alat",
                'time' => $p->created_at
            ]);
        });

        PerpusPeminjaman::with('user')->latest()->take(3)->get()->each(function($p) use ($activities) {
            $name = $p->user ? $p->user->name : 'Siswa';
            $activities->push([
                'color' => 'bg-emerald-500',
                'title' => "Perpus: {$name} meminjam buku",
                'time' => $p->created_at
            ]);
        });

        $recentActivities = $activities->sortByDesc('time')->take(5);

        return view('superadmin.dashboard', compact(
            'totalSiswa',
            'totalSiswaMaster',
            'totalBarangBengkel',
            'totalPeminjamanBengkel',
            'totalBukuPerpus',
            'totalPeminjamanPerpus',
            'jurusanDistribution',
            'recentActivities'
        ));
    }

    public function checkNis($nis)
    {
        $master = PerpusMasterSiswa::where('nis', $nis)->first();
        if (!$master) {
            return response()->json(['status' => 'not_found', 'message' => 'NIS belum terdaftar di data verifikasi sekolah.']);
        }

        $registered = User::where('nis', $nis)->first();
        if ($registered) {
            return response()->json([
                'status' => 'registered',
                'name' => $master->nama,
                'jurusan' => $master->jurusan,
                'kelas' => $master->kelas,
                'username' => $registered->username,
                'email' => $registered->email ?? '-',
                'message' => 'Akun sudah terdaftar aktif.'
            ]);
        }

        return response()->json([
            'status' => 'verified_only',
            'name' => $master->nama,
            'jurusan' => $master->jurusan,
            'kelas' => $master->kelas,
            'message' => 'NIS terverifikasi tapi akun belum didaftarkan.'
        ]);
    }
}
