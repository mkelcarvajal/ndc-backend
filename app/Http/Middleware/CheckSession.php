<?php

namespace App\Http\Middleware;
use Closure;

class CheckSession
{
    public function handle($request, Closure $next)
    {
        if (!$request->session()->exists('usuario')) 
        {
            return redirect('/login');
        }
        return $next($request);
    }
}
