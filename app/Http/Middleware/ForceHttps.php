<?php
// En App\Http\Middleware\ForceHttps.php (crealo si no existe)
namespace App\Http\Middleware;

use Closure;

class ForceHttps
{
    public function handle($request, Closure $next)
    {
        if (!$request->secure()) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
