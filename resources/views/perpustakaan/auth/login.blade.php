@extends('perpustakaan.layouts.auth')

@section('title', 'Login Perpustakaan')

@section('content')
<div class="glass-card rounded-2xl shadow-2xl overflow-hidden">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-green-700 to-green-600 p-8 text-center">
        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <h1 class="text-2xl font-extrabold text-white tracking-tight">SIPB</h1>
        <p class="text-green-200 text-sm mt-1 font-medium">Sistem Informasi Perpustakaan</p>
        <p class="text-green-300 text-xs mt-0.5">SMK Ma'arif Talang</p>
    </div>

    <!-- Form -->
    <div class="p-8">
        @if(session('success'))
        <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm mb-5">
            <svg class="w-4 h-4 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm mb-5">
            {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('perpustakaan.login.post') }}" class="space-y-5">
            @csrf
            <div>
                <label for="username" class="block text-sm font-semibold text-slate-700 mb-1.5">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus
                        class="w-full pl-10 pr-4 py-2.5 border {{ $errors->has('username') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                        placeholder="Masukkan username" />
                </div>
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                    <button type="button" onclick="showLupaPassword()" class="text-xs font-bold text-green-600 hover:text-green-700 transition-colors focus:outline-none">
                        Lupa Password?
                    </button>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <input id="password" name="password" type="password" required
                        class="w-full pl-10 pr-4 py-2.5 border border-slate-200 bg-slate-50 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                        placeholder="Masukkan password" />
                </div>
            </div>

            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox" class="w-4 h-4 text-green-600 border-slate-300 rounded">
                <label for="remember" class="ml-2 text-sm text-slate-600">Ingat saya</label>
            </div>

            <button type="submit" id="btn-login"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 shadow-md hover:shadow-lg hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                Masuk ke Perpustakaan
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-slate-100 text-center">
            <p class="text-sm text-slate-500">
                Belum terdaftar?
                <a href="{{ route('perpustakaan.register') }}" class="text-green-600 hover:text-green-700 font-semibold transition-colors">
                    Daftar sekarang
                </a>
            </p>
            <p class="text-xs text-slate-400 mt-1">Pastikan NIS Anda sudah terdaftar di sistem sekolah.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showLupaPassword() {
    Swal.fire({
        title: 'Lupa Password?',
        html: `
            <div class="text-sm text-slate-600 space-y-3">
                <p>Sistem perpustakaan tidak menyediakan fitur ganti password mandiri.</p>
                <div class="bg-green-50 p-4 rounded-xl border border-green-100 text-left">
                    <p class="font-semibold text-green-800 mb-1">Cara Reset Password:</p>
                    <ol class="list-decimal list-inside text-green-700 space-y-1">
                        <li>Silakan kunjungi <strong>Petugas/Admin Perpustakaan</strong>.</li>
                        <li>Beritahu <strong>NIS</strong> Anda.</li>
                        <li>Admin akan membantu mereset password akun Anda (Password akan di-reset menjadi NIS).</li>
                    </ol>
                </div>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Baik, Saya Mengerti',
        confirmButtonColor: '#16a34a',
        customClass: {
            confirmButton: 'rounded-xl px-6 py-2.5 font-semibold text-sm shadow-md',
            popup: 'rounded-2xl'
        }
    });
}
</script>
@endpush
@endsection
