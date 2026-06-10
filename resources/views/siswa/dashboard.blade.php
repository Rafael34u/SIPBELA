@extends('layouts.app')
@section('title', 'Katalog Alat Bengkel')
@section('page-title', 'Katalog Alat Bengkel')
@section('page-subtitle', 'Pilih alat yang ingin dipinjam')

@section('content')
<!-- Welcome & Stats -->
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-slate-500 text-sm">Selamat datang, <span class="font-semibold text-slate-800">{{ auth()->user()->name }}</span></p>
    </div>
    <div class="flex gap-3">
        <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-2 text-center">
            <p class="text-xl font-bold text-amber-600">{{ $stats['sedang_dipinjam'] }}</p>
            <p class="text-xs text-amber-700">Sedang Dipinjam</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-2 text-center">
            <p class="text-xl font-bold text-blue-600">{{ $stats['total_pinjam'] }}</p>
            <p class="text-xs text-blue-700">Total Pinjam</p>
        </div>
    </div>
</div>

<!-- Catalog Grid -->
<div id="katalog-grid" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
    @foreach($barangs as $barang)
    <div class="card p-3 sm:p-4 hover:shadow-md transition-all duration-200 group flex flex-col">
        <!-- Icon -->
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center mb-3 {{ $barang->stok > 0 && $barang->kondisi === 'baik' ? 'bg-blue-100' : 'bg-slate-100' }}">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 {{ $barang->stok > 0 && $barang->kondisi === 'baik' ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>

        <!-- Info -->
        <div class="flex-1">
            <p class="text-[9px] sm:text-xs font-mono text-slate-400 mb-0.5">{{ $barang->kode_barang }}</p>
            <h3 class="font-semibold text-slate-800 text-xs sm:text-sm leading-tight mb-2 min-h-[2rem]">{{ $barang->nama_barang }}</h3>
        </div>

        <!-- Footer -->
        <div class="mt-auto">
            <div class="flex items-center justify-between mb-3">
                <!-- Stok Badge -->
                <div class="flex items-center gap-1">
                    <div class="w-1.5 h-1.5 rounded-full {{ $barang->stok > 2 ? 'bg-emerald-500' : ($barang->stok > 0 ? 'bg-amber-500' : 'bg-red-500') }}"></div>
                    <span class="text-[10px] font-medium {{ $barang->stok > 2 ? 'text-emerald-600' : ($barang->stok > 0 ? 'text-amber-600' : 'text-red-600') }}">
                        S: {{ $barang->stok }}
                    </span>
                </div>
                <span class="bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase">{{ $barang->kondisi }}</span>
            </div>

            <!-- Action -->
            @if($barang->stok > 0 && $barang->kondisi === 'baik')
            <a href="{{ route('siswa.peminjaman.create', ['barang_id' => $barang->id]) }}"
                class="btn-primary w-full justify-center text-[10px] sm:text-xs py-2 px-1">
                Pinjam
            </a>
            @else
            <button disabled class="w-full bg-slate-100 text-slate-400 rounded-lg py-2 text-[10px] sm:text-xs font-semibold cursor-not-allowed">
                N/A
            </button>
            @endif
        </div>
    </div>
    @endforeach
</div>

@if($barangs->isEmpty())
<div class="text-center py-16 text-slate-400">
    <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
    <p>Belum ada barang di katalog.</p>
</div>
@endif

@push('scripts')
<script>
    // ─── Real-time Polling & Pull-to-Refresh ────────────────────────────
    const POLL_INTERVAL = 5000;

    function refreshDashboard() {
        return fetch('{{ route("siswa.api.data") }}', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            // Update stats
            const statsCards = document.querySelectorAll('.bg-amber-50 .text-xl, .bg-blue-50 .text-xl');
            if (statsCards[0]) statsCards[0].textContent = data.stats.sedang_dipinjam;
            if (statsCards[1]) statsCards[1].textContent = data.stats.total_pinjam;

            // Update katalog barang
            const grid = document.getElementById('katalog-grid');
            if (grid && data.barangs) {
                grid.innerHTML = data.barangs.map(b => {
                    const tersedia = b.stok > 0 && b.kondisi === 'baik';
                    const stokColor = b.stok > 2 ? 'emerald' : (b.stok > 0 ? 'amber' : 'red');
                    return `
                    <div class="card p-3 sm:p-4 hover:shadow-md transition-all duration-200 group flex flex-col">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center mb-3 ${tersedia ? 'bg-blue-100' : 'bg-slate-100'}">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 ${tersedia ? 'text-blue-600' : 'text-slate-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-[9px] sm:text-xs font-mono text-slate-400 mb-0.5">${b.kode_barang}</p>
                            <h3 class="font-semibold text-slate-800 text-xs sm:text-sm leading-tight mb-2 min-h-[2rem]">${b.nama_barang}</h3>
                        </div>
                        <div class="mt-auto">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-1">
                                    <div class="w-1.5 h-1.5 rounded-full bg-${stokColor}-500"></div>
                                    <span class="text-[10px] font-medium text-${stokColor}-600">S: ${b.stok}</span>
                                </div>
                                <span class="bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase">${b.kondisi}</span>
                            </div>
                            ${tersedia
                                ? `<a href="/siswa/peminjaman/buat?barang_id=${b.id}" class="btn-primary w-full justify-center text-[10px] sm:text-xs py-2 px-1">Pinjam</a>`
                                : `<button disabled class="w-full bg-slate-100 text-slate-400 rounded-lg py-2 text-[10px] sm:text-xs font-semibold cursor-not-allowed">N/A</button>`
                            }
                        </div>
                    </div>`;
                }).join('');
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
