<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if ($user && $user->role === 'admin') {
                return $next($request);
            }
            return response()->json(['message' => 'Acesso negado. Apenas administradores podem acessar esta rota.'], 403);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao validar o token.'], 401);
        }

        return $next($request);
    }
}
