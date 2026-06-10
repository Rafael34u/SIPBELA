<?php

namespace App\Http\Controllers\Perpustakaan\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan\PerpusBuku;
use App\Models\Perpustakaan\PerpusPeminjaman;
use App\Models\Perpustakaan\PerpusPengembalian;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku        = PerpusBuku::count();
        $totalAnggota     = User::where('role', 'siswa')->count();
        $peminjamanAktif  = PerpusPeminjaman::whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])->count();
        $totalDenda       = PerpusPengembalian::sum('denda');

        // Peminjaman yang melewati batas kembali dan belum dikembalikan
        $terlambat = PerpusPeminjaman::with(['user', 'buku'])
            ->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])
            ->where('batas_kembali', '<', now()->toDateString())
            ->count();

        // 5 peminjaman terbaru
        $peminjamanTerbaru = PerpusPeminjaman::with(['user', 'buku'])
            ->latest()
            ->take(5)
            ->get();

        return view('perpustakaan.admin.dashboard', compact(
            'totalBuku',
            'totalAnggota',
            'peminjamanAktif',
            'totalDenda',
            'terlambat',
            'peminjamanTerbaru'
        ));
    }
}
