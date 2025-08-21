<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware; // for older stacks
// If Laravel 11 native middleware is used instead, fallback:
if (!class_exists(Middleware::class)) {
    class_alias(\Illuminate\Http\Middleware\TrustProxies::class, Middleware::class);
}

class TrustProxies extends Middleware
{
    protected $proxies = '*'; // trust Cloudflare/proxy chain

    protected $headers =
          Request::HEADER_X_FORWARDED_FOR
        | Request::HEADER_X_FORWARDED_HOST
        | Request::HEADER_X_FORWARDED_PORT
        | Request::HEADER_X_FORWARDED_PROTO
        | Request::HEADER_X_FORWARDED_AWS_ELB;
}
