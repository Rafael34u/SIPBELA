@extends('perpustakaan.layouts.app')

@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard Saya')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('content')
<!-- Alert terlambat -->
@if($terlambat > 0)
<div class="flex items-start gap-3 bg-red-50 border border-red-300 text-red-800 px-4 py-4 rounded-xl mb-6">
    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
    <div>
        <p class="font-bold">Peringatan! Kamu memiliki {{ $terlambat }} buku yang terlambat dikembalikan.</p>
        <p class="text-sm mt-0.5">Denda: <strong>Rp 1.000 per hari</strong> keterlambatan. Segera hubungi petugas perpustakaan.</p>
    </div>
</div>
@endif

<!-- Stats -->
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="stat-card text-center">
        <p id="stat-total-pinjam" class="text-3xl font-extrabold text-slate-800">{{ $totalPinjam }}</p>
        <p class="text-xs text-slate-500 font-medium mt-1">Total Pinjaman</p>
    </div>
    <div class="stat-card text-center">
        <p id="stat-aktif" class="text-3xl font-extrabold text-amber-600">{{ $aktif }}</p>
        <p class="text-xs text-slate-500 font-medium mt-1">Sedang Dipinjam</p>
    </div>
    <div class="stat-card text-center">
        <p id="stat-total-buku" class="text-3xl font-extrabold text-green-600">{{ $totalBuku }}</p>
        <p class="text-xs text-slate-500 font-medium mt-1">Koleksi Buku</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Peminjaman aktif -->
    <div class="card">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-800">Buku Sedang Dipinjam</h2>
            <a href="{{ route('perpustakaan.siswa.riwayat') }}" class="text-xs text-green-600 hover:underline font-semibold">Lihat Riwayat →</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($peminjamanAktif as $p)
            <div class="px-6 py-4 {{ $p->isTerlambat() ? 'bg-red-50/50' : '' }}">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-800 truncate">{{ $p->buku->judul }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $p->buku->penulis }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-xs {{ $p->isTerlambat() ? 'text-red-600 font-bold' : 'text-slate-500' }}">
                                Kembali: {{ $p->batas_kembali->format('d M Y') }}
                                @if($p->isTerlambat())
                                    (telat {{ $p->hariTerlambatSekarang() }} hari)
                                @endif
                            </span>
                        </div>
                    </div>
                    @if($p->isTerlambat())
                        <span class="badge-terlambat flex-shrink-0">⚠ Terlambat</span>
                    @else
                        <span class="badge-dipinjam flex-shrink-0">Dipinjam</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-slate-400">
                <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                Tidak ada buku yang sedang dipinjam.
                <div class="mt-2">
                    <a href="{{ route('perpustakaan.siswa.peminjaman.create') }}" class="text-green-600 text-sm font-semibold hover:underline">Pinjam sekarang →</a>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Buku tersedia -->
    <div class="card">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-800">Buku Tersedia</h2>
            <a href="{{ route('perpustakaan.siswa.buku.index') }}" class="text-xs text-green-600 hover:underline font-semibold">Lihat Semua →</a>
        </div>
        <div id="buku-tersedia-list" class="divide-y divide-slate-100">
            @forelse($bukuTersedia as $buku)
            <div class="px-6 py-3 flex items-center justify-between gap-3 hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-14 flex-shrink-0 bg-slate-100 rounded overflow-hidden shadow-sm border border-slate-200">
                        @if($buku->gambar)
                            <img src="{{ asset('storage/' . $buku->gambar) }}" alt="{{ $buku->judul }}" class="w-full h-full object-contain">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="font-medium text-slate-800 truncate text-sm">{{ $buku->judul }}</p>
                        <p class="text-xs text-slate-500">{{ $buku->penulis }} @if($buku->kategori) · <span class="text-green-600">{{ $buku->kategori }}</span>@endif</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">{{ $buku->stok }} stok</span>
                    <a href="{{ route('perpustakaan.siswa.peminjaman.create', ['buku_id' => $buku->id]) }}" class="btn-perpus py-1 px-3 text-xs">Pinjam</a>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-slate-400">Tidak ada buku tersedia.</div>
            @endforelse
        </div>
    </div>
