<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Response as LaravelResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MicroCache {
  public function handle($request, Closure $next){
    $isGet   = $request->isMethod('GET');
    $isGuest = !auth()->check();
    $key     = 'mc:'.sha1($request->fullUrl());

    // Only consider GET for guests
    if (!$isGet) {
      $resp = $next($request);
      $resp->headers->set('X-Micro-Cache','BYPASS-NOTGET');
      return $resp;
    }
    if (!$isGuest) {
      $resp = $next($request);
      $resp->headers->set('X-Micro-Cache','BYPASS-AUTH');
      return $resp;
    }

    // Serve from cache if present
    if (Cache::has($key)) {
      return (new LaravelResponse(Cache::get($key), 200))
        ->header('Content-Type','text/html; charset=UTF-8')
        ->header('X-Micro-Cache','HIT');
    }

    $resp = $next($request);

    // Only cache successful, non-streamed HTML-ish responses
    $statusOk = $resp->getStatusCode() === 200;
    $isStream = ($resp instanceof StreamedResponse) || ($resp instanceof BinaryFileResponse);
    $ct       = $resp->headers->get('Content-Type','');

    $looksHtml = stripos($ct,'text/html') !== false;
    if (!$looksHtml && $resp instanceof LaravelResponse) {
      // Fallback heuristic: if we can get string content, treat as HTML-ish
      try { $content = $resp->getContent(); $looksHtml = is_string($content); } catch (\Throwable $e) { $looksHtml = false; }
    }

    if ($statusOk && !$isStream && $looksHtml) {
      try {
        $content = $resp->getContent();
        if (is_string($content) && $content !== '') {
          Cache::put($key, $content, 60); // 60 seconds
          $resp->headers->set('X-Micro-Cache','MISS');
          return $resp;
        }
      } catch (\Throwable $e) {
        // fall through
      }
      $resp->headers->set('X-Micro-Cache','BYPASS-NOCONTENT');
      return $resp;
    }

    $reason = !$statusOk ? 'BYPASS-STATUS' : ($isStream ? 'BYPASS-STREAM' : 'BYPASS-NONHTML');
    $resp->headers->set('X-Micro-Cache',$reason);
    return $resp;
  }
}
