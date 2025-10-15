<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Verificar se o usuário está autenticado
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Não autenticado.'], 401);
            }
            return redirect()->route('admin.login');
        }

        $user = auth()->user();

        // Verificar se o usuário está ativo
        if ($user->status !== 'ativo') {
            auth()->logout();
            
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Usuário não está ativo.'], 403);
            }
            
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Sua conta não está ativa.']);
        }

        // Se roles específicas foram fornecidas, verificar se o usuário tem uma delas
        if (!empty($roles)) {
            if (!$user->hasAnyRole($roles)) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Acesso negado.'], 403);
                }
                
                abort(403, 'Acesso negado.');
            }
        } else {
            // Se não foram especificadas roles, verificar se é pelo menos operador
            $allowedRoles = ['admin', 'operador', 'caixa', 'supervisor'];
            if (!$user->hasAnyRole($allowedRoles)) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Acesso negado.'], 403);
                }
                
                abort(403, 'Acesso negado.');
            }
        }

        return $next($request);
    }
}