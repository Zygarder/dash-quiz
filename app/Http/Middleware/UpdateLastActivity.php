<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if ($user) {
            if (!$user->last_activity || $user->last_activity->lt(now()->subMinute())) {
                $user->update([
                    'last_activity' => now(),
                    'active_status' => 1
                ]);
            }
        }

        return $next($request);
    }
}
