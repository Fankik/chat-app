<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware для ограничения запросов в 10 запросов в секунду
 */
class ThrottleRequests
{
    /**
     * @var RateLimiter $rateLimiter
     */
    protected $rateLimiter;

    /**
     * @param RateLimiter $rateLimiter
     */
    public function __construct(RateLimiter $rateLimiter)
    {
        $this->rateLimiter = $rateLimiter;
    }

    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->ip();

        if ($this->rateLimiter->tooManyAttempts($key, 10)) {
            return new Response('Too Many Requests', 429);
        }

        $this->rateLimiter->hit($key, 1);

        return $next($request);
    }
}
