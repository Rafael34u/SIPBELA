@extends('perpustakaan.layouts.app')

@section('title', 'Tambah Buku')
@section('page-title', 'Tambah Buku Baru')
@section('page-subtitle', 'Masukkan data buku yang akan ditambahkan ke koleksi perpustakaan')

@section('content')
<div class="max-w-2xl">
    <div class="card p-6">
        <form method="POST" action="{{ route('perpustakaan.admin.buku.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Gambar Sampul</label>
                    <input type="file" name="gambar" accept="image/*"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    <p class="text-[10px] text-slate-500 mt-1">Format: JPG, JPEG, PNG. Maks: 2MB.</p>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Buku <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul') }}" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Contoh: Pemrograman PHP Modern">
                    @error('judul')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Penulis <span class="text-red-500">*</span></label>
                    <input type="text" name="penulis" value="{{ old('penulis') }}" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Nama penulis">
                    @error('penulis')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Penerbit</label>
                    <input type="text" name="penerbit" value="{{ old('penerbit') }}"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Nama penerbit">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Terbit</label>
                    <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" min="1900" max="{{ date('Y') }}"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori</label>
                    <input type="text" name="kategori" value="{{ old('kategori') }}"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Fiksi, Sains, Sejarah, dll">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="stok" value="{{ old('stok', 1) }}" min="0" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error('stok')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"
                        placeholder="Deskripsi singkat buku...">{{ old('deskripsi') }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-perpus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Buku
                </button>
                <a href="{{ route('perpustakaan.admin.buku.index') }}" class="btn-warning">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
