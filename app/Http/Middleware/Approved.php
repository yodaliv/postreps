<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class Approved
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
        $isApproved = auth()->user()->approved || auth()->user()->role == User::ROLE_SUPER_ADMIN ? true : false;

        if( ! $isApproved) {
          return redirect('/account/inactive');
        }

        return $next($request);
    }
}
