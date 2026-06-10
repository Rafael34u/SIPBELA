<?php

namespace App\Http\Controllers\Perpustakaan\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan\PerpusBuku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = PerpusBuku::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('judul', 'like', "%{$s}%")
                  ->orWhere('penulis', 'like', "%{$s}%")
                  ->orWhere('kategori', 'like', "%{$s}%")
                  ->orWhere('penerbit', 'like', "%{$s}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $bukus    = $query->latest()->paginate(12)->withQueryString();
        $kategoris = PerpusBuku::select('kategori')->distinct()->whereNotNull('kategori')->pluck('kategori');

        return view('perpustakaan.siswa.buku.index', compact('bukus', 'kategoris'));
    }
}
