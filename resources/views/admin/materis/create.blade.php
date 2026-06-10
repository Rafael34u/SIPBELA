@extends('layouts.app')
@section('title', 'Upload Materi')
@section('page-title', 'Upload Materi Baru')
@section('page-subtitle', 'Unggah file PDF atau Word untuk siswa')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
            <div>
                <h2 class="font-semibold text-slate-800">Upload Materi Pembelajaran</h2>
                <p class="text-xs text-slate-400">Format: PDF, DOC, DOCX &bull; Maks 20 MB</p>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.materis.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            {{-- Judul --}}
            <div>
                <label for="judul" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Judul Materi <span class="text-red-500">*</span>
                </label>
                <input type="text" id="judul" name="judul" value="{{ old('judul') }}"
                    placeholder="Contoh: Modul Teknik Mesin Semester 1"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('judul') border-red-400 @enderror">
                @error('judul')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Deskripsi <span class="text-slate-400 font-normal text-xs">(opsional)</span>
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                    placeholder="Deskripsi singkat tentang materi ini..."
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none @error('deskripsi') border-red-400 @enderror">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- File Upload --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    File Materi <span class="text-red-500">*</span>
                </label>
                <div id="drop-zone"
                    class="relative border-2 border-dashed border-slate-200 rounded-xl p-8 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50/50 transition-all duration-200 @error('file') border-red-400 @enderror"
                    onclick="document.getElementById('file-input').click()">
                    <input type="file" id="file-input" name="file" accept=".pdf,.doc,.docx"
                        class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                        onchange="updateFileName(this)">
                    <div id="upload-icon" class="flex flex-col items-center gap-3">
                        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-700">Klik untuk pilih file</p>
                            <p class="text-xs text-slate-400 mt-1">PDF, DOC, DOCX &bull; Maksimal 20 MB</p>
                        </div>
                    </div>
                    <div id="file-selected" class="hidden flex-col items-center gap-3">
                        <div id="file-icon-container" class="w-14 h-14 rounded-2xl flex items-center justify-center">
                        </div>
                        <div>
                            <p id="file-name-display" class="text-sm font-semibold text-slate-700 break-all"></p>
                            <p id="file-size-display" class="text-xs text-slate-400 mt-0.5"></p>
                        </div>
                        <span class="text-xs text-blue-600 font-medium hover:underline">Ganti file</span>
                    </div>
                </div>
                @error('file')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Upload Materi
                </button>
                <a href="{{ route('admin.materis.index') }}" class="text-sm text-slate-500 hover:text-slate-700 font-medium">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function updateFileName(input) {
    const file = input.files[0];
    if (!file) return;

    const uploadIcon = document.getElementById('upload-icon');
    const fileSelected = document.getElementById('file-selected');
    const fileIconContainer = document.getElementById('file-icon-container');
    const fileNameDisplay = document.getElementById('file-name-display');
    const fileSizeDisplay = document.getElementById('file-size-display');

    // Determine file type
    const ext = file.name.split('.').pop().toLowerCase();
    const isPdf = ext === 'pdf';

    // Update icon
    fileIconContainer.className = `w-14 h-14 ${isPdf ? 'bg-red-100' : 'bg-blue-100'} rounded-2xl flex items-center justify-center`;
    fileIconContainer.innerHTML = `
        <svg class="w-7 h-7 ${isPdf ? 'text-red-600' : 'text-blue-600'}" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
        </svg>`;

    // Update text
    fileNameDisplay.textContent = file.name;
    const sizeKb = file.size >= 1048576
        ? (file.size / 1048576).toFixed(2) + ' MB'
        : (file.size / 1024).toFixed(1) + ' KB';
    fileSizeDisplay.textContent = sizeKb + ' · ' + ext.toUpperCase();

    uploadIcon.classList.add('hidden');
    fileSelected.classList.remove('hidden');
    fileSelected.classList.add('flex');
}
</script>
@endpush
@endsection
