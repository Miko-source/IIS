<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserActive
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role->value === 'deactivated') {
            Auth::logout();

            return redirect()
                ->route('login')
                ->with('error', 'Počkejte, než váš účet aktivuji.');
        }

        return $next($request);
    }
}
