<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Este middleware é usado principalmente para APIs via Sanctum
        if (auth('sanctum')->check()) {
            $user = auth('sanctum')->user();
            
            // Verificar se o usuário está ativo
            if ($user->status !== 'ativo') {
                return response()->json([
                    'message' => 'Usuário não está ativo.',
                    'status' => $user->status
                ], 403);
            }
        }

        return $next($request);
    }
}