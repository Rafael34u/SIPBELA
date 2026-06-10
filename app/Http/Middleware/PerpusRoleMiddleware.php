<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PerpusRoleMiddleware
{
    /**
     * Handle an incoming request.
     * Middleware ini sekarang menggunakan guard 'web' (SSO).
     * Semua user (siswa, admin_perpus, superadmin) login melalui halaman login utama.
     * 
     * Pemetaan role perpustakaan:
     *  - 'siswa'      => role 'siswa' di tabel users
     *  - 'admin'      => role 'admin_perpus' atau 'superadmin' di tabel users
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pastikan sudah login via guard web (SSO)
        if (! auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();
        $userRole = $user->role;

        if ($role === 'admin') {
            // Admin perpustakaan atau superadmin boleh masuk
            if (!in_array($userRole, ['admin_perpus', 'superadmin'])) {
                return redirect()->route('portal')
                    ->with('error', 'Akses ditolak. Halaman ini hanya untuk Admin Perpustakaan.');
            }
        } elseif ($role === 'siswa') {
            // Hanya siswa yang boleh masuk area siswa
            if ($userRole !== 'siswa') {
                if ($userRole === 'admin_perpus') {
                    return redirect()->route('perpustakaan.admin.dashboard')
                        ->with('error', 'Akses ditolak.');
                }
                if ($userRole === 'superadmin') {
                    return redirect()->route('superadmin.dashboard')
                        ->with('error', 'Akses ditolak.');
                }
                return redirect()->route('login')
                    ->with('error', 'Akses ditolak.');
            }
        }

        return $next($request);
    }
}
