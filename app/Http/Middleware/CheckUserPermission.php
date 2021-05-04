<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $perm)
    {
        if (!auth()->user()->hasPermission($perm)) {
            abort(403, 'Нет прав на данное действие');
        }
        return $next($request);
    }
}
