<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Tenta autenticar o usuário usando o token JWT
            $user = JWTAuth::parseToken()->authenticate();

            // Verifica se o usuário autenticado é válido
            if (!$user) {
                return response()->json(['error' => 'Usuário não encontrado'], 404);
            }
        } catch (TokenExpiredException $e) {
            // Caso o token esteja expirado
            return response()->json(['error' => 'Token expirado'], 401);
        } catch (TokenInvalidException $e) {
            // Caso o token seja inválido
            return response()->json(['error' => 'Token inválido'], 401);
        } catch (JWTException $e) {
            // Caso não seja possível validar o token por outros motivos
            return response()->json(['error' => 'Token não fornecido'], 401);
        }

        // Continua para a próxima etapa da aplicação
        return $next($request);

    }
}
