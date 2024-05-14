<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleRequests
{
    protected $rateLimiter;

    public function __construct(RateLimiter $rateLimiter)
    {
        $this->rateLimiter = $rateLimiter;
    }

    public function handle(Request $request, Closure $next)
    {
        $key = $request->ip();

        if ($this->rateLimiter->tooManyAttempts($key, 10)) {
            return new Response('Too Many Requests', 429);
        }

        $this->rateLimiter->hit($key, 1);
  
        return $next($request);
    }
}
