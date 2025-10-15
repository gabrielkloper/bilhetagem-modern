<?php

namespace App\Http\Controllers;

use App\Models\Vinculo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VinculoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Vinculo::query();

        // Filtros
        if ($request->filled('search')) {
            $query->where('descricao', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('ativo', $request->status == '1');
        }

        $vinculos = $query->withCount('vinculados')
            ->ordenadosPorDescricao()
            ->paginate(15)
            ->appends($request->query());

        $stats = [
            'total' => Vinculo::count(),
            'ativos' => Vinculo::where('ativo', true)->count(),
            'inativos' => Vinculo::where('ativo', false)->count(),
            'em_uso' => Vinculo::whereHas('vinculados')->count(),
        ];

        return view('admin.vinculos.index', compact('vinculos', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vinculos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'descricao' => ['required', 'string', 'max:100', 'unique:vinculos,descricao'],
            'ativo' => ['boolean'],
        ]);

        Vinculo::create([
            'descricao' => $request->descricao,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('admin.vinculos.index')
            ->with('success', 'Vínculo criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vinculo $vinculo)
    {
        $vinculo->load('vinculados.responsavel');
        $vinculo->loadCount('vinculados');

        $stats = [
            'vinculados_count' => $vinculo->vinculados()->count(),
            'responsaveis_count' => $vinculo->vinculados()->distinct('responsavel_id')->count('responsavel_id'),
        ];

        return view('admin.vinculos.show', compact('vinculo', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vinculo $vinculo)
    {
        return view('admin.vinculos.edit', compact('vinculo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vinculo $vinculo)
    {
        $request->validate([
            'descricao' => [
                'required', 
                'string', 
                'max:100', 
                Rule::unique('vinculos', 'descricao')->ignore($vinculo->id)
            ],
            'ativo' => ['boolean'],
        ]);

        $vinculo->update([
            'descricao' => $request->descricao,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('admin.vinculos.index')
            ->with('success', 'Vínculo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vinculo $vinculo)
    {
        // Verificar se o vínculo está sendo usado
        if ($vinculo->vinculados()->exists()) {
            return response()->json([
                'error' => 'Não é possível excluir vínculo que possui vinculados associados.'
            ], 422);
        }

        $vinculo->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => 'Vínculo excluído com sucesso!'
            ]);
        }

        return redirect()->route('admin.vinculos.index')
            ->with('success', 'Vínculo excluído com sucesso!');
    }

    /**
     * Toggle status do vínculo
     */
    public function toggleStatus(Vinculo $vinculo)
    {
        $novoStatus = !$vinculo->ativo;
        
        $vinculo->update(['ativo' => $novoStatus]);

        return response()->json([
            'success' => 'Status atualizado com sucesso!',
            'ativo' => $novoStatus
        ]);
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => ['required', Rule::in(['activate', 'deactivate', 'delete'])],
            'vinculos' => ['required', 'array'],
            'vinculos.*' => ['exists:vinculos,id'],
        ]);

        $count = 0;
        
        foreach ($request->vinculos as $id) {
            $vinculo = Vinculo::find($id);
            if (!$vinculo) continue;
            
            switch ($request->action) {
                case 'activate':
                    $vinculo->update(['ativo' => true]);
                    $count++;
                    break;
                case 'deactivate':
                    $vinculo->update(['ativo' => false]);
                    $count++;
                    break;
                case 'delete':
                    if (!$vinculo->vinculados()->exists()) {
                        $vinculo->delete();
                        $count++;
                    }
                    break;
            }
        }

        return response()->json([
            'success' => "{$count} vínculo(s) processado(s) com sucesso!"
        ]);
    }
}