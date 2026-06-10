@extends('perpustakaan.layouts.app')

@section('title', 'Data Buku')
@section('page-title', 'Manajemen Buku')
@section('page-subtitle', 'Kelola koleksi buku perpustakaan')

@section('content')
<!-- Filter & Tambah -->
<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari judul, penulis, kategori..."
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
        <a href="{{ route('perpustakaan.admin.buku.create') }}" class="btn-perpus">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Buku
        </a>
    </form>
</div>

<!-- Table -->
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="table-th">No</th>
                    <th class="table-th">Judul & Penulis</th>
                    <th class="table-th">Penerbit</th>
                    <th class="table-th">Tahun</th>
                    <th class="table-th">Kategori</th>
                    <th class="table-th text-center">Stok</th>
                    <th class="table-th text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($bukus as $i => $buku)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="table-td text-slate-400 text-xs">{{ $bukus->firstItem() + $i }}</td>
                    <td class="table-td">
                        <div class="font-semibold text-slate-800">{{ $buku->judul }}</div>
                        <div class="text-xs text-slate-500">{{ $buku->penulis }}</div>
                    </td>
                    <td class="table-td text-slate-600">{{ $buku->penerbit ?? '-' }}</td>
                    <td class="table-td text-slate-600">{{ $buku->tahun ?? '-' }}</td>
                    <td class="table-td">
                        @if($buku->kategori)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">{{ $buku->kategori }}</span>
                        @else
                            <span class="text-slate-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="table-td text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-xs font-bold {{ $buku->stok > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                            {{ $buku->stok }}
                        </span>
                    </td>
                    <td class="table-td">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('perpustakaan.admin.buku.edit', $buku) }}" class="btn-warning">Edit</a>
                            <form action="{{ route('perpustakaan.admin.buku.destroy', $buku) }}" method="POST"
                                  class="delete-form" data-message="Hapus buku '{{ $buku->judul }}'?">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="table-td text-center text-slate-400 py-10">
                        <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                        Belum ada data buku.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bukus->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $bukus->links() }}
    </div>
    @endif
</div>
@endsection
