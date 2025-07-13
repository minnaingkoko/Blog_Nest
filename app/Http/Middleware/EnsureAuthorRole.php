<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthorRole
{
    // EnsureAuthorRole.php
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->isAuthor()) {
            return $next($request);
        }
        abort(403, 'Unauthorized access.');
    }
}
