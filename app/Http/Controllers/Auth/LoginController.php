<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        // Verificar se o usuário está ativo
        if ($user->status !== 'ativo') {
            $statusMessages = [
                'inativo' => 'Sua conta está temporariamente desativada. Entre em contato com o administrador.',
                'suspenso' => 'Sua conta foi suspensa. Entre em contato com o administrador.',
                'bloqueado' => 'Sua conta foi bloqueada permanentemente.',
            ];
            
            throw ValidationException::withMessages([
                'email' => [$statusMessages[$user->status] ?? 'Sua conta não está ativa.'],
            ]);
        }

        // Login via session para web
        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    // API Login - retorna token
    public function apiLogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_name' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        // Verificar se o usuário está ativo
        if ($user->status !== 'ativo') {
            throw ValidationException::withMessages([
                'email' => ['Usuário não está ativo.'],
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'user' => $user->load('evento'),
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    // API Logout
    public function apiLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso.']);
    }

    // API User Info
    public function apiUser(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load('evento'),
        ]);
    }
}