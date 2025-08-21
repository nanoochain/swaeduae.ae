<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin {
  public function handle(Request $request, Closure $next): Response {
    if (!auth()->check() || !method_exists(auth()->user(),'is_admin') || !auth()->user()->is_admin) {
      abort(403);
    }
    return $next($request);
  }
}
