<?php

namespace App\Http\Controllers\Perpustakaan\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'siswa');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('username', 'like', "%{$s}%")
                  ->orWhere('no_anggota', 'like', "%{$s}%")
                  ->orWhere('kelas', 'like', "%{$s}%")
                  ->orWhere('jurusan', 'like', "%{$s}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('perpustakaan.admin.users.index', compact('users'));
    }

    public function create()
    {
        $jurusans = \App\Models\Perpustakaan\PerpusJurusan::orderBy('nama_jurusan')->get();
        return view('perpustakaan.admin.users.create', compact('jurusans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'nis'        => 'nullable|string|max:20',
            'username'   => 'required|string|max:50|unique:perpus_users,username',
            'email'      => 'nullable|email|max:255',
            'no_anggota' => 'nullable|string|max:20|unique:perpus_users,no_anggota',
            'kelas'      => 'nullable|string|max:20',
            'jurusan'    => 'nullable|string|max:100',
            'password'   => ['required', Password::min(6)],
        ]);

        $data['role']     = 'siswa';
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('perpustakaan.admin.users.index')
            ->with('success', "Anggota \"{$data['name']}\" berhasil ditambahkan.");
    }

    public function edit(User $user)
    {
        $jurusans = \App\Models\Perpustakaan\PerpusJurusan::orderBy('nama_jurusan')->get();
        return view('perpustakaan.admin.users.edit', compact('user', 'jurusans'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'nis'        => 'nullable|string|max:20',
            'username'   => 'required|string|max:50|unique:perpus_users,username,' . $user->id,
            'email'      => 'nullable|email|max:255',
            'no_anggota' => 'nullable|string|max:20|unique:perpus_users,no_anggota,' . $user->id,
            'kelas'      => 'nullable|string|max:20',
            'jurusan'    => 'nullable|string|max:50',
            'password'   => ['nullable', Password::min(6)],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()->route('perpustakaan.admin.users.index')
            ->with('success', "Data anggota \"{$user->name}\" berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        $name = $user->name;
        $user->delete();

        return back()->with('success', "Anggota \"{$name}\" berhasil dihapus.");
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xls,xlsx|max:2048',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\UsersImport, $request->file('file'));
            return back()->with('success', "Data NIS siswa berhasil diimpor ke Daftar NIS Terverifikasi. Siswa dengan NIS tersebut kini dapat mendaftar mandiri.");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal mengimpor data: " . $e->getMessage());
        }
    }

    public function resetPassword(User $user)
    {
        if (!$user->nis) {
            return back()->with('error', "Gagal reset: NIS tidak tersedia pada akun {$user->name}.");
        }

        $user->update([
            'password' => Hash::make($user->nis)
        ]);

        return back()->with('success', "Password untuk {$user->name} telah dikembalikan ke default (NIS).");
    }
}
