<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TkrOnlyMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            $jurusanUpper = strtoupper(trim($user->jurusan ?? ''));
            if ($user->role === 'siswa' && !in_array($jurusanUpper, ['TKR', 'TEKNIK KENDARAAN RINGAN'])) {
                return redirect()->route('portal')->with('error', 'Akses ditolak. Layanan Bengkel hanya diperuntukkan bagi siswa Jurusan Teknik Kendaraan Ringan (TKR).');
            }
        }

        return $next($request);
    }
}
