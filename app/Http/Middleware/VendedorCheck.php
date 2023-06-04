<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendedorCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->type == 'vendedor') {
            if(Auth::user()->vendedor->status == "A"){
                return $next($request);
            }
            elseif(strpos($request->route()->getName(), 'dashboard') != false){
                return $next($request);
            }
        } 

        return redirect()->route(Auth::user()->type.'/dashboard');
    }  
}
