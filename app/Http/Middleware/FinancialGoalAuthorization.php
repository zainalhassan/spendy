<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class FinancialGoalAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // For index and create routes, check general permissions
        if ($request->routeIs('financial-goals.index') || $request->routeIs('financial-goals.create')) {
            if (!Gate::allows('view-financial-goals')) {
                abort(403, 'Unauthorized to view financial goals.');
            }
        }

        // For store route, check create permission
        if ($request->routeIs('financial-goals.store')) {
            if (!Gate::allows('create-financial-goals')) {
                abort(403, 'Unauthorized to create financial goals.');
            }
        }

        return $next($request);
    }
}
