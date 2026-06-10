@extends('perpustakaan.layouts.app')

@section('title', 'Katalog Buku')
@section('page-title', 'Katalog Buku')
@section('page-subtitle', 'Temukan buku yang ingin kamu pinjam')

@section('content')
<!-- Filter -->
<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari judul, penulis, penerbit..."
            class="flex-1 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        <select name="kategori" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $k)
                <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-perpus">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Cari
        </button>
    </form>
</div>

<!-- Grid Buku -->
<div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
    @forelse($bukus as $buku)
    <div class="card overflow-hidden hover:shadow-md transition-all duration-200 hover:-translate-y-1 flex flex-col">
        <!-- Book Header -->
        <div class="h-40 sm:h-48 flex items-center justify-center relative overflow-hidden bg-slate-100">
            @if($buku->gambar)
                <img src="{{ asset('storage/' . $buku->gambar) }}" alt="{{ $buku->judul }}" class="w-full h-full object-contain">
            @else
                <div class="w-full h-full bg-gradient-to-br from-green-600 to-green-800 flex items-center justify-center">
                    <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            @endif
            <!-- Stok badge -->
            <div class="absolute top-2 right-2">
                <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $buku->stok > 0 ? 'bg-green-400/90 text-white' : 'bg-red-500/90 text-white shadow-lg' }}">
                    {{ $buku->stok > 0 ? $buku->stok . ' tersedia' : 'Habis' }}
                </span>
            </div>
            @if($buku->kategori)
            <div class="absolute bottom-2 left-2">
                <span class="text-xs bg-slate-900/40 backdrop-blur-sm text-white px-2 py-0.5 rounded-full">{{ $buku->kategori }}</span>
            </div>
            @endif
        </div>

        <div class="p-3 sm:p-4 flex-1 flex flex-col">
            <h3 class="font-bold text-slate-800 text-xs sm:text-sm leading-snug mb-1 line-clamp-2 min-h-[2.5rem]">{{ $buku->judul }}</h3>
            <p class="text-[10px] sm:text-xs text-slate-500 mb-1 truncate">{{ $buku->penulis }}</p>
            @if($buku->penerbit)
                <p class="text-[9px] sm:text-xs text-slate-400 truncate">{{ $buku->penerbit }} @if($buku->tahun)· {{ $buku->tahun }}@endif</p>
            @endif
            
            <div class="mt-auto pt-3">
                @if($buku->stok > 0)
                    <a href="{{ route('perpustakaan.siswa.peminjaman.create', ['buku_id' => $buku->id]) }}"
                        class="btn-perpus w-full justify-center text-[10px] sm:text-xs py-2 px-1">
                        Pinjam Buku
                    </a>
                @else
                    <button disabled class="w-full text-center text-[10px] sm:text-xs py-2 bg-slate-100 text-slate-400 rounded-lg font-semibold cursor-not-allowed">
                        Stok Habis
                    </button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-16 text-slate-400">
        <svg class="w-16 h-16 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
        Buku tidak ditemukan.
    </div>
    @endforelse
</div>

@if($bukus->hasPages())
<div class="mt-6">{{ $bukus->links() }}</div>
@endif
@endsection
