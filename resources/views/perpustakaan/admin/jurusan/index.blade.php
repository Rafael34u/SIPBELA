@extends('perpustakaan.layouts.app')

@section('title', 'Manajemen Jurusan')
@section('page-title', 'Manajemen Jurusan')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form Tambah -->
    <div class="lg:col-span-1">
        <div class="card p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Tambah Jurusan</h3>
            <form action="{{ route('perpustakaan.admin.jurusan.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Jurusan</label>
                    <input type="text" name="nama_jurusan" required 
                           class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                           placeholder="Contoh: Teknik Komputer & Jaringan">
                </div>
                <button type="submit" class="btn-perpus w-full justify-center">
                    Simpan Jurusan
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar -->
    <div class="lg:col-span-2">
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="table-th">#</th>
                            <th class="table-th">Nama Jurusan</th>
                            <th class="table-th text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($jurusans as $j)
                        <tr>
                            <td class="table-td w-12">{{ $loop->iteration }}</td>
                            <td class="table-td font-medium">{{ $j->nama_jurusan }}</td>
                            <td class="table-td text-right">
                                <div class="flex justify-end gap-2">
                                    <!-- Edit Trigger (Modal or Simple Inline) -->
                                    <button onclick="editJurusan({{ $j->id }}, '{{ $j->nama_jurusan }}')" 
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <form action="{{ route('perpustakaan.admin.jurusan.destroy', $j->id) }}" method="POST" class="delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="table-td text-center py-8 text-slate-400">Belum ada data jurusan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Edit Jurusan</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-edit" method="POST" class="p-6">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Jurusan</label>
                <input type="text" name="nama_jurusan" id="edit-nama" required 
                       class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-slate-600 font-medium">Batal</button>
                <button type="submit" class="btn-perpus">Perbarui</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function editJurusan(id, nama) {
        const modal = document.getElementById('modal-edit');
        const form = document.getElementById('form-edit');
        const input = document.getElementById('edit-nama');
        
        form.action = `/perpustakaan/admin/jurusan/${id}`;
        input.value = nama;
        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modal-edit').classList.add('hidden');
    }
</script>
@endpush
@endsection
