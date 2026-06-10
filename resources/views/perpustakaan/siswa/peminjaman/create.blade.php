@extends('perpustakaan.layouts.app')

@section('title', 'Pinjam Buku')
@section('page-title', 'Form Peminjaman Buku')
@section('page-subtitle', 'Pilih buku dan ajukan peminjaman')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-xl text-sm mb-6">
        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
        <div>
            <strong>Info Peminjaman:</strong>
            Batas peminjaman <strong>7 hari</strong> sejak tanggal peminjaman.
            Keterlambatan dikenakan denda <strong>Rp 1.000 per hari</strong>.
        </div>
    </div>

    <div class="card p-6">
        <form method="POST" action="{{ route('perpustakaan.siswa.peminjaman.store') }}" class="space-y-5">
            @csrf

            <!-- Pilih Buku -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pilih Buku <span class="text-red-500">*</span></label>
                <select name="buku_id" id="buku_id" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">-- Pilih buku --</option>
                    @foreach($bukus as $buku)
                        <option value="{{ $buku->id }}"
                            {{ (old('buku_id', $selectedBuku?->id) == $buku->id) ? 'selected' : '' }}>
                            {{ $buku->judul }} — {{ $buku->penulis }} (Stok: {{ $buku->stok }})
                        </option>
                    @endforeach
                </select>
                @error('buku_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                @if($selectedBuku)
                <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-xl text-sm">
                    <p class="font-semibold text-green-800">{{ $selectedBuku->judul }}</p>
                    <p class="text-green-600 text-xs mt-0.5">{{ $selectedBuku->penulis }} · {{ $selectedBuku->kategori ?? 'Umum' }}</p>
                    @if($selectedBuku->deskripsi)
                    <p class="text-green-700 text-xs mt-1 line-clamp-2">{{ $selectedBuku->deskripsi }}</p>
                    @endif
                </div>
                @endif
            </div>

            <!-- Info Tanggal (otomatis) -->
            <div class="grid grid-cols-2 gap-4">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-xs font-semibold text-slate-500 mb-1">Tanggal Pinjam</p>
                    <p class="font-bold text-slate-800">{{ now()->format('d F Y') }}</p>
                    <p class="text-xs text-slate-400">Otomatis</p>
                </div>
                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200">
                    <p class="text-xs font-semibold text-amber-600 mb-1">Batas Kembali</p>
                    <p class="font-bold text-amber-800">{{ now()->addDays(7)->format('d F Y') }}</p>
                    <p class="text-xs text-amber-500">+7 hari dari sekarang</p>
                </div>
            </div>

            <!-- Catatan -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Catatan <span class="text-slate-400 font-normal text-xs">(opsional)</span></label>
                <textarea name="catatan" rows="2"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"
                    placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-perpus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Ajukan Peminjaman
                </button>
                <a href="{{ route('perpustakaan.siswa.buku.index') }}" class="btn-warning">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
