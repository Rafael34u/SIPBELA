@extends('superadmin.layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('superadmin.admins.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors inline-flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Kelola Admin
    </a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm relative overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 rounded-full bg-gradient-to-br from-indigo-50 to-emerald-50 opacity-50 blur-2xl pointer-events-none"></div>
        
        <div class="border-b border-slate-100 pb-5 mb-6 relative">
            <h2 class="text-2xl font-extrabold text-slate-800 font-display">Daftarkan Admin Baru</h2>
            <p class="text-slate-500 mt-1 text-sm">Buat akun untuk Admin Bengkel atau Admin Perpustakaan.</p>
        </div>

        @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-xl text-sm mb-6 shadow-sm relative">
            <p class="font-bold mb-1.5">Terjadi kesalahan input:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('superadmin.admins.store') }}" method="POST" class="space-y-5 relative">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input type="text" id="name" name="name" required placeholder="Nama Admin" value="{{ old('name') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                <div>
                    <label for="role" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Posisi (Role)</label>
                    <select id="role" name="role" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="">Pilih Posisi</option>
                        <option value="admin_bengkel" {{ old('role') == 'admin_bengkel' ? 'selected' : '' }}>Admin Bengkel</option>
                        <option value="admin_perpus" {{ old('role') == 'admin_perpus' ? 'selected' : '' }}>Admin Perpustakaan</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="username" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Username</label>
                    <input type="text" id="username" name="username" required placeholder="Contoh: admin_perpus1" value="{{ old('username') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email (Opsional)</label>
                    <input type="email" id="email" name="email" placeholder="Contoh: admin@sekolah.sch.id" value="{{ old('email') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Min. 6 Karakter"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi Password"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-bold py-3 px-4 rounded-xl text-sm transition-all shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40">
                    Daftarkan Admin
                </button>
                <a href="{{ route('superadmin.admins.index') }}" class="px-6 py-3 border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold rounded-xl text-sm transition-colors text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
