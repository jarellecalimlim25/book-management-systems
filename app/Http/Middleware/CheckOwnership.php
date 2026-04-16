<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOwnership
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $resourceId = $request->route('id') ?? $request->route('user');

        // Allow if user is admin or viewing their own resource
        if ($user && ($user->role === 'admin' || $user->id == $resourceId)) {
            return $next($request);
        }

        abort(403, 'Unauthorized. You can only access your own resources.');
    }
}
