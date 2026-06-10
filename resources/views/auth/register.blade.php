@extends('layouts.auth')

@section('content')
<div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">

    <!-- Logo & Header -->
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-gradient-to-tr from-blue-600 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-500/20">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
        </div>
        <h1 class="text-2xl font-extrabold text-white tracking-tight">Daftar Akun Siswa</h1>
        <p class="text-slate-300 text-sm mt-1">Portal Layanan Terpadu Sekolah</p>
        <p class="text-blue-400 text-xs mt-1 font-medium">SMK Ma'arif Talang</p>
    </div>

    @if (session('error'))
    <div class="bg-red-500/20 border border-red-500/30 text-red-300 px-4 py-3 rounded-xl text-sm mb-5 font-medium text-center">
        {{ session('error') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-500/20 border border-red-500/30 text-red-300 px-4 py-3 rounded-xl text-sm mb-5">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}" id="form-register" class="space-y-4">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <label for="name" class="block text-sm font-medium text-slate-300 mb-1.5">Nama Lengkap <span class="text-red-400">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}"
                placeholder="Masukkan nama lengkap" required
                class="w-full bg-white/10 border @error('name') border-red-500 @else border-white/20 @enderror
                       text-white placeholder-slate-500 rounded-xl px-4 py-3 text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
        </div>

        <!-- NIS -->
        <div>
            <label for="nis" class="block text-sm font-medium text-slate-300 mb-1.5">NIS <span class="text-red-400">*</span> <span class="text-slate-500 text-xs">(Nomor Induk Siswa)</span></label>
            <input type="text" id="nis" name="nis" value="{{ old('nis') }}"
                placeholder="Contoh: 12345" required
                class="w-full bg-white/10 border @error('nis') border-red-500 @else border-white/20 @enderror
                       text-white placeholder-slate-500 rounded-xl px-4 py-3 text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            @error('nis')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Username -->
        <div>
            <label for="username" class="block text-sm font-medium text-slate-300 mb-1.5">Username <span class="text-red-400">*</span></label>
            <input type="text" id="username" name="username" value="{{ old('username') }}"
                placeholder="Contoh: budi2025" required
                class="w-full bg-white/10 border @error('username') border-red-500 @else border-white/20 @enderror
                       text-white placeholder-slate-500 rounded-xl px-4 py-3 text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            @error('username')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email <span class="text-red-400">*</span></label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                placeholder="nama@email.com" required
                class="w-full bg-white/10 border @error('email') border-red-500 @else border-white/20 @enderror
                       text-white placeholder-slate-500 rounded-xl px-4 py-3 text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Jurusan & Kelas (grid) -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="jurusan" class="block text-sm font-medium text-slate-300 mb-1.5">Jurusan <span class="text-red-400">*</span></label>
                <select id="jurusan" name="jurusan" required
                    class="w-full bg-white/10 border border-white/20 text-slate-300 rounded-xl px-4 py-3 text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all
                           [&>option]:bg-slate-800 [&>option]:text-white">
                    <option value="">-- Pilih --</option>
                    @foreach($jurusans as $j)
                        <option value="{{ $j }}" {{ old('jurusan') == $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="kelas" class="block text-sm font-medium text-slate-300 mb-1.5">Kelas <span class="text-red-400">*</span></label>
                <input id="kelas" name="kelas" type="text" value="{{ old('kelas') }}"
                    placeholder="Contoh: XI TKJ 1" required
                    class="w-full bg-white/10 border border-white/20 text-white placeholder-slate-500 rounded-xl px-4 py-3 text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                @error('kelas')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Password <span class="text-red-400">*</span></label>
            <div class="relative">
                <input type="password" id="password" name="password"
                    placeholder="Minimal 6 karakter" required
                    class="w-full bg-white/10 border @error('password') border-red-500 @else border-white/20 @enderror
                           text-white placeholder-slate-500 rounded-xl px-4 py-3 pr-12 text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                <button type="button" onclick="togglePass('password')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Konfirmasi Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1.5">Konfirmasi Password <span class="text-red-400">*</span></label>
            <div class="relative">
                <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Ulangi password" required
                    class="w-full bg-white/10 border border-white/20
                           text-white placeholder-slate-500 rounded-xl px-4 py-3 pr-12 text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                <button type="button" onclick="togglePass('password_confirmation')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            <p id="password-match-error" class="text-red-400 text-xs mt-1 hidden">Konfirmasi password berbeda dengan password</p>
        </div>

        <!-- Submit -->
        <button type="submit" id="btn-register"
            class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 px-4 rounded-xl
                   transition-all duration-200 text-sm shadow-lg shadow-blue-600/30
                   focus:outline-none focus:ring-2 focus:ring-blue-500 mt-2
                   disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none">
            Buat Akun
        </button>
    </form>

    <!-- Link Login -->
    <div class="mt-6 text-center">
        <p class="text-slate-500 text-sm">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 font-medium transition-colors">
                Masuk di sini
            </a>
        </p>
    </div>
</div>

<script>
function togglePass(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}

document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const registerBtn = document.getElementById('btn-register');
    const errorMsg = document.getElementById('password-match-error');

    function validatePassword() {
        const password = passwordInput.value;
        const confirmPassword = confirmInput.value;

        // Validasi hanya jika form konfirmasi password sudah mulai diisi
        if (confirmPassword.length > 0) {
            if (password !== confirmPassword) {
                // Berikan border merah ke input konfirmasi password
                confirmInput.classList.add('border-red-500');
                confirmInput.classList.remove('border-white/20');
                
                // Tampilkan pesan error
                errorMsg.classList.remove('hidden');
                
                // Disable tombol register
                registerBtn.disabled = true;
            } else {
                // Kembalikan border seperti semula
                confirmInput.classList.remove('border-red-500');
                confirmInput.classList.add('border-white/20');
                
                // Sembunyikan pesan error
                errorMsg.classList.add('hidden');
                
                // Enable tombol register
                registerBtn.disabled = false;
            }
        } else {
            // Jika konfirmasi password kosong, reset state
            confirmInput.classList.remove('border-red-500');
            confirmInput.classList.add('border-white/20');
            errorMsg.classList.add('hidden');
            registerBtn.disabled = false;
        }
    }

    passwordInput.addEventListener('input', validatePassword);
    confirmInput.addEventListener('input', validatePassword);
});
</script>
@endsection
