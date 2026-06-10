@extends('layouts.app')
@section('title', 'Edit Siswa')
@section('page-title', 'Edit Siswa (TKR)')
@section('page-subtitle', 'Perbarui informasi dan kata sandi siswa khusus Jurusan TKR')
@section('content')
<div class="max-w-2xl">
    <div class="card p-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Nama Lengkap --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full border @error('name') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- NIS + Username --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">NIS <span class="text-red-500">*</span></label>
                    <input type="text" name="nis" value="{{ old('nis', $user->nis) }}" required
                        class="w-full border @error('nis') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('nis')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                        class="w-full border @error('username') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('username')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    placeholder="nama@email.com"
                    class="w-full border @error('email') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Jurusan + Kelas --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Jurusan</label>
                    <input type="text" name="jurusan" value="TKR" readonly
                        class="w-full bg-slate-50 border border-slate-200 text-slate-500 rounded-lg px-3 py-2.5 text-sm focus:outline-none cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Kelas <span class="text-red-500">*</span></label>
                    <input type="text" name="kelas" value="{{ old('kelas', $user->kelas) }}" required
                        placeholder="Contoh: XI TKR 1"
                        class="w-full border @error('kelas') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('kelas')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Ganti Password Section --}}
            <div class="mt-6 border-t border-slate-200 pt-6">
                <h3 class="font-semibold text-slate-800 text-sm mb-1">Ganti Kata Sandi <span class="font-normal text-slate-400">(Opsional)</span></h3>
                <p class="text-xs text-slate-500 mb-4">Biarkan kosong jika tidak ingin mengubah kata sandi saat ini.</p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Kata Sandi Baru</label>
                        <input type="password" name="password"
                            class="w-full border @error('password') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation"
                            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3 mt-8 pt-4 border-t border-slate-100">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}"
                    class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg text-sm hover:bg-slate-50 font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
