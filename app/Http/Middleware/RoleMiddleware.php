<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Použití: ->middleware('role:admin') nebo 'role:admin,coordinator'
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Nemáš oprávnění.');
        }

        // převedeme stringy na enum hodnoty (pokud používáš enum)
        $allowed = collect($roles)
            ->map(fn ($r) => $r instanceof UserRole ? $r->value : $r)
            ->toArray();

        if (!in_array($user->role?->value, $allowed, true)) {
            abort(403, 'Nemáš oprávnění.');
        }

        return $next($request);
    }
}
