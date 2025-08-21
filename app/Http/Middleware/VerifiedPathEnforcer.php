<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class VerifiedPathEnforcer
{
    public function handle(Request $request, Closure $next)
    {
        // Require verification for these areas
        $should = $request->is('org/*') || $request->is('profile') || $request->is('profile/*') || $request->is('my/*');

        // Allow these without verification
        $exempt = $request->is('org/login') || $request->is('org/register')
                || $request->is('organization/login') || $request->is('organization/register');

        if ($should && !$exempt) {
            if (!Auth::check()) {
                return redirect()->guest('/login');
            }
            $user = Auth::user();
            if (method_exists($user, 'hasVerifiedEmail') && !$user->hasVerifiedEmail()) {
                // Prefer the standard notice route if it exists; otherwise, fall back to /verify
                return Route::has('verification.notice')
                    ? redirect()->route('verification.notice')
                    : redirect('/verify');
            }
        }
        return $next($request);
    }
}
