<?php

namespace App\Domains\System\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Forçar HTTPS apenas em produção
        if (config('app.env') === 'production') {
            // Verificar se já está usando HTTPS
            if (!$request->secure() && !$request->header('X-Forwarded-Proto')) {
                // Redirecionar para HTTPS apenas se não estiver seguro
                $secureUrl = 'https://' . $request->getHost() . $request->getRequestUri();
                return redirect($secureUrl, 301);
            }
            
            // Configurar cookies seguros em produção
            config(['session.secure' => true]);
        }

        return $next($request);
    }
}