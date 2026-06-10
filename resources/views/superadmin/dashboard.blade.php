@extends('superadmin.layouts.app')

@section('content')
<!-- Welcome banner with gradient -->
<div class="bg-gradient-to-r from-violet-600 to-indigo-700 rounded-3xl p-8 mb-8 text-white shadow-lg shadow-indigo-600/10 relative overflow-hidden">
    <div class="absolute right-0 top-0 translate-x-12 -translate-y-12 w-64 h-64 bg-white/5 rounded-full blur-2xl"></div>
    <div class="relative z-10 max-w-2xl">
        <h2 class="text-3xl font-extrabold tracking-tight">Selamat Datang, Superadmin!</h2>
        <p class="text-indigo-100/90 mt-2 text-sm leading-relaxed">
            Selamat datang di Control Center SMK Ma'arif Talang. Di sini Anda memiliki kontrol penuh atas verifikasi NIS, registrasi akun siswa, dan manajemen kredensial admin sub-sistem.
        </p>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('error'))
<div class="flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl text-sm mb-8 shadow-sm">
    <svg class="w-5 h-5 flex-shrink-0 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9V7a1 1 0 112 0v2a1 1 0 11-2 0zm0 4a1 1 0 102 0 1 1 0 00-2 0z" clip-rule="evenodd"/></svg>
    <span class="font-semibold">{{ session('error') }}</span>
</div>
@endif

