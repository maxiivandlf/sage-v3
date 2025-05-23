<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class LimpiarInyeccionHTML
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
{
    $response = $next($request);

    // Verificamos que sea texto o JSON, no binario
    $contentType = $response->headers->get('Content-Type');

    if ($contentType && (str_contains($contentType, 'text/html') || str_contains($contentType, 'application/json'))) {
        $contenido = $response->getContent();

        // Limpia solo si contiene la inyección
        if (str_contains($contenido, 'Selamat datang')) {
            logger('⚠️ Inyección detectada y limpiada en: ' . $request->fullUrl());

            $limpio = preg_replace('/<div style="display:none;">.*?<\/div>/s', '', $contenido);
            $response->setContent($limpio);
        }
    }

    return $response;
}

}
