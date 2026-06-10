<?php

namespace App\Http\Controllers\Perpustakaan\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan\PerpusPeminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = PerpusPeminjaman::with(['user', 'buku', 'pengembalian']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$s}%"))
                  ->orWhereHas('buku', fn($b) => $b->where('judul', 'like', "%{$s}%"));
            });
        }

        // Filter terlambat
        if ($request->filled('terlambat') && $request->terlambat == '1') {
            $query->where('status', 'dipinjam')
                  ->where('batas_kembali', '<', now()->toDateString());
        }

        $peminjamans = $query->latest()->paginate(10)->withQueryString();

        return view('perpustakaan.admin.peminjaman.index', compact('peminjamans'));
    }
}
