<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Roles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //usar la funcion hasRole del modelo User
        if (!Auth::user()) {
            return redirect()->route('login');
        } elseif (!Auth::user()->hasRole('admin')) {
            return redirect('/')->with('error', 'No tienes permiso para acceder a esta página');
        } else {
            return $next($request);
        }
    }
}
