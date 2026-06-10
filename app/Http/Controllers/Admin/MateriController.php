<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        $materis = Materi::with('uploader')->latest()->get();
        return view('admin.materis.index', compact('materis'));
    }

    public function create()
    {
        return view('admin.materis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'    => 'required|string|max:255',
            'deskripsi'=> 'nullable|string|max:1000',
            'file'     => 'required|file|mimes:pdf,doc,docx|max:20480', // max 20MB
        ], [
            'judul.required'  => 'Judul materi wajib diisi.',
            'file.required'   => 'File materi wajib diunggah.',
            'file.mimes'      => 'Format file harus PDF, DOC, atau DOCX.',
            'file.max'        => 'Ukuran file maksimal 20 MB.',
        ]);

        $file = $request->file('file');
        $ext  = strtolower($file->getClientOriginalExtension());
        $tipe = ($ext === 'pdf') ? 'pdf' : 'word';

        // Simpan ke storage/app/public/materis/
        $path = $file->store('materis', 'public');

        Materi::create([
            'judul'         => $request->judul,
            'deskripsi'     => $request->deskripsi,
            'nama_file'     => $file->getClientOriginalName(),
            'path_file'     => $path,
            'tipe_file'     => $tipe,
            'ukuran_file'   => $file->getSize(),
            'diunggah_oleh' => auth()->id(),
        ]);

        return redirect()->route('admin.materis.index')
            ->with('success', 'Materi "' . $request->judul . '" berhasil diunggah.');
    }

    public function destroy(Materi $materi)
    {
        // Hapus file fisik dari storage
        Storage::disk('public')->delete($materi->path_file);
        $materi->delete();

        return redirect()->route('admin.materis.index')
            ->with('success', 'Materi berhasil dihapus.');
    }
}
