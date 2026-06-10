<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegister()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('siswa.dashboard');
        }

        $jurusans = ['TKJ', 'RPL', 'TKR', 'MM', 'DG', 'TEI'];

        return view('auth.register', compact('jurusans'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|max:100',
            'nis'                   => 'required|string|max:20|exists:perpus_master_siswas,nis|unique:users,nis',
            'username'              => 'required|string|max:50|unique:users,username|alpha_dash',
            'email'                 => 'required|email|max:255|unique:users,email',
            'jurusan'               => 'required|string|max:50',
            'kelas'                 => 'required|string|max:20',
            'password'              => 'required|string|min:6|confirmed',
        ], [
            'name.required'     => 'Nama Lengkap wajib diisi.',
            'nis.required'      => 'NIS wajib diisi.',
            'nis.exists'        => 'NIS Anda belum terdaftar dalam Daftar NIS Terverifikasi sekolah. Silakan hubungi pihak sekolah untuk mendaftarkan NIS Anda.',
            'nis.unique'        => 'NIS ini sudah digunakan untuk mendaftar. Silakan login.',
            'username.required' => 'Username wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'jurusan.required'  => 'Jurusan wajib diisi.',
            'kelas.required'    => 'Kelas wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'email.unique'      => 'Email ini sudah digunakan.',
            'username.unique'   => 'Username ini sudah digunakan.',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->filled('nis') && $request->filled('jurusan')) {
                $master = \App\Models\Perpustakaan\PerpusMasterSiswa::where('nis', $request->nis)->first();
                if ($master) {
                    $j1 = strtoupper(trim($master->jurusan));
                    $j2 = strtoupper(trim($request->jurusan));
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
                        $validator->errors()->add('jurusan', 'Jurusan yang Anda pilih (' . $request->jurusan . ') tidak sesuai dengan jurusan resmi NIS Anda di database sekolah (' . $master->jurusan . ').');
                    }
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Pendaftaran gagal! Data ada yang belum terisi.');
        }

        $validated = $validator->validated();

        $user = User::create([
            'name'     => $validated['name'],
            'nis'      => $validated['nis'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'jurusan'  => $validated['jurusan'],
            'kelas'    => $validated['kelas'],
            'password' => Hash::make($validated['password']),
            'role'     => 'siswa',
        ]);

        return redirect()->route('login')
            ->with('success', 'Pendaftaran berhasil! Silakan login untuk masuk ke dashboard.');
    }
}
