@extends('perpustakaan.layouts.app')

@section('title', 'Data Anggota')
@section('page-title', 'Manajemen Anggota')
@section('page-subtitle', 'Daftar data siswa anggota perpustakaan (Read-only)')

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl text-sm mb-5 shadow-sm">
    <svg class="w-5 h-5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl text-sm mb-5 shadow-sm">
    <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9V7a1 1 0 112 0v2a1 1 0 11-2 0zm0 4a1 1 0 102 0 1 1 0 00-2 0z" clip-rule="evenodd"/></svg>
    <span>{{ session('error') }}</span>
</div>
@endif

<div class="card p-4 mb-6">
    <form method="GET" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari nama, NIS, username, kelas..."
            class="flex-1 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        <button type="submit" class="btn-perpus">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Cari
        </button>
        @if(request('search'))
            <a href="{{ route('perpustakaan.admin.users.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-colors flex items-center justify-center">Reset</a>
        @endif
    </form>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="table-th">No</th>
                    <th class="table-th">Nama</th>
                    <th class="table-th">NIS</th>
                    <th class="table-th">Username</th>
                    <th class="table-th">Jurusan</th>
                    <th class="table-th">Kelas</th>
                    <th class="table-th">Email</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $i => $user)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="table-td text-slate-400 text-xs">{{ $users->firstItem() + $i }}</td>
                    <td class="table-td">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm">{{ $user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="table-td text-slate-600 font-mono text-xs">{{ $user->nis ?? '-' }}</td>
                    <td class="table-td">
                        <span class="font-mono text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded">{{ $user->username }}</span>
                    </td>
                    <td class="table-td">
                        @if($user->jurusan)
                            <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $user->jurusan }}</span>
                        @else
                            <span class="text-slate-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="table-td text-slate-600 text-sm">{{ $user->kelas ?? '-' }}</td>
                    <td class="table-td text-slate-500 text-xs">{{ $user->email ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="table-td text-center text-slate-400 py-10">Belum ada data anggota.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">{{ $users->links() }}</div>
    @endif
</div>
@endsection
