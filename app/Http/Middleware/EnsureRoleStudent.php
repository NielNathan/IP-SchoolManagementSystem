<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRoleStudent
{
    public function handle(Request $request, Closure $next)
    {
        // Cek Login & Role
        if (Auth::check() && Auth::user()->role === 'student') {
            return $next($request);
        }
        
        // Kalau bukan siswa, tendang keluar
        abort(403, 'Anda bukan Siswa!');
    }
}