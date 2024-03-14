<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $hr = ["dashboard","human-resource", "profile"];
        $user = ["dashboard","profile"];
        $current_route = $request->route()->getName();

        if(Auth::user()->role === "system-admin" ) {
            return $next($request);
        }

        if(Auth::user()->role == "hr" && in_array($current_route, $hr)) {
            return $next($request);
        }

        if(Auth::user()->role == "user" && in_array($current_route, $user)) {
            return $next($request);
        }

        return redirect()->back();
    }
}
