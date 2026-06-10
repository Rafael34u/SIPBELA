@extends('superadmin.layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('superadmin.siswa.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors inline-flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Daftar NIS
    </a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm relative overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 rounded-full bg-gradient-to-br from-indigo-50 to-pink-50 opacity-50 blur-2xl pointer-events-none"></div>
        
        <div class="border-b border-slate-100 pb-5 mb-6 relative">
            <h2 class="text-2xl font-extrabold text-slate-800 font-display">Edit Master Siswa</h2>
            <p class="text-slate-500 mt-1 text-sm">Perbarui data NIS Terverifikasi untuk <strong>{{ $siswa->nama }}</strong>.</p>
        </div>

        @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-xl text-sm mb-6 shadow-sm">
            <p class="font-bold mb-1.5">Terjadi kesalahan input:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('superadmin.siswa.update', $siswa->id) }}" method="POST" class="space-y-5 relative">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label for="nis" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">NIS Terverifikasi</label>
                    <input type="text" id="nis" name="nis" required placeholder="Contoh: 12345" value="{{ old('nis', $siswa->nis) }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                <div>
                    <label for="nama" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" required placeholder="Nama Siswa" value="{{ old('nama', $siswa->nama) }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="jurusan" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jurusan</label>
                    <select id="jurusan" name="jurusan" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="">Pilih Jurusan</option>
                        @php
                            $jurusans = ['TKJ', 'RPL', 'TKR', 'MM', 'DG', 'TEI'];
                        @endphp
                        @foreach($jurusans as $jur)
                            <option value="{{ $jur }}" {{ old('jurusan', $siswa->jurusan) == $jur ? 'selected' : '' }}>{{ $jur }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="kelas" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kelas</label>
                    <input type="text" id="kelas" name="kelas" required placeholder="Contoh: XI TKR 1" value="{{ old('kelas', $siswa->kelas) }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="bg-indigo-50/50 border border-indigo-100 p-4 rounded-2xl">
                <p class="text-xs text-indigo-700 leading-normal">
                    💡 <strong>Informasi:</strong> Jika NIS ini sudah dibuatkan akun login, maka data pada akun login akan otomatis diperbarui mengikuti perubahan ini.
                </p>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-bold py-3 px-4 rounded-xl text-sm transition-all shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40">
                    Simpan Perubahan
                </button>
                <a href="{{ route('superadmin.siswa.index') }}" class="px-6 py-3 border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold rounded-xl text-sm transition-colors text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
