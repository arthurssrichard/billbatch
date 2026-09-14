<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use App\Services\ResolverVisitanteService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class VerifyVisitorToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('visitor_token');

        if (! $token) {
            $token = (string) Str::uuid();
            Cookie::queue('visitor_token', $token, 60 * 24 * 30);
        }

        $usuario = ResolverVisitanteService::resolverOuCriar($token);

        app()->instance(Usuario::class, $usuario);

        return $next($request);
    }
}
