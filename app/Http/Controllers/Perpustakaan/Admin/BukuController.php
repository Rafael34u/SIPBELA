<?php

namespace App\Http\Controllers\Perpustakaan\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan\PerpusBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
                  ->orWhere('kategori', 'like', "%{$s}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $bukus    = $query->latest()->paginate(10)->withQueryString();
        $kategoris = PerpusBuku::select('kategori')->distinct()->whereNotNull('kategori')->pluck('kategori');

        return view('perpustakaan.admin.buku.index', compact('bukus', 'kategoris'));
    }

    public function create()
    {
        return view('perpustakaan.admin.buku.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'     => 'required|string|max:255',
            'penulis'   => 'required|string|max:255',
            'penerbit'  => 'nullable|string|max:255',
            'tahun'     => 'nullable|digits:4|integer',
            'kategori'  => 'nullable|string|max:100',
            'stok'      => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('buku', 'public');
        }

        PerpusBuku::create($data);

        return redirect()->route('perpustakaan.admin.buku.index')
            ->with('success', "Buku \"{$data['judul']}\" berhasil ditambahkan.");
    }

    public function edit(PerpusBuku $buku)
    {
        return view('perpustakaan.admin.buku.edit', compact('buku'));
    }

    public function update(Request $request, PerpusBuku $buku)
    {
        $data = $request->validate([
            'judul'     => 'required|string|max:255',
            'penulis'   => 'required|string|max:255',
            'penerbit'  => 'nullable|string|max:255',
            'tahun'     => 'nullable|digits:4|integer',
            'kategori'  => 'nullable|string|max:100',
            'stok'      => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($buku->gambar) {
                Storage::disk('public')->delete($buku->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('buku', 'public');
        }

        $buku->update($data);

        return redirect()->route('perpustakaan.admin.buku.index')
            ->with('success', "Buku \"{$buku->judul}\" berhasil diperbarui.");
    }

    public function destroy(PerpusBuku $buku)
    {
        $judul = $buku->judul;
        if ($buku->gambar) {
            Storage::disk('public')->delete($buku->gambar);
        }
        $buku->delete();

        return back()->with('success', "Buku \"{$judul}\" berhasil dihapus.");
    }
}
