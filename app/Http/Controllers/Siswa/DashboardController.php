<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Materi;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua barang untuk katalog — real-time stok
        $barangs = Barang::orderBy('kondisi')
            ->orderByDesc('stok')
            ->get();

        // Statistik peminjaman siswa yang sedang login
        $user = auth()->user();
        $stats = [
            'sedang_dipinjam' => $user->peminjamans()->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])->count(),
            'total_pinjam'    => $user->peminjamans()->count(),
        ];

        $materis = Materi::latest()->get();

        return view('siswa.dashboard', compact('barangs', 'stats', 'materis'));
    }

    /**
     * API endpoint: data katalog real-time (JSON)
     */
    public function apiData()
    {
        $barangs = Barang::orderBy('kondisi')->orderByDesc('stok')->get();

        $user = auth()->user();
        $stats = [
            'sedang_dipinjam' => $user->peminjamans()->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])->count(),
            'total_pinjam'    => $user->peminjamans()->count(),
        ];

        return response()->json([
            'barangs' => $barangs,
            'stats'   => $stats,
        ]);
    }
}
