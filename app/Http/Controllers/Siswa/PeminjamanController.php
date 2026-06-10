<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Form peminjaman: pilih barang & tanggal
     */
    public function create(Request $request)
    {
        // Hanya tampilkan barang dengan stok > 0 dan kondisi baik
        $barangs = Barang::where('stok', '>', 0)
            ->where('kondisi', 'baik')
            ->orderBy('nama_barang')
            ->get();

        $selected_barang = null;
        if ($request->filled('barang_id')) {
            $selected_barang = Barang::find($request->barang_id);
        }

        return view('siswa.peminjaman.create', compact('barangs', 'selected_barang'));
    }

    /**
     * LOGIKA KRITIS: Proses Peminjaman
     * - Gunakan DB Transaction + lockForUpdate untuk mencegah race condition
     * - Validasi stok > 0 dalam transaksi
     * - Decrement stok barang jika berhasil
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id'     => 'required|exists:barangs,id',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'catatan'        => 'nullable|string|max:300',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // Lock baris barang untuk mencegah race condition
                $barang = Barang::lockForUpdate()->findOrFail($validated['barang_id']);

                // Validasi stok real-time (cek ulang di dalam transaksi)
                if ($barang->stok <= 0) {
                    throw new \Exception("Stok barang \"{$barang->nama_barang}\" sudah habis. Peminjaman dibatalkan.");
                }

                // Validasi kondisi barang
                if ($barang->kondisi !== 'baik') {
                    throw new \Exception("Barang \"{$barang->nama_barang}\" sedang dalam kondisi {$barang->kondisi} dan tidak dapat dipinjam.");
                }

                // Kurangi stok barang
                $barang->decrement('stok');

                // Buat record peminjaman
                Peminjaman::create([
                    'user_id'        => auth()->id(),
                    'barang_id'      => $barang->id,
                    'tanggal_pinjam' => $validated['tanggal_pinjam'],
                    'status'         => 'dipinjam',
                    'catatan'        => $validated['catatan'] ?? null,
                ]);
            });

            return redirect()->route('siswa.riwayat')
                ->with('success', 'Peminjaman berhasil diajukan! Segera ambil barang di bengkel.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Riwayat peminjaman milik siswa yang sedang login
     */
    public function riwayat(Request $request)
    {
        $query = Peminjaman::with('barang')
            ->where('user_id', auth()->id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjamans = $query->latest()->paginate(10)->withQueryString();

        return view('siswa.riwayat', compact('peminjamans'));
    }

    /**
     * Proses pengembalian barang oleh siswa
     */
    public function prosesKembali($id)
    {
        $peminjaman = Peminjaman::with('barang')->where('user_id', auth()->id())->findOrFail($id);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Barang ini tidak sedang dipinjam.');
        }

        try {
            // Update status peminjaman menjadi menunggu_konfirmasi
            // Tidak ada perubahan stok atau tanggal_kembali di sini, karena dilakukan oleh admin setelah konfirmasi fisik
            $peminjaman->update([
                'status' => 'menunggu_konfirmasi',
            ]);

            return back()->with('success', "Pengembalian barang \"{$peminjaman->barang->nama_barang}\" telah diajukan. Silakan serahkan barang ke admin bengkel untuk dikonfirmasi.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }
}
