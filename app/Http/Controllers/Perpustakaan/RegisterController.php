<?php

namespace App\Http\Controllers\Perpustakaan;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan\PerpusUser;
use App\Models\Perpustakaan\PerpusJurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegister()
    {
        if (Auth::guard('perpus')->check()) {
            $user = Auth::guard('perpus')->user();
            return $user->role === 'admin'
                ? redirect()->route('perpustakaan.admin.dashboard')
                : redirect()->route('perpustakaan.siswa.dashboard');
        }

        $jurusans = PerpusJurusan::orderBy('nama_jurusan')->get();

        return view('perpustakaan.auth.register', compact('jurusans'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:100',
            'nis'      => 'required|string|max:20|exists:perpus_master_siswas,nis|unique:perpus_users,nis',
            'username' => 'required|string|max:50|unique:perpus_users,username|alpha_dash',
            'email'    => 'required|email|max:255|unique:perpus_users,email',
            'jurusan'  => 'required|string|max:50',
            'kelas'    => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required'     => 'Nama Lengkap wajib diisi.',
            'nis.required'      => 'NIS wajib diisi.',
            'nis.exists'        => 'NIS Anda belum terdaftar dalam Daftar NIS Terverifikasi sekolah. Silakan hubungi pihak sekolah.',
            'nis.unique'        => 'NIS ini sudah digunakan untuk mendaftar. Silakan login atau hubungi Admin Perpustakaan.',
            'username.required' => 'Username wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'jurusan.required'  => 'Jurusan wajib diisi.',
            'kelas.required'    => 'Kelas wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'email.unique'      => 'Email ini sudah digunakan.',
            'username.unique'   => 'Username ini sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Pendaftaran gagal! Data ada yang belum terisi.');
        }

        $validated = $validator->validated();

        $user = PerpusUser::create([
            'name'     => $validated['name'],
            'nis'      => $validated['nis'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'jurusan'  => $validated['jurusan'],
            'kelas'    => $validated['kelas'],
            'password' => Hash::make($validated['password']),
            'role'     => 'siswa',
        ]);

        return redirect()->route('perpustakaan.login')
            ->with('success', 'Pendaftaran berhasil! Silakan login untuk masuk ke dashboard perpustakaan.');
    }
}
