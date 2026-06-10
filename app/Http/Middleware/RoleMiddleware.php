<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: Route::middleware('role:superadmin,admin_bengkel')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        // Cek jika role user ada dalam daftar roles yang diizinkan
        if (!in_array($userRole, $roles)) {
            // Redirect sesuai role
            if ($userRole === 'superadmin') {
                return redirect()->route('superadmin.dashboard')->with('error', 'Akses ditolak.');
            } elseif ($userRole === 'admin_bengkel') {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
            } elseif ($userRole === 'admin_perpus') {
                return redirect()->route('perpustakaan.admin.dashboard')->with('error', 'Akses ditolak.');
            } else {
                return redirect()->route('portal')->with('error', 'Akses ditolak.');
            }
        }

        return $next($request);
    }
}
