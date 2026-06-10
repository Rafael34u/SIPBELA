@extends('layouts.app')
@section('title', 'Manajemen Materi')
@section('page-title', 'Manajemen Materi')
@section('page-subtitle', 'Upload dan kelola materi pembelajaran untuk siswa')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div></div>
    <a href="{{ route('admin.materis.create') }}" class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
        </svg>
        Upload Materi
    </a>
</div>

<div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-3">
        <div class="w-9 h-9 bg-purple-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <div>
            <h2 class="font-semibold text-slate-800 text-sm">Daftar Materi</h2>
            <p class="text-xs text-slate-400">{{ $materis->count() }} materi tersedia</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="table-th">Judul</th>
                    <th class="table-th">Tipe</th>
                    <th class="table-th">Ukuran</th>
                    <th class="table-th">Diunggah Oleh</th>
                    <th class="table-th">Tanggal</th>
                    <th class="table-th">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($materis as $materi)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="table-td">
                        <div class="flex items-center gap-3">
                            {{-- Icon tipe file --}}
                            @if($materi->tipe_file === 'pdf')
                            <div class="w-9 h-9 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            @else
                            <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            @endif
                            <div>
                                <p class="font-medium text-slate-800 text-sm">{{ $materi->judul }}</p>
                                @if($materi->deskripsi)
                                <p class="text-xs text-slate-400 line-clamp-1">{{ $materi->deskripsi }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="table-td">
                        @if($materi->tipe_file === 'pdf')
                        <span style="display:inline-flex;align-items:center;padding:0.125rem 0.625rem;border-radius:9999px;font-size:0.75rem;font-weight:600;background:#fee2e2;color:#991b1b;">PDF</span>
                        @else
                        <span style="display:inline-flex;align-items:center;padding:0.125rem 0.625rem;border-radius:9999px;font-size:0.75rem;font-weight:600;background:#dbeafe;color:#1e40af;">Word</span>
                        @endif
                    </td>
                    <td class="table-td text-slate-500">{{ $materi->ukuranFormatted() }}</td>
                    <td class="table-td">{{ $materi->uploader->name ?? '-' }}</td>
                    <td class="table-td text-slate-500">{{ $materi->created_at->format('d/m/Y') }}</td>
                    <td class="table-td">
                        <div class="flex items-center gap-2">
                            <a href="{{ Storage::url($materi->path_file) }}" target="_blank" class="btn-info" title="Lihat / Unduh">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Unduh
                            </a>
                            <form action="{{ route('admin.materis.destroy', $materi) }}" method="POST"
                                  class="delete-form" data-message="Hapus materi ini?">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="table-td text-center text-slate-400 py-12">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <p class="text-sm font-medium">Belum ada materi yang diunggah.</p>
                        <a href="{{ route('admin.materis.create') }}" class="text-blue-600 text-xs hover:underline mt-1 inline-block">Upload materi pertama →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