</div>
@push('scripts')
<script>
    // ─── Real-time Polling & Pull-to-Refresh ────────────────────────────
    const POLL_INTERVAL = 5000;

    function refreshDashboard() {
        return fetch('{{ route("perpustakaan.siswa.api.data") }}', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            // Update stats
            const elTotal = document.getElementById('stat-total-pinjam');
            const elAktif = document.getElementById('stat-aktif');
            const elBuku  = document.getElementById('stat-total-buku');
            if (elTotal) elTotal.textContent = data.totalPinjam;
            if (elAktif) elAktif.textContent = data.aktif;
            if (elBuku)  elBuku.textContent  = data.totalBuku;

            // Update buku tersedia
            const bukuList = document.getElementById('buku-tersedia-list');
            if (bukuList && data.bukuTersedia) {
                if (data.bukuTersedia.length === 0) {
                    bukuList.innerHTML = '<div class="px-6 py-8 text-center text-slate-400">Tidak ada buku tersedia.</div>';
                } else {
                    bukuList.innerHTML = data.bukuTersedia.map(buku => {
                        const gambarHtml = buku.gambar
                            ? `<img src="/storage/${buku.gambar}" alt="${buku.judul}" class="w-full h-full object-contain">`
                            : `<div class="w-full h-full flex items-center justify-center text-slate-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg></div>`;
                        const kategori = buku.kategori ? ` · <span class="text-green-600">${buku.kategori}</span>` : '';
                        return `
                        <div class="px-6 py-3 flex items-center justify-between gap-3 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-14 flex-shrink-0 bg-slate-100 rounded overflow-hidden shadow-sm border border-slate-200">
                                    ${gambarHtml}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-slate-800 truncate text-sm">${buku.judul}</p>
                                    <p class="text-xs text-slate-500">${buku.penulis}${kategori}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">${buku.stok} stok</span>
                                <a href="/perpustakaan/siswa/peminjaman/buat?buku_id=${buku.id}" class="btn-perpus py-1 px-3 text-xs">Pinjam</a>
                            </div>
                        </div>`;
                    }).join('');
                }
            }
        })
        .catch(err => console.warn('Polling error:', err));
    }

    setInterval(refreshDashboard, POLL_INTERVAL);

    // --- Pull to Refresh Logic (Aman untuk Navbar) ---
    let touchStartY = 0;
    let isRefreshing = false;
    const pullRefreshEl = document.getElementById('pull-refresh');

    function triggerRefresh() {
        if (isRefreshing) return;
        isRefreshing = true;
        if(pullRefreshEl) pullRefreshEl.classList.remove('hidden');
        
        refreshDashboard().finally(() => {
            setTimeout(() => {
                if(pullRefreshEl) pullRefreshEl.classList.add('hidden');
                isRefreshing = false;
            }, 500);
        });
    }

    // Deteksi sentuhan hanya di window
    window.addEventListener('touchstart', e => {
        // Pastikan kita berada paling atas halaman
        if (window.scrollY <= 0) {
            touchStartY = e.touches[0].clientY;
        } else {
            touchStartY = 0;
        }
    }, {passive: true});

    window.addEventListener('touchmove', e => {
        if (touchStartY > 0 && !isRefreshing && window.scrollY <= 0) {
            const currentY = e.touches[0].clientY;
            if (currentY > touchStartY + 30) {
                if(pullRefreshEl) pullRefreshEl.classList.remove('hidden');
            }
        }
    }, {passive: true});

    window.addEventListener('touchend', e => {
        if (touchStartY > 0 && !isRefreshing) {
            const touchEndY = e.changedTouches[0].clientY;
            if (touchEndY > touchStartY + 60) {
                triggerRefresh();
            } else {
                if(pullRefreshEl) pullRefreshEl.classList.add('hidden');
            }
            touchStartY = 0;
        }
    });

    // Deteksi Scroll Mouse (Desktop)
    window.addEventListener('wheel', e => {
        // Cek jika posisi mentok di atas dan scroll (wheel) masih didorong ke atas
        if (window.scrollY <= 0 && e.deltaY < -20 && !isRefreshing) {
            triggerRefresh();
        }
    }, {passive: true});
</script>
@endpush
@endsection
