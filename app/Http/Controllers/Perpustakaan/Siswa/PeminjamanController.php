<?php

namespace App\Http\Controllers\Perpustakaan\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan\PerpusBuku;
use App\Models\Perpustakaan\PerpusPeminjaman;
use App\Models\Perpustakaan\PerpusUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
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

    public function create(Request $request)
    {
        $bukus = PerpusBuku::where('stok', '>', 0)->get();
        $selectedBuku = $request->filled('buku_id')
            ? PerpusBuku::find($request->buku_id)
            : null;

        return view('perpustakaan.siswa.peminjaman.create', compact('bukus', 'selectedBuku'));
    }

    /**
     * LOGIKA KRITIS: Simpan Peminjaman
     * - Validasi stok
     * - Set batas_kembali = tanggal_pinjam + 7 hari
     * - Kurangi stok buku (decrement)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $perpusUser = $this->getOrCreatePerpusUser($user);

        $request->validate([
            'buku_id' => 'required|exists:perpus_bukus,id',
            'catatan' => 'nullable|string|max:500',
        ]);

        $buku = PerpusBuku::findOrFail($request->buku_id);

        // Validasi stok
        if ($buku->stok <= 0) {
            return back()->with('error', "Buku \"{$buku->judul}\" sedang tidak tersedia (stok habis).");
        }

        // Cek apakah user masih meminjam buku yang sama
        $sudahPinjam = PerpusPeminjaman::where('user_id', $perpusUser->id)
            ->where('buku_id', $buku->id)
            ->where('status', 'dipinjam')
            ->exists();

        if ($sudahPinjam) {
            return back()->with('error', "Anda masih meminjam buku \"{$buku->judul}\". Silakan kembalikan terlebih dahulu.");
        }

        $tanggalPinjam = now()->toDateString();
        $batasKembali  = now()->addDays(7)->toDateString();

        DB::transaction(function () use ($perpusUser, $buku, $tanggalPinjam, $batasKembali, $request) {
            // 1. Simpan transaksi peminjaman
            PerpusPeminjaman::create([
                'user_id'       => $perpusUser->id,
                'buku_id'       => $buku->id,
                'tanggal_pinjam' => $tanggalPinjam,
                'batas_kembali'  => $batasKembali,
                'status'         => 'dipinjam',
                'catatan'        => $request->catatan,
            ]);

            // 2. Kurangi stok buku
            $buku->decrement('stok');
        });

        return redirect()->route('perpustakaan.siswa.riwayat')
            ->with('success', "Buku \"{$buku->judul}\" berhasil dipinjam! Batas kembali: {$batasKembali}.");
    }

    public function riwayat()
    {
        $user = Auth::user();
        $perpusUser = $this->getOrCreatePerpusUser($user);

        $peminjamans = PerpusPeminjaman::with(['buku', 'pengembalian'])
            ->where('user_id', $perpusUser->id)
            ->latest()
            ->paginate(10);

        return view('perpustakaan.siswa.riwayat.index', compact('peminjamans'));
    }

    /**
     * Siswa mengajukan pengembalian buku dari riwayat.
     * Status diubah menjadi menunggu_konfirmasi.
     */
    public function prosesKembali($id)
    {
        $user       = Auth::user();
        $perpusUser = $this->getOrCreatePerpusUser($user);
        $peminjaman = PerpusPeminjaman::with('buku')
            ->where('user_id', $perpusUser->id)
            ->findOrFail($id);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku ini tidak sedang dipinjam.');
        }

        try {
            $peminjaman->update([
                'status' => 'menunggu_konfirmasi',
            ]);

            return back()->with('success', "Pengembalian buku \"{$peminjaman->buku->judul}\" telah diajukan. Silakan serahkan buku ke petugas/admin perpustakaan untuk diverifikasi.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengajukan pengembalian: ' . $e->getMessage());
        }
    }
}
