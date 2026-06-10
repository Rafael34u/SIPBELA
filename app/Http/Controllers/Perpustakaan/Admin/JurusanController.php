<?php

namespace App\Http\Controllers\Perpustakaan\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan\PerpusJurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusans = PerpusJurusan::latest()->get();
        return view('perpustakaan.admin.jurusan.index', compact('jurusans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:100|unique:perpus_jurusans,nama_jurusan',
        ]);

        PerpusJurusan::create($request->all());

        return back()->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function update(Request $request, PerpusJurusan $jurusan)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:100|unique:perpus_jurusans,nama_jurusan,' . $jurusan->id,
        ]);

        $jurusan->update($request->all());

        return back()->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(PerpusJurusan $jurusan)
    {
        $jurusan->delete();
        return back()->with('success', 'Jurusan berhasil dihapus.');
    }
}
