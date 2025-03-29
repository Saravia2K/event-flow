<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  ...$guards
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        if (Auth::check())
            return $this->redirectAuthenticatedUser();

        return $next($request);
    }

    /**
     * Redirige al usuario autenticado según su rol
     */
    protected function redirectAuthenticatedUser()
    {
        $user = Auth::user();
        return redirect(
            route($user->isAdmin()
                ? "organizer.dashboard"
                : "index")
        );
    }
}