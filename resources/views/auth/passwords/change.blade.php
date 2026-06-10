@php
    $from = request('from');
    $layout = $from === 'perpus' ? 'perpustakaan.layouts.app' : 'layouts.app';
    
    // Tentukan URL untuk tombol batal
    if ($from === 'perpus') {
        $cancelUrl = auth()->user()->role === 'admin_perpus' ? route('perpustakaan.admin.dashboard') : route('perpustakaan.siswa.dashboard');
    } else {
        $cancelUrl = auth()->user()->role === 'superadmin' ? route('superadmin.dashboard') : (auth()->user()->role === 'admin_bengkel' ? route('admin.dashboard') : route('siswa.dashboard'));
    }
@endphp
@extends($layout)

@section('title', 'Ganti Password')
@section('page-title', 'Ganti Password')
@section('page-subtitle', 'Ubah kata sandi Anda untuk keamanan akun')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200 bg-slate-50/50">
            <h3 class="font-bold text-slate-800">Form Ganti Password</h3>
            <p class="text-sm text-slate-500 mt-1">Pastikan password baru Anda mudah diingat dan aman.</p>
        </div>
        
        <form action="{{ route('password.update', ['from' => $from]) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1">Password Saat Ini</label>
                <input type="password" name="current_password" id="current_password" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" required>
                @error('current_password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
                <input type="password" name="password" id="password" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" required>
            </div>

            <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                <a href="{{ $cancelUrl }}" class="text-sm text-slate-500 hover:text-slate-800 font-medium">Batal</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Password Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
