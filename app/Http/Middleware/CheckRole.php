<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (! auth()->check()) {
            return redirect('login');
        }

        // 2. Cek apakah role user ada di dalam list roles yang diizinkan
        $user = auth()->user();
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 3. Kalau gak punya akses, lempar ke 403 Forbidden
        abort(403, 'Login declined.');
    }
}
