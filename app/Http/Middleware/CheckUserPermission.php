<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @param $perm
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $perm)
    {
        if (!Auth::user()->hasPermission($perm)) {
            abort(403, 'Нет прав на данное действие');
        }
        return $next($request);
    }
}
