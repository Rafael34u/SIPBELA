@extends('superadmin.layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('superadmin.dashboard') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors inline-flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Dashboard
    </a>
</div>

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h2 class="text-3xl font-extrabold text-slate-800 font-display">Daftar NIS Terverifikasi</h2>
        <p class="text-slate-500 mt-1">Daftar NIS siswa resmi yang diperbolehkan untuk mendaftarkan akun di sistem.</p>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
<div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl text-sm mb-6 shadow-sm">
    <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    <span class="font-medium">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl text-sm mb-6 shadow-sm">
    <svg class="w-5 h-5 flex-shrink-0 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9V7a1 1 0 112 0v2a1 1 0 11-2 0zm0 4a1 1 0 102 0 1 1 0 00-2 0z" clip-rule="evenodd"/></svg>
    <span class="font-medium">{{ session('error') }}</span>
</div>
@endif

@if($errors->any())
<div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl text-sm mb-6 shadow-sm">
    <p class="font-bold mb-2">Terjadi kesalahan input:</p>
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Form Manual & Import -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Tambah Manual -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <h3 class="text-base font-bold text-slate-800 mb-4">Tambah Siswa (Manual)</h3>
            <form action="{{ route('superadmin.siswa.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="nis" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">NIS</label>
                    <input type="text" id="nis" name="nis" required placeholder="Contoh: 12345" value="{{ old('nis') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label for="nama" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" required placeholder="Nama Siswa" value="{{ old('nama') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="kelas" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kelas</label>
                        <input type="text" id="kelas" name="kelas" placeholder="XI TKJ 1" value="{{ old('kelas') }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label for="jurusan" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jurusan</label>
                        <select id="jurusan" name="jurusan" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="">Pilih</option>
                            <option value="TKJ" {{ old('jurusan') == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                            <option value="RPL" {{ old('jurusan') == 'RPL' ? 'selected' : '' }}>RPL</option>
                            <option value="TKR" {{ old('jurusan') == 'TKR' ? 'selected' : '' }}>TKR</option>
                            <option value="MM" {{ old('jurusan') == 'MM' ? 'selected' : '' }}>MM</option>
                            <option value="DG" {{ old('jurusan') == 'DG' ? 'selected' : '' }}>DG</option>
                            <option value="TEI" {{ old('jurusan') == 'TEI' ? 'selected' : '' }}>TEI</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition-all shadow-md shadow-indigo-600/10">
                    Simpan ke NIS Terverifikasi
                </button>
            </form>
        </div>

        <!-- Import Excel/CSV -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <h3 class="text-base font-bold text-slate-800 mb-2">Import Data NIS</h3>
            <p class="text-slate-400 text-xs mb-4 leading-relaxed">Format header baris pertama file Excel/CSV wajib berupa: <code class="bg-slate-100 text-indigo-600 px-1 py-0.5 rounded font-mono font-semibold">nis, nama, jurusan, kelas</code></p>
            <form action="{{ route('superadmin.siswa.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Pilih File (.xlsx, .csv, .xls)</label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-dashed border-slate-200 p-2.5 rounded-xl bg-slate-50 focus:outline-none transition-all">
                </div>
                <button type="submit" class="w-full bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold py-2.5 px-4 rounded-xl text-sm transition-all border border-indigo-200">
                    Upload & Impor Data
                </button>
            </form>
        </div>
    </div>

    <!-- Table List Whitelist -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <!-- Search & Actions Header -->
            <div class="p-6 border-b border-slate-100">
                <form method="GET" action="{{ route('superadmin.siswa.index') }}" class="flex gap-3">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan Nama, NIS, Kelas, atau Jurusan..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-colors">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('superadmin.siswa.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold px-4 py-2.5 rounded-xl text-sm transition-colors flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">NIS (Terverifikasi)</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Jurusan</th>
                            <th class="px-6 py-3.5 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($siswa as $i => $item)
                            @php
                                $isTkr = $item->jurusan === 'TKR';
                                $badgeColor = $isTkr ? 'bg-rose-50 text-rose-700' : 'bg-indigo-50 text-indigo-700';
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-slate-400 text-xs font-medium">{{ $siswa->firstItem() + $i }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-700 font-mono text-sm font-semibold">{{ $item->nis }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-800 text-sm font-medium">{{ $item->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600 text-sm">{{ $item->kelas ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->jurusan)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                            {{ $item->jurusan }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('superadmin.siswa.edit', $item->id) }}" class="bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold text-xs border border-amber-200 px-2.5 py-1.5 rounded-lg transition-all">
                                            Edit
                                        </a>
                                        <form action="{{ route('superadmin.siswa.destroy', $item->id) }}" method="POST" class="inline-block delete-form" data-name="{{ $item->nama }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this)" class="bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs border border-rose-200 px-2.5 py-1.5 rounded-lg transition-all">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-sm">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                        <span>Data master NIS terverifikasi kosong atau tidak ditemukan.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($siswa->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $siswa->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(button) {
    const form = button.closest('form');
    const name = form.getAttribute('data-name');
    
    Swal.fire({
        title: 'Hapus dari Daftar?',
        text: `Apakah Anda yakin ingin menghapus ${name} dari daftar NIS terverifikasi? Siswa tersebut tidak akan bisa melakukan registrasi baru menggunakan NIS ini.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        customClass: {
            confirmButton: 'rounded-xl px-5 py-2.5 text-sm font-semibold shadow-md',
            cancelButton: 'rounded-xl px-5 py-2.5 text-sm font-semibold'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>
@endpush
