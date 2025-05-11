<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ControlDelTiempoUsuario
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
        //no use user porque no uso sistema automatico de laravel para autenticar
        //$user = Auth::user();
        
        if (Session::has('last_activity')) {
            $lastActivity = Session::get('last_activity');
            $inactiveTime = now()->diffInMinutes($lastActivity);

            if ($inactiveTime >= 1) {
                //Auth::logout();
                Session::flush();
                return redirect()->route('/')->with('FinDeSession', 'OK');
            }
        }

        Session::put('last_activity', now());
        return $next($request);
    }
}
