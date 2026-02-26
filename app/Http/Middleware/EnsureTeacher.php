<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTeacher
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if (!$user || !in_array(($user->role ?? ''), ['class_teacher', 'admin'], true)) {
            abort(403, 'Unauthorized.');
        }
        return $next($request);
    }
}
