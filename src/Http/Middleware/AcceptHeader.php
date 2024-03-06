<?php

namespace TyrantG\LaravelScaffold\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AcceptHeader
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $type = 'json'): mixed
    {
        Str::contains($request->header('Accept'), $contentType = "application/$type") or
        $request->headers->set('Accept', $contentType);

        return $next($request);
    }
}
