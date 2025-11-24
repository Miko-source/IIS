<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    // prichazejici request
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Nemáš oprávnění.');
        }

        // string na enum role
        $allowed = collect($roles)
            ->map(fn ($r) => $r instanceof UserRole ? $r->value : $r)
            ->toArray();

        if (!in_array($user->role?->value, $allowed, true)) {
            abort(403, 'Nemáš oprávnění1.');
        }

        return $next($request);
    }
}
