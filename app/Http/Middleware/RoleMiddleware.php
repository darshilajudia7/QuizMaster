<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Session::has('user_id')) {
            return redirect('/login')
                ->with('error', 'Please login first.');
        }

        if (Session::get('user_role') !== $role) {
            return redirect('/login')
                ->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}
