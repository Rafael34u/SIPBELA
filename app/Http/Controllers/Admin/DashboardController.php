<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Materi;
use App\Models\Peminjaman;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_barang'       => Barang::count(),
            'total_stok'         => Barang::sum('stok'),
            'peminjaman_aktif'   => Peminjaman::aktif()->count(),
            'total_siswa'        => User::where('role', 'siswa')->count(),
            'dikembalikan_hari'  => Peminjaman::selesai()
                                        ->whereDate('tanggal_kembali', today())
                                        ->count(),
            'total_materi'       => Materi::count(),
        ];

        $peminjaman_terbaru = Peminjaman::with(['user', 'barang'])
            ->latest()
            ->take(5)
            ->get();

        $barang_stok_minim = Barang::where('stok', '<=', 2)
            ->orderBy('stok')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'peminjaman_terbaru', 'barang_stok_minim'));
    }

    public function bab5()
    {
        return view('admin.bab5');
    }
}
