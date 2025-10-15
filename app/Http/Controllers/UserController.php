<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }

    public function index(Request $request)
    {
        $query = User::with(['evento:id,titulo']);
        
        // Filtros
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('evento_id')) {
            $query->where('evento_id', $request->evento_id);
        }
        
        $usuarios = $query->orderBy('created_at', 'desc')
                         ->paginate(15)
                         ->appends($request->query());
        
        $eventos = Evento::where('status', 'ativo')->get();
        
        return view('admin.usuarios.index', compact('usuarios', 'eventos'));
    }

    public function create()
    {
        $eventos = Evento::where('status', 'ativo')->get();
        return view('admin.usuarios.create', compact('eventos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', Rule::in(['admin', 'operador', 'caixa', 'supervisor'])],
            'status' => ['required', Rule::in(['ativo', 'inativo', 'suspenso', 'bloqueado'])],
            'evento_id' => ['nullable', 'exists:eventos,id'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $password = $request->password ?: Str::random(8);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
            'evento_id' => $request->evento_id,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        // TODO: Enviar email com credenciais

        return redirect()->route('admin.usuarios.index')
            ->with('success', "Usuário criado com sucesso! Senha: {$password}");
    }

    public function show(User $usuario)
    {
        $usuario->load('evento');
        return view('admin.usuarios.show', compact('usuario'));
    }

    public function edit(User $usuario)
    {
        $eventos = Evento::where('status', 'ativo')->get();
        return view('admin.usuarios.edit', compact('usuario', 'eventos'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($usuario->id)],
            'role' => ['required', Rule::in(['admin', 'operador', 'caixa', 'supervisor'])],
            'status' => ['required', Rule::in(['ativo', 'inativo', 'suspenso', 'bloqueado'])],
            'evento_id' => ['nullable', 'exists:eventos,id'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
            'evento_id' => $request->evento_id,
        ];

        if ($request->password) {
            $updateData['password'] = Hash::make($request->password);
        }

        $usuario->update($updateData);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $usuario)
    {
        // Não permite deletar o próprio usuário
        if ($usuario->id === auth()->id()) {
            return response()->json(['error' => 'Não é possível deletar seu próprio usuário'], 422);
        }

        // Não permite deletar se for o último admin
        if ($usuario->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return response()->json(['error' => 'Não é possível deletar o último administrador'], 422);
        }

        $usuario->delete();

        return response()->json(['success' => 'Usuário deletado com sucesso!']);
    }


    public function bulkStatus(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'status' => ['required', Rule::in(['ativo', 'inativo', 'suspenso', 'bloqueado'])],
        ]);

        $updated = User::whereIn('id', $request->ids)
            ->where('id', '!=', auth()->id()) // Não alterar próprio usuário
            ->update(['status' => $request->status]);

        return response()->json([
            'success' => "Status atualizado para {$updated} usuários."
        ]);
    }
}