<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckHasVote
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->guard('voter')->user()->status == true) {
            return redirect()->route('user-voter.thanks');
        }
        return $next($request);
    }
}
