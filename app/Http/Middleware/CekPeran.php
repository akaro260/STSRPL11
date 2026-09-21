<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware sederhana untuk membatasi halaman berdasarkan peran.
 * Contoh pakai di routes: Route::get('/pengguna', ...)->middleware('peran:admin');
 * Bisa lebih dari satu peran: ->middleware('peran:admin,petugas');
 */
class CekPeran
{
    public function handle(Request $request, Closure $next, ...$peranYangDiizinkan)
    {
        $peranUser = Auth::user()->role;

        if (!in_array($peranUser, $peranYangDiizinkan)) {
            abort(403, 'Anda tidak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}
