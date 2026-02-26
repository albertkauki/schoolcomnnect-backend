<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if (!$user || ($user->role ?? '') !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        return $next($request);
    }
}
