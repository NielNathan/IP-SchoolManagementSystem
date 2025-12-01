<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRoleAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }
        abort(403, 'Akses Ditolak. Halaman ini khusus Admin.');
    }
}