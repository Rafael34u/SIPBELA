@extends('perpustakaan.layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Perpustakaan')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('content')
<!-- Stat Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Total Buku -->
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-slate-800">{{ $totalBuku }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Total Koleksi Buku</p>
    </div>

    <!-- Total Anggota -->
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-slate-800">{{ $totalAnggota }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Anggota Siswa</p>
    </div>

    <!-- Peminjaman Aktif -->
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-slate-800">{{ $peminjamanAktif }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Sedang Dipinjam</p>
        @if($terlambat > 0)
        <span class="inline-flex items-center gap-1 text-xs text-red-600 font-semibold mt-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ $terlambat }} terlambat
        </span>
        @endif
    </div>

    <!-- Total Denda -->
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-slate-800">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Total Denda Terkumpul</p>
    </div>
</div>

<!-- Peminjaman Terbaru -->
<div class="card">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h2 class="font-bold text-slate-800">Transaksi Peminjaman Terbaru</h2>
        <a href="{{ route('perpustakaan.admin.peminjaman.index') }}" class="text-xs text-green-600 font-semibold hover:underline">Lihat Semua →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="table-th">Anggota</th>
                    <th class="table-th">Buku</th>
                    <th class="table-th">Tgl Pinjam</th>
                    <th class="table-th">Batas Kembali</th>
                    <th class="table-th">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($peminjamanTerbaru as $p)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="table-td">
                        <div class="font-semibold text-slate-800">{{ $p->user->name }}</div>
                        <div class="text-xs text-slate-400">{{ $p->user->kelas ?? '-' }}</div>
                    </td>
                    <td class="table-td">
                        <div class="font-medium text-slate-700">{{ Str::limit($p->buku->judul, 30) }}</div>
                        <div class="text-xs text-slate-400">{{ $p->buku->penulis }}</div>
                    </td>
                    <td class="table-td text-slate-600">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td class="table-td">
                        <span class="{{ $p->isTerlambat() ? 'text-red-600 font-semibold' : 'text-slate-600' }}">
                            {{ $p->batas_kembali->format('d/m/Y') }}
                        </span>
                    </td>
                    <td class="table-td">
                        @if($p->status === 'dikembalikan')
                            <span class="badge-dikembalikan">Dikembalikan</span>
                        @elseif($p->status === 'menunggu_konfirmasi')
                            <span class="badge-menunggu">⏳ Menunggu Konfirmasi</span>
                        @elseif($p->isTerlambat())
                            <span class="badge-terlambat">⚠ Terlambat</span>
                        @else
                            <span class="badge-dipinjam">Dipinjam</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="table-td text-center text-slate-400 py-8">Belum ada transaksi peminjaman.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
