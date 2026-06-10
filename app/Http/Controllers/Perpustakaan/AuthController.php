<?php

namespace App\Http\Controllers\Perpustakaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('perpustakaan.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('perpus')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $request->session()->flash('login_success', true);

            $user = Auth::guard('perpus')->user();

            if ($user->role === 'admin') {
                return redirect()->route('perpustakaan.admin.dashboard')
                    ->with('success', 'Selamat datang, ' . $user->name . '!');
            }

            return redirect()->route('perpustakaan.siswa.dashboard')
                ->with('success', 'Selamat datang, ' . $user->name . '!');
        }

        return back()
            ->withInput($request->only('username'))
            ->withErrors(['username' => 'Username atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('perpus')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('perpustakaan.login')
            ->with('success', 'Anda telah berhasil keluar.');
    }
}
