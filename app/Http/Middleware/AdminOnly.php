<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        $u = $request->user();
        $ok = false;

        if ($u) {
            // Spatie roles/permissions if present
            if (method_exists($u, 'hasRole') && $u->hasRole('admin')) $ok = true;
            if (method_exists($u, 'can') && $u->can('access admin')) $ok = true;
            // fallback boolean column (if exists)
            if (isset($u->is_admin) && $u->is_admin) $ok = true;
        }

        if (!$ok) abort(403);
        return $next($request);
    }
}
