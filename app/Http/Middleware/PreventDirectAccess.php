<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDirectAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya terapkan untuk method GET dan bukan request AJAX
        if ($request->ajax() || !$request->isMethod('GET')) {
            return $next($request);
        }

        // Daftar route yang diperbolehkan diakses langsung (sebagai titik awal masuk aplikasi)
        $exemptedRoutes = [
            '/',
            'login',
            'register',
            'portal',
            'admin/dashboard',
            'superadmin/dashboard',
            'perpustakaan/admin/dashboard',
            'perpustakaan/siswa/dashboard',
            'siswa/dashboard',
            'logout'
        ];

        foreach ($exemptedRoutes as $route) {
            if ($request->is($route)) {
                return $next($request);
            }
        }

        $referer = $request->headers->get('referer');
        $host = $request->getHost();

        // Jika tidak ada referer (URL di-paste langsung/tab baru) atau referer dari host lain
        if (!$referer || parse_url($referer, PHP_URL_HOST) !== $host) {
            // Tolak akses dan arahkan ke root. Root akan otomatis direct ke dashboard yg sesuai jika sudah login, atau ke halaman login jika belum.
            return redirect('/');
        }

        return $next($request);
    }
}
