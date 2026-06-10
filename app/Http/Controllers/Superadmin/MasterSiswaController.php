<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan\PerpusMasterSiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class MasterSiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = PerpusMasterSiswa::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('nis', 'like', "%{$s}%")
                  ->orWhere('jurusan', 'like', "%{$s}%")
                  ->orWhere('kelas', 'like', "%{$s}%");
            });
        }

        $siswa = $query->orderBy('nama')->paginate(20)->withQueryString();

        return view('superadmin.siswa.index', compact('siswa'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nis' => 'required|string|max:20|unique:perpus_master_siswas,nis',
            'nama' => 'required|string|max:100',
            'jurusan' => 'nullable|string|max:50',
            'kelas' => 'nullable|string|max:20',
        ]);

        PerpusMasterSiswa::create($data);

        // Auto-create user account with default credentials
        User::create([
            'name'     => $data['nama'],
            'nis'      => $data['nis'],
            'username' => $data['nis'], // default username is NIS
            'email'    => $data['nis'] . '@siswa.sch.id', // default email
            'jurusan'  => $data['jurusan'] ?? '-',
            'kelas'    => $data['kelas'] ?? '-',
            'password' => Hash::make($data['nis']), // default password is NIS
            'role'     => 'siswa',
        ]);

        return back()->with('success', "Siswa \"{$data['nama']}\" dengan NIS {$data['nis']} berhasil ditambahkan ke Daftar NIS Terverifikasi dan akun siswa otomatis dibuat.");
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xls,xlsx|max:2048',
        ]);

        try {
            Excel::import(new \App\Imports\UsersImport, $request->file('file'));
            return back()->with('success', "Data NIS siswa berhasil diimpor. Siswa dengan NIS tersebut kini dapat mendaftar mandiri ke Portal Sekolah.");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal mengimpor data: " . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $siswa = PerpusMasterSiswa::findOrFail($id);
        return view('superadmin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        $siswa = PerpusMasterSiswa::findOrFail($id);
        
        $data = $request->validate([
            'nis' => 'required|string|max:20|unique:perpus_master_siswas,nis,' . $siswa->id,
            'nama' => 'required|string|max:100',
            'jurusan' => 'nullable|string|max:50',
            'kelas' => 'nullable|string|max:20',
        ]);

        $oldNis = $siswa->nis;
        $siswa->update($data);

        // Update corresponding user account if it exists
        $user = User::where('nis', $oldNis)->where('role', 'siswa')->first();
        if ($user) {
            $user->update([
                'name' => $data['nama'],
                'nis' => $data['nis'],
                'jurusan' => $data['jurusan'] ?? '-',
                'kelas' => $data['kelas'] ?? '-',
            ]);
        }

        return redirect()->route('superadmin.siswa.index')->with('success', "Data siswa \"{$data['nama']}\" berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $siswa = PerpusMasterSiswa::findOrFail($id);
        $user = User::where('nis', $siswa->nis)->first();
        
        if ($user) {
            // Check active borrowings in Bengkel
            if ($user->peminjamans()->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])->exists()) {
                return back()->with('error', "Gagal menghapus: Siswa \"{$siswa->nama}\" masih memiliki pinjaman aktif di Bengkel.");
            }

            // Check active borrowings in Perpustakaan
            $perpusUser = \App\Models\Perpustakaan\PerpusUser::where('nis', $user->nis)->first();
            if ($perpusUser && \App\Models\Perpustakaan\PerpusPeminjaman::where('user_id', $perpusUser->id)->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])->exists()) {
                return back()->with('error', "Gagal menghapus: Siswa \"{$siswa->nama}\" masih memiliki pinjaman aktif di Perpustakaan.");
            }
            
            $user->delete();
        }

        $nama = $siswa->nama;
        $siswa->delete();

        return back()->with('success', "Siswa \"{$nama}\" berhasil dihapus dari Daftar NIS Terverifikasi.");
    }
}
