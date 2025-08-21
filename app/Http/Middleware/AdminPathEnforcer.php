<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPathEnforcer
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('admin') || $request->is('admin/*')) {
            if (!Auth::check()) {
                // send to admin login, preserve intended
                session(['url.intended' => $request->fullUrl()]);
                return redirect('/admin/login');
            }
            $u = Auth::user();
            $isAdminFlag = $u && property_exists($u, 'is_admin') && (int)$u->is_admin === 1;
            $isAdminRole = $u && method_exists($u, 'hasAnyRole') && $u->hasAnyRole(['admin','superadmin']);
            if (!($isAdminFlag || $isAdminRole)) {
                abort(403, 'ADMINS ONLY');
            }
        }
        return $next($request);
    }
}
