<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NeedChangePassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()->needChangePassword)
        {
            return redirect()->route('auth.change-password');
        }
        else
            return $next($request);
    }
}
