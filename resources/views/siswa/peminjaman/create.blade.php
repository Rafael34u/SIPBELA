@extends('layouts.app')
@section('title', 'Form Peminjaman')
@section('page-title', 'Formulir Peminjaman Alat')
@section('page-subtitle', 'Isi form berikut untuk meminjam alat bengkel')

@section('content')
<div class="max-w-xl">
    <div class="card p-6">

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-5">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('siswa.peminjaman.store') }}">
            @csrf

            <!-- Pilih Barang -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Pilih Alat yang Dipinjam <span class="text-red-500">*</span>
                </label>
                <select name="barang_id" id="barang_id" required
                    class="w-full truncate border @error('barang_id') border-red-400 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
                    onchange="updateBarangInfo(this)">
                    <option value="">-- Pilih Alat Bengkel --</option>
                    @foreach($barangs as $b)
                    <option value="{{ $b->id }}"
                        data-nama="{{ $b->nama_barang }}"
                        data-stok="{{ $b->stok }}"
                        data-kode="{{ $b->kode_barang }}"
                        data-deskripsi="{{ $b->deskripsi }}"
                        {{ old('barang_id', $selected_barang?->id ?? '') == $b->id ? 'selected' : '' }}>
                        {{ Str::limit($b->nama_barang, 25) }} (Stok: {{ $b->stok }})
                    </option>
                    @endforeach
                </select>
                @error('barang_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                <!-- Info Barang -->
                <div id="barang-info" class="mt-3 p-3 bg-blue-50 border border-blue-100 rounded-xl text-sm hidden">
                    <p class="text-blue-700 font-medium" id="info-nama"></p>
                    <p class="text-blue-600 text-xs mt-1" id="info-detail"></p>
                </div>
            </div>

            <!-- Tanggal Pinjam -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Tanggal Peminjaman <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal_pinjam"
                    value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                    min="{{ date('Y-m-d') }}"
                    required
                    class="w-full border @error('tanggal_pinjam') border-red-400 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('tanggal_pinjam')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Catatan -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Catatan / Keperluan <span class="text-slate-400 font-normal">(opsional)</span>
                </label>
                <textarea name="catatan" rows="3"
                    placeholder="Contoh: Untuk praktikum mesin semester 2"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('catatan') }}</textarea>
            </div>

            <!-- Info -->
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 text-xs text-amber-700">
                <p class="font-semibold mb-1">⚠️ Perhatian:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Stok barang akan berkurang secara otomatis setelah pengajuan berhasil</li>
                    <li>Pastikan barang dikembalikan tepat waktu dalam kondisi baik</li>
                    <li>Hubungi admin bengkel untuk proses pengembalian</li>
                </ul>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex-1 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Ajukan Peminjaman
                </button>
                <a href="{{ route('siswa.dashboard') }}"
                    class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg text-sm hover:bg-slate-50 font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function updateBarangInfo(select) {
    const opt = select.options[select.selectedIndex];
    const info = document.getElementById('barang-info');
    if (!opt.value) { info.classList.add('hidden'); return; }
    document.getElementById('info-nama').textContent = opt.dataset.nama;
    document.getElementById('info-detail').textContent =
        'Kode: ' + opt.dataset.kode + ' | Stok tersedia: ' + opt.dataset.stok +
        (opt.dataset.deskripsi ? ' | ' + opt.dataset.deskripsi : '');
    info.classList.remove('hidden');
}
// Trigger on load if pre-selected
window.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('barang_id');
    if (sel.value) updateBarangInfo(sel);
});
</script>
@endpush
@endsection
