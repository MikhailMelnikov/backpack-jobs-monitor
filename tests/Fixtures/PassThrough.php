<?php

declare(strict_types=1);

namespace MikhailMelnikov\BackpackJobsMonitor\Tests\Fixtures;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Stand-in for the application's CheckIfAdmin middleware, which testbench lacks.
 */
class PassThrough
{
    /**
     * Let the request through.
     *
     * @param Request                      $request Incoming request
     * @param Closure(Request): Response   $next    Next handler
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
