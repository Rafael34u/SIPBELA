<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Enforce TKR only for Bengkel Admin (handling both TKR and Teknik Kendaraan Ringan)
        $query = User::where('role', 'siswa')->whereIn('jurusan', ['TKR', 'Teknik Kendaraan Ringan']);

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

        $users = $query->withCount(['peminjamans as aktif_count' => function ($q) {
            $q->whereIn('status', ['dipinjam', 'menunggu_konfirmasi']);
        }])->latest()->paginate(10, ['*'], 'users_page')->withQueryString();

        // Get whitelist entries for TKR only
        $whitelistQuery = \App\Models\Perpustakaan\PerpusMasterSiswa::whereIn('jurusan', ['TKR', 'Teknik Kendaraan Ringan']);
        if ($request->filled('search')) {
            $search = $request->search;
            $whitelistQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }
        $whitelist = $whitelistQuery->orderBy('nama')->paginate(15, ['*'], 'whitelist_page')->withQueryString();

        return view('admin.users.index', compact('users', 'whitelist'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'nis'      => 'required|string|max:20|exists:perpus_master_siswas,nis|unique:users,nis',
            'username' => 'required|string|max:50|unique:users,username|alpha_dash',
            'email'    => 'nullable|email|max:255|unique:users,email',
            'kelas'    => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $master = \App\Models\Perpustakaan\PerpusMasterSiswa::where('nis', $validated['nis'])->first();
        User::create([
            'name'     => $validated['name'],
            'nis'      => $validated['nis'],
            'username' => $validated['username'],
            'email'    => $validated['email'] ?? null,
            'jurusan'  => $master ? $master->jurusan : 'Teknik Kendaraan Ringan',
            'kelas'    => $validated['kelas'],
            'password' => Hash::make($validated['password']),
            'role'     => 'siswa',
        ]);

        return redirect()->route('admin.users.index')->with('success', "Akun siswa TKR \"{$validated['name']}\" berhasil dibuat.");
    }

    public function edit(User $user)
    {
        // Pastikan siswa TKR dan bukan admin
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak dapat mengedit akun admin.');
        }
        if (!in_array(strtoupper(trim($user->jurusan)), ['TKR', 'TEKNIK KENDARAAN RINGAN'])) {
            return back()->with('error', 'Hanya diizinkan mengedit siswa TKR.');
        }
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Pastikan siswa TKR dan bukan admin
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak dapat mengedit akun admin.');
        }
        if (!in_array(strtoupper(trim($user->jurusan)), ['TKR', 'TEKNIK KENDARAAN RINGAN'])) {
            return back()->with('error', 'Hanya diizinkan mengedit siswa TKR.');
        }

        $rules = [
            'name'     => 'required|string|max:100',
            'nis'      => 'required|string|max:20|exists:perpus_master_siswas,nis|unique:users,nis,' . $user->id,
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,' . $user->id,
            'email'    => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'kelas'    => 'required|string|max:20',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        $validated = $request->validate($rules);

        $master = \App\Models\Perpustakaan\PerpusMasterSiswa::where('nis', $validated['nis'])->first();

        $user->name     = $validated['name'];
        $user->nis      = $validated['nis'];
        $user->username = $validated['username'];
        $user->email    = $validated['email'] ?? null;
        $user->jurusan  = $master ? $master->jurusan : 'Teknik Kendaraan Ringan';
        $user->kelas    = $validated['kelas'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', "Akun siswa TKR \"{$user->name}\" berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        // Pastikan siswa TKR dan bukan admin
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak dapat menghapus akun admin.');
        }
        if (!in_array(strtoupper(trim($user->jurusan)), ['TKR', 'TEKNIK KENDARAAN RINGAN'])) {
            return back()->with('error', 'Hanya diizinkan menghapus siswa TKR.');
        }

        // Cek peminjaman aktif
        if ($user->peminjamans()->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])->exists()) {
            return back()->with('error', "Siswa \"{$user->name}\" masih memiliki barang yang dipinjam.");
        }

        $nama = $user->name;
        $user->delete();

        return back()->with('success', "Akun siswa \"{$nama}\" berhasil dihapus.");
    }

    public function resetPassword(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak dapat mereset sandi admin.');
        }
        if (!in_array(strtoupper(trim($user->jurusan)), ['TKR', 'TEKNIK KENDARAAN RINGAN'])) {
            return back()->with('error', 'Hanya diizinkan mereset sandi siswa TKR.');
        }

        if (!$user->nis) {
            return back()->with('error', "Gagal reset: NIS tidak tersedia pada akun {$user->name}.");
        }

        $user->update([
            'password' => Hash::make($user->nis)
        ]);

        return back()->with('success', "Password untuk {$user->name} telah dikembalikan ke default (NIS).");
    }

    public function storeWhitelist(Request $request)
    {
        $data = $request->validate([
            'nis' => 'required|string|max:20|unique:perpus_master_siswas,nis',
            'nama' => 'required|string|max:100',
            'kelas' => 'nullable|string|max:20',
        ]);

        $data['jurusan'] = 'TKR'; // Force TKR for Bengkel admin

        \App\Models\Perpustakaan\PerpusMasterSiswa::create($data);

        return back()->with('success', "Siswa \"{$data['nama']}\" dengan NIS {$data['nis']} berhasil ditambahkan ke Daftar NIS Terverifikasi TKR.");
    }

    public function importWhitelist(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xls,xlsx|max:2048',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\BengkelUsersImport, $request->file('file'));
            return back()->with('success', "Data NIS siswa TKR berhasil diimpor ke Daftar NIS Terverifikasi. Siswa TKR kini dapat mendaftar mandiri.");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal mengimpor data: " . $e->getMessage());
        }
    }

    public function destroyWhitelist($id)
    {
        $siswa = \App\Models\Perpustakaan\PerpusMasterSiswa::whereIn('jurusan', ['TKR', 'Teknik Kendaraan Ringan'])->findOrFail($id);
        $nama = $siswa->nama;
        $siswa->delete();

        return back()->with('success', "Siswa \"{$nama}\" berhasil dihapus dari Daftar NIS Terverifikasi TKR.");
    }
}
