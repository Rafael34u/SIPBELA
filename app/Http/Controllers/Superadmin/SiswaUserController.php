<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Perpustakaan\PerpusMasterSiswa;
use App\Models\Perpustakaan\PerpusPeminjaman;
use App\Models\Perpustakaan\PerpusUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SiswaUserController extends Controller
{
    public function index(Request $request)
    {
        // Auto-sync missing student accounts from master siswa list on load
        User::syncFromMaster();

        $query = User::where('role', 'siswa');

        if ($request->filled('jurusan')) {
            $jur = $request->jurusan;
            if (in_array($jur, ['TKR', 'Teknik Kendaraan Ringan'])) {
                $query->whereIn('jurusan', ['TKR', 'Teknik Kendaraan Ringan']);
            } elseif (in_array($jur, ['TKJ', 'Teknik Komputer Jaringan'])) {
                $query->whereIn('jurusan', ['TKJ', 'Teknik Komputer Jaringan']);
            } elseif (in_array($jur, ['RPL', 'Rekayasa Perangkat Lunak'])) {
                $query->whereIn('jurusan', ['RPL', 'Rekayasa Perangkat Lunak']);
            } else {
                $query->where('jurusan', $jur);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        // Department count statistics for stats cards (handling both abbreviations and full names)
        $stats = [
            'total' => User::where('role', 'siswa')->count(),
            'tkr' => User::where('role', 'siswa')->whereIn('jurusan', ['TKR', 'Teknik Kendaraan Ringan'])->count(),
            'tkj' => User::where('role', 'siswa')->whereIn('jurusan', ['TKJ', 'Teknik Komputer Jaringan'])->count(),
            'rpl' => User::where('role', 'siswa')->whereIn('jurusan', ['RPL', 'Rekayasa Perangkat Lunak'])->count(),
            'mm' => User::where('role', 'siswa')->whereIn('jurusan', ['MM', 'Multimedia'])->count(),
            'dg' => User::where('role', 'siswa')->whereIn('jurusan', ['DG', 'Desain Grafis'])->count(),
            'tei' => User::where('role', 'siswa')->whereIn('jurusan', ['TEI', 'Teknik Audio Video'])->count(),
        ];

        $jurusans = ['TKJ', 'RPL', 'TKR', 'MM', 'DG', 'TEI'];

        return view('superadmin.users.index', compact('users', 'stats', 'jurusans'));
    }

    public function create()
    {
        $jurusans = ['TKJ', 'RPL', 'TKR', 'MM', 'DG', 'TEI'];
        return view('superadmin.users.create', compact('jurusans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'nis'      => 'required|string|max:20|exists:perpus_master_siswas,nis|unique:users,nis',
            'username' => 'required|string|max:50|unique:users,username|alpha_dash',
            'email'    => 'nullable|email|max:255|unique:users,email',
            'jurusan'  => 'required|string|max:50',
            'kelas'    => 'required|string|max:20',
            'password' => ['required', 'string', Password::min(6), 'confirmed'],
        ], [
            'nis.exists' => 'NIS tidak terdaftar dalam Daftar NIS Terverifikasi sekolah. Silakan tambahkan NIS terlebih dahulu di menu Master Siswa.',
            'nis.unique' => 'NIS ini sudah memiliki akun yang terdaftar.',
            'username.unique' => 'Username ini sudah digunakan.',
            'email.unique' => 'Email ini sudah digunakan.',
        ]);

        $master = PerpusMasterSiswa::where('nis', $validated['nis'])->first();
        if ($master) {
            $j1 = strtoupper(trim($master->jurusan));
            $j2 = strtoupper(trim($validated['jurusan']));
            $isMatch = ($j1 === $j2);
            if (!$isMatch) {
                $equivs = [
                    'TKR' => ['TKR', 'TEKNIK KENDARAAN RINGAN'],
                    'TEKNIK KENDARAAN RINGAN' => ['TKR', 'TEKNIK KENDARAAN RINGAN'],
                    'TKJ' => ['TKJ', 'TEKNIK KOMPUTER JARINGAN'],
                    'TEKNIK KOMPUTER JARINGAN' => ['TKJ', 'TEKNIK KOMPUTER JARINGAN'],
                    'RPL' => ['RPL', 'REKAYASA PERANGKAT LUNAK'],
                    'REKAYASA PERANGKAT LUNAK' => ['RPL', 'REKAYASA PERANGKAT LUNAK'],
                    'TEI' => ['TEI', 'TEKNIK AUDIO VIDEO'],
                    'TEKNIK AUDIO VIDEO' => ['TEI', 'TEKNIK AUDIO VIDEO'],
                ];
                if (isset($equivs[$j1]) && in_array($j2, $equivs[$j1])) {
                    $isMatch = true;
                }
            }
            if (!$isMatch) {
                return back()->withErrors(['jurusan' => 'Jurusan yang dipilih (' . $validated['jurusan'] . ') tidak sesuai dengan data resmi NIS tersebut (' . $master->jurusan . ').'])->withInput();
            }
        }

        User::create([
            'name'     => $validated['name'],
            'nis'      => $validated['nis'],
            'username' => $validated['username'],
            'email'    => $validated['email'] ?? null,
            'jurusan'  => $validated['jurusan'],
            'kelas'    => $validated['kelas'],
            'password' => Hash::make($validated['password']),
            'role'     => 'siswa',
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', "Akun siswa \"{$validated['name']}\" berhasil didaftarkan.");
    }

    public function edit(User $user)
    {
        if ($user->role !== 'siswa') {
            return redirect()->route('superadmin.users.index')->with('error', 'Hanya dapat mengedit akun siswa.');
        }

        $jurusans = ['TKJ', 'RPL', 'TKR', 'MM', 'DG', 'TEI'];
        return view('superadmin.users.edit', compact('user', 'jurusans'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role !== 'siswa') {
            return redirect()->route('superadmin.users.index')->with('error', 'Hanya dapat mengedit akun siswa.');
        }

        $rules = [
            'name'     => 'required|string|max:100',
            'nis'      => 'required|string|max:20|exists:perpus_master_siswas,nis|unique:users,nis,' . $user->id,
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,' . $user->id,
            'email'    => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'jurusan'  => 'required|string|max:50',
            'kelas'    => 'required|string|max:20',
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', Password::min(6), 'confirmed'];
        }

        $validated = $request->validate($rules, [
            'nis.exists' => 'NIS tidak terdaftar dalam Daftar NIS Terverifikasi sekolah.',
            'nis.unique' => 'NIS ini sudah digunakan oleh akun lain.',
            'username.unique' => 'Username ini sudah digunakan.',
            'email.unique' => 'Email ini sudah digunakan.',
        ]);

        $master = PerpusMasterSiswa::where('nis', $validated['nis'])->first();
        if ($master) {
            $j1 = strtoupper(trim($master->jurusan));
            $j2 = strtoupper(trim($validated['jurusan']));
            $isMatch = ($j1 === $j2);
            if (!$isMatch) {
                $equivs = [
                    'TKR' => ['TKR', 'TEKNIK KENDARAAN RINGAN'],
                    'TEKNIK KENDARAAN RINGAN' => ['TKR', 'TEKNIK KENDARAAN RINGAN'],
                    'TKJ' => ['TKJ', 'TEKNIK KOMPUTER JARINGAN'],
                    'TEKNIK KOMPUTER JARINGAN' => ['TKJ', 'TEKNIK KOMPUTER JARINGAN'],
                    'RPL' => ['RPL', 'REKAYASA PERANGKAT LUNAK'],
                    'REKAYASA PERANGKAT LUNAK' => ['RPL', 'REKAYASA PERANGKAT LUNAK'],
                    'TEI' => ['TEI', 'TEKNIK AUDIO VIDEO'],
                    'TEKNIK AUDIO VIDEO' => ['TEI', 'TEKNIK AUDIO VIDEO'],
                ];
                if (isset($equivs[$j1]) && in_array($j2, $equivs[$j1])) {
                    $isMatch = true;
                }
            }
            if (!$isMatch) {
                return back()->withErrors(['jurusan' => 'Jurusan yang dipilih (' . $validated['jurusan'] . ') tidak sesuai dengan data resmi NIS tersebut (' . $master->jurusan . ').'])->withInput();
            }
        }

        $user->name     = $validated['name'];
        $user->nis      = $validated['nis'];
        $user->username = $validated['username'];
        $user->email    = $validated['email'] ?? null;
        $user->jurusan  = $validated['jurusan'];
        $user->kelas    = $validated['kelas'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('superadmin.users.index')
            ->with('success', "Akun siswa \"{$user->name}\" berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->role !== 'siswa') {
            return back()->with('error', 'Hanya dapat menghapus akun siswa.');
        }

        // Check active borrowings in Bengkel
        if ($user->peminjamans()->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])->exists()) {
            return back()->with('error', "Gagal menghapus: Siswa \"{$user->name}\" masih memiliki pinjaman alat aktif di Bengkel.");
        }

        // Check active borrowings in Perpustakaan
        $perpusUser = PerpusUser::where('nis', $user->nis)->first();
        if ($perpusUser && PerpusPeminjaman::where('user_id', $perpusUser->id)->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])->exists()) {
            return back()->with('error', "Gagal menghapus: Siswa \"{$user->name}\" masih memiliki pinjaman buku aktif di Perpustakaan.");
        }

        $nama = $user->name;
        $user->delete();

        return back()->with('success', "Akun siswa \"{$nama}\" berhasil dihapus.");
    }

    public function resetPassword(User $user)
    {
        if ($user->role !== 'siswa') {
            return back()->with('error', 'Hanya dapat mereset password akun siswa.');
        }

        if (!$user->nis) {
            return back()->with('error', "Gagal reset: NIS tidak tersedia pada akun {$user->name}.");
        }

        $user->update([
            'password' => Hash::make($user->nis)
        ]);

        return back()->with('success', "Password untuk siswa \"{$user->name}\" telah berhasil dikembalikan ke default (NIS).");
    }
}
