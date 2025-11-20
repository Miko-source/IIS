<?php
/**
 * ---------------------------------------------------------
 * Autor:  Martin Bureš
 * Login:  xbures38
 * ---------------------------------------------------------
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleAtLeastMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();
        //$role ocekava honotu z app/Enums/UserRole.php
        if (!$user || !$user->hasRoleOrHigher($role)) {
            abort(403, 'Nemáš oprávnění.');
        }

        return $next($request);
    }
}