<!-- Dynamic Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Akun Siswa -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
        <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Akun Siswa</p>
            <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $totalSiswa }}</h3>
        </div>
    </div>

    <!-- Master NIS Terverifikasi -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
        <div class="w-14 h-14 bg-violet-50 text-violet-600 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">NIS Terverifikasi</p>
            <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $totalSiswaMaster }}</h3>
        </div>
    </div>

    <!-- Total Sirkulasi Bengkel -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12a3 3 0 106 0 3 3 0 00-6 0z"/></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sirkulasi Bengkel</p>
            <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $totalPeminjamanBengkel }}</h3>
        </div>
    </div>

    <!-- Total Sirkulasi Perpus -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
        <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sirkulasi Perpus</p>
            <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $totalPeminjamanPerpus }}</h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Left Column: Quick Check NIS & System Status -->
    <div class="space-y-8">
        <!-- Quick Check NIS Widget -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <h3 class="text-base font-bold text-slate-800 mb-2">Cek NIS Cepat</h3>
            <p class="text-xs text-slate-400 mb-4 leading-normal">Ketik nomor NIS untuk memeriksa status registrasi siswa secara instan.</p>
            
            <div class="flex gap-2">
                <input type="text" id="quick-nis-input" placeholder="Contoh: 12345"
                    class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                <button type="button" onclick="quickCheckNis()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 rounded-xl text-xs transition-colors flex items-center justify-center">
                    Cek
                </button>
            </div>
            
            <!-- Result Area -->
            <div id="check-result-container" class="mt-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 hidden transition-all">
                <div class="flex items-start gap-3">
                    <div id="result-status-icon" class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"></div>
                    <div class="flex-1 space-y-1">
                        <h4 id="result-name" class="text-sm font-bold text-slate-800"></h4>
                        <p id="result-meta" class="text-xs text-slate-500 font-medium"></p>
                        <p id="result-detail" class="text-xs text-slate-400 leading-normal font-medium mt-1 pt-1.5 border-t border-slate-200/50"></p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Center & Right: Chart.js Graphs -->
    <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
        <div>
            <h3 class="text-base font-bold text-slate-800 mb-2">Analisis Distribusi Akun Siswa</h3>
            <p class="text-xs text-slate-400 mb-6">Visualisasi perbandingan jumlah pendaftaran akun siswa aktif per jurusan.</p>
        </div>
        
        <div class="h-64 flex items-center justify-center relative">
            <canvas id="jurusanChart" class="max-h-full"></canvas>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-6 gap-2 mt-6 pt-6 border-t border-slate-100 text-center">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">TKJ</p>
                <h4 class="text-base font-black text-indigo-600 mt-0.5">{{ $jurusanDistribution['TKJ'] }}</h4>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">TKR</p>
                <h4 class="text-base font-black text-rose-500 mt-0.5">{{ $jurusanDistribution['TKR'] }}</h4>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">RPL</p>
                <h4 class="text-base font-black text-emerald-600 mt-0.5">{{ $jurusanDistribution['RPL'] }}</h4>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">MM</p>
                <h4 class="text-base font-black text-purple-600 mt-0.5">{{ $jurusanDistribution['MM'] }}</h4>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">DG</p>
                <h4 class="text-base font-black text-cyan-600 mt-0.5">{{ $jurusanDistribution['DG'] }}</h4>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">TEI</p>
                <h4 class="text-base font-black text-amber-500 mt-0.5">{{ $jurusanDistribution['TEI'] }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Quick Actions Card -->
    <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <h3 class="text-base font-bold text-slate-800 mb-6">Menu Fitur Utama</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Action 1: Master NIS -->
            <a href="{{ route('superadmin.siswa.index') }}" class="group border border-slate-100 hover:border-violet-200 bg-slate-50/50 hover:bg-violet-50/20 p-5 rounded-2xl transition-all duration-300 shadow-sm flex flex-col justify-between">
                <div class="w-10 h-10 bg-violet-100 text-violet-700 rounded-xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800 mb-1 group-hover:text-violet-700 transition-colors">NIS Terverifikasi</h4>
                    <p class="text-[11px] text-slate-400 font-medium leading-normal">Pre-approved NIS master list & Excel CSV upload.</p>
                </div>
            </a>

            <!-- Action 2: Accounts -->
            <a href="{{ route('superadmin.users.index') }}" class="group border border-slate-100 hover:border-indigo-200 bg-slate-50/50 hover:bg-indigo-50/20 p-5 rounded-2xl transition-all duration-300 shadow-sm flex flex-col justify-between">
                <div class="w-10 h-10 bg-indigo-100 text-indigo-700 rounded-xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800 mb-1 group-hover:text-indigo-700 transition-colors">Akun Siswa</h4>
                    <p class="text-[11px] text-slate-400 font-medium leading-normal">Kelola akun, edit biodata, & reset sandi siswa.</p>
                </div>
            </a>

            <!-- Action 3: Admins -->
            <a href="{{ route('superadmin.admins.index') }}" class="group border border-slate-100 hover:border-pink-200 bg-slate-50/50 hover:bg-pink-50/20 p-5 rounded-2xl transition-all duration-300 shadow-sm flex flex-col justify-between">
                <div class="w-10 h-10 bg-pink-100 text-pink-700 rounded-xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800 mb-1 group-hover:text-pink-700 transition-colors">Kelola Admin</h4>
                    <p class="text-[11px] text-slate-400 font-medium leading-normal">Kelola info sandi Admin Bengkel & Perpustakaan.</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Live Activity Logs -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <h3 class="text-base font-bold text-slate-800 mb-4">Aktivitas Sistem Terbaru</h3>
        <div class="space-y-4">
            @forelse($recentActivities as $activity)
                <div class="flex gap-3 text-xs leading-normal">
                    <div class="w-2.5 h-2.5 rounded-full {{ $activity['color'] }} mt-1 flex-shrink-0"></div>
                    <div>
                        <p class="text-slate-700 font-medium">{{ $activity['title'] }}</p>
                        <span class="text-slate-400 text-[10px] font-bold" title="{{ $activity['time']->format('d M Y H:i') }}">
                            {{ $activity['time']->diffForHumans() }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-xs text-slate-400 text-center py-4">Belum ada aktivitas tercatat.</div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Add Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Check NIS AJax function
function quickCheckNis() {
    const nis = document.getElementById('quick-nis-input').value.trim();
    const resultContainer = document.getElementById('check-result-container');
    const nameEl = document.getElementById('result-name');
    const metaEl = document.getElementById('result-meta');
    const detailEl = document.getElementById('result-detail');
    const iconEl = document.getElementById('result-status-icon');
    
    if (!nis) {
        Swal.fire({
            title: 'Input Kosong!',
            text: 'Masukkan nomor NIS terlebih dahulu.',
            icon: 'warning',
            confirmButtonColor: '#4f46e5'
        });
        return;
    }
    
    resultContainer.classList.add('hidden');
    
    fetch(`{{ url('/superadmin/api/check-nis') }}/${nis}`)
        .then(response => response.json())
        .then(data => {
            resultContainer.classList.remove('hidden');
            
            if (data.status === 'not_found') {
                iconEl.className = 'w-8 h-8 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0';
                iconEl.innerHTML = '<svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>';
                nameEl.textContent = 'Data Tidak Ditemukan';
                metaEl.textContent = `NIS: ${nis}`;
                detailEl.innerHTML = `<span class="text-rose-600 font-semibold">${data.message}</span>`;
            } else if (data.status === 'registered') {
                iconEl.className = 'w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0';
                iconEl.innerHTML = '<svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
                nameEl.textContent = data.name;
                metaEl.textContent = `NIS: ${nis} | Kelas: ${data.kelas} | Jurusan: ${data.jurusan}`;
                detailEl.innerHTML = `<span class="text-emerald-600 font-bold">● ${data.message}</span><br>Username: <code class="font-mono bg-white border border-slate-100 rounded px-1.5 text-slate-700">${data.username}</code>`;
            } else {
                iconEl.className = 'w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0';
                iconEl.innerHTML = '<svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
                nameEl.textContent = data.name;
                metaEl.textContent = `NIS: ${nis} | Kelas: ${data.kelas} | Jurusan: ${data.jurusan}`;
                detailEl.innerHTML = `<span class="text-amber-600 font-bold">● ${data.message}</span><br><a href="{{ route('superadmin.users.create') }}?nis=${nis}" class="text-indigo-600 hover:underline font-bold mt-1 inline-block">Daftarkan Akun Sekarang &rarr;</a>`;
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                title: 'Error!',
                text: 'Gagal menghubungi server.',
                icon: 'error'
            });
        });
}

// Chart.js render
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('jurusanChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['TKJ', 'TKR', 'RPL', 'MM', 'DG', 'TEI'],
            datasets: [{
                data: [
                    {{ $jurusanDistribution['TKJ'] }},
                    {{ $jurusanDistribution['TKR'] }},
                    {{ $jurusanDistribution['RPL'] }},
                    {{ $jurusanDistribution['MM'] }},
                    {{ $jurusanDistribution['DG'] }},
                    {{ $jurusanDistribution['TEI'] }}
                ],
                backgroundColor: [
                    '#4f46e5', // indigo (TKJ)
                    '#f43f5e', // rose (TKR)
                    '#10b981', // emerald (RPL)
                    '#9333ea', // purple (MM)
                    '#0891b2', // cyan (DG)
                    '#d97706'  // amber/tei (TEI)
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            family: 'Plus Jakarta Sans',
                            size: 11,
                            weight: '600'
                        },
                        padding: 20
                    }
                }
            },
            cutout: '65%'
        }
    });
});
</script>
@endpush
