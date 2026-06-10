@extends('layouts.app')
@section('title', 'Materi Pembelajaran')
@section('page-title', 'Materi Pembelajaran')
@section('page-subtitle', 'Unduh dan pelajari materi dari guru')

@section('content')
{{-- Info Banner --}}
<div class="flex items-center gap-3 bg-purple-50 border border-purple-100 rounded-xl px-4 py-3 mb-6">
    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <p class="text-sm text-purple-800">Klik tombol <strong>Unduh</strong> untuk mengunduh dan membuka materi.</p>
</div>

@if($materis->isEmpty())
{{-- Empty State --}}
<div class="card p-16 text-center">
    <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
    </div>
    <p class="text-slate-500 font-semibold">Belum ada materi</p>
    <p class="text-slate-400 text-sm mt-1">Materi akan muncul di sini ketika admin mengupload.</p>
</div>
@else
{{-- Materi Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($materis as $materi)
    <div class="card p-5 hover:shadow-md transition-all duration-200 flex flex-col">
        {{-- File Icon --}}
        <div class="flex items-start gap-4 mb-4">
            @if($materi->tipe_file === 'pdf')
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                </svg>
            </div>
            @else
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                </svg>
            </div>
            @endif
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    @if($materi->tipe_file === 'pdf')
                    <span style="display:inline-flex;align-items:center;padding:0.1rem 0.5rem;border-radius:9999px;font-size:0.65rem;font-weight:700;background:#fee2e2;color:#991b1b;letter-spacing:0.05em;">PDF</span>
                    @else
                    <span style="display:inline-flex;align-items:center;padding:0.1rem 0.5rem;border-radius:9999px;font-size:0.65rem;font-weight:700;background:#dbeafe;color:#1e40af;letter-spacing:0.05em;">WORD</span>
                    @endif
                    <span class="text-xs text-slate-400">{{ $materi->ukuranFormatted() }}</span>
                </div>
                <h3 class="font-semibold text-slate-800 text-sm leading-snug line-clamp-2">{{ $materi->judul }}</h3>
            </div>
        </div>

        @if($materi->deskripsi)
        <p class="text-xs text-slate-400 mb-4 line-clamp-2 flex-1">{{ $materi->deskripsi }}</p>
        @else
        <div class="flex-1"></div>
        @endif

        {{-- Footer --}}
        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
            <span class="text-xs text-slate-400">
                {{ $materi->created_at->format('d M Y') }}
            </span>
            <a href="{{ Storage::url($materi->path_file) }}" target="_blank" download
               class="btn-primary text-xs py-1.5 px-3">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh
            </a>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
