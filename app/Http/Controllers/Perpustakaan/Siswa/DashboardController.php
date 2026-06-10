<?php

namespace App\Http\Controllers\Perpustakaan\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan\PerpusBuku;
use App\Models\Perpustakaan\PerpusPeminjaman;
use App\Models\Perpustakaan\PerpusUser;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private function getOrCreatePerpusUser($user)
    {
        $perpusUser = PerpusUser::where('nis', $user->nis)->first();
        if (!$perpusUser) {
            $noAnggota = 'P-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            while (PerpusUser::where('no_anggota', $noAnggota)->exists()) {
                $noAnggota = 'P-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }

            $perpusUser = PerpusUser::create([
                'name'       => $user->name,
                'nis'        => $user->nis,
                'username'   => $user->username,
                'email'      => $user->email,
                'password'   => $user->password,
                'role'       => 'siswa',
                'no_anggota' => $noAnggota,
                'kelas'      => $user->kelas,
                'jurusan'    => $user->jurusan,
            ]);
        }
        return $perpusUser;
    }

    public function index()
    {
        $user = Auth::user();
        $perpusUser = $this->getOrCreatePerpusUser($user);

        $totalPinjam  = PerpusPeminjaman::where('user_id', $perpusUser->id)->count();
        $aktif        = PerpusPeminjaman::where('user_id', $perpusUser->id)->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])->count();
        $totalBuku    = PerpusBuku::count();

        // Cek apakah ada yang terlambat
        $terlambat = PerpusPeminjaman::where('user_id', $perpusUser->id)
            ->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])
            ->where('batas_kembali', '<', now()->toDateString())
            ->count();

        // Peminjaman aktif user
        $peminjamanAktif = PerpusPeminjaman::with(['buku', 'pengembalian'])
            ->where('user_id', $perpusUser->id)
            ->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])
            ->latest()
            ->get();

        // Buku tersedia terbaru
        $bukuTersedia = PerpusBuku::where('stok', '>', 0)->latest()->take(6)->get();

        return view('perpustakaan.siswa.dashboard', compact(
            'user',
            'totalPinjam',
            'aktif',
            'totalBuku',
            'terlambat',
            'peminjamanAktif',
            'bukuTersedia'
        ));
    }

    /**
     * API endpoint: data dashboard real-time (JSON)
     */
    public function apiData()
    {
        $user = Auth::user();
        $perpusUser = $this->getOrCreatePerpusUser($user);

        $totalPinjam  = PerpusPeminjaman::where('user_id', $perpusUser->id)->count();
        $aktif        = PerpusPeminjaman::where('user_id', $perpusUser->id)->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])->count();
        $totalBuku    = PerpusBuku::count();
        $terlambat    = PerpusPeminjaman::where('user_id', $perpusUser->id)
            ->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])
            ->where('batas_kembali', '<', now()->toDateString())
            ->count();

        $bukuTersedia = PerpusBuku::where('stok', '>', 0)->latest()->take(6)->get();

        return response()->json([
            'totalPinjam'  => $totalPinjam,
            'aktif'        => $aktif,
            'totalBuku'    => $totalBuku,
            'terlambat'    => $terlambat,
            'bukuTersedia' => $bukuTersedia,
        ]);
    }
}
