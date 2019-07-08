<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check())
            if (Auth::user()->role->id == 1)
                return $next($request);
            else 
                return abort('403');
        else
            if ($request->wantsJson())
                return response()->json(['error' => __('response.e403')], 403);
            else
                return abort('403');
            
    }
}
