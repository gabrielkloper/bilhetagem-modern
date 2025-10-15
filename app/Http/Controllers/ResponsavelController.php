<?php

namespace App\Http\Controllers;

use App\Models\Responsavel;
use App\Models\Vinculado;
use App\Models\Evento;
use App\Models\Entrada;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ResponsavelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin,supervisor');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Buscar todos os eventos ativos para o seletor
        $eventos = Evento::where('status', 'ativo')->orderBy('titulo')->get();
        
        $selectedEvento = null;
        $responsaveis = collect();
        $stats = [];
        
        if ($request->filled('evento_id')) {
            $selectedEvento = Evento::find($request->evento_id);
            
            if ($selectedEvento) {
                // Query base das inscrições do evento
                $query = Responsavel::whereHas('inscricoes', function($q) use ($request) {
                        $q->where('evento_id', $request->evento_id);
                    })
                    ->with(['vinculados.entradas' => function($q) {
                        $q->latest('datahora_entrada')->limit(1);
                    }, 'vinculados.vinculo', 'inscricoes' => function($q) use ($request) {
                        $q->where('evento_id', $request->evento_id);
                    }]);
              
                // Filtros
                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->where(function($q) use ($search) {
                        $q->where('nome', 'like', "%{$search}%")
                          ->orWhere('cpf', 'like', "%{$search}%")  
                          ->orWhere('email', 'like', "%{$search}%");
                    });
                }
                
                if ($request->filled('status')) {
                    $query->whereHas('inscricoes', function($q) use ($request) {
                        $q->where('evento_id', $request->evento_id)
                          ->where('ativo', $request->status == '1');
                    });
                }
                
                if ($request->filled('vinculados')) {
                    if ($request->vinculados == 'with') {
                        $query->comVinculados();
                    } elseif ($request->vinculados == 'without') {
                        $query->semVinculados();
                    }
                }
                
                if ($request->filled('periodo')) {
                    switch ($request->periodo) {
                        case 'today':
                            $query->whereDate('created_at', today());
                            break;
                        case 'week':
                            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                            break;
                        case 'month':
                            $query->whereMonth('created_at', now()->month);
                            break;
                    }
                }
                
                $responsaveis = $query->orderBy('nome')
                    ->paginate(15)
                    ->appends($request->query());
                
                // Calcular estatísticas
                $stats = $this->calcularEstatisticas($selectedEvento->id);
            }
        }
        
        return view('admin.responsaveis.index', compact(
            'eventos', 
            'selectedEvento', 
            'responsaveis', 
            'stats'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $eventos = Evento::where('status', 'ativo')->orderBy('titulo')->get();
        $vinculos = \App\Models\Vinculo::ativos()->ordenadosPorDescricao()->get();
        $eventoId = $request->evento_id;
        
        return view('admin.responsaveis.create', compact('eventos', 'vinculos', 'eventoId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Limpar CPF antes da validação
        $cpfLimpo = preg_replace('/\D/', '', $request->cpf);
        $request->merge(['cpf_clean' => $cpfLimpo]);
        
        $request->validate([
            'evento_id' => ['required', 'exists:eventos,id'],
            'nome' => ['required', 'string', 'max:250'],
            'cpf_clean' => [
                'required', 
                'string', 
                'max:11',
                'min:11',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = \App\Models\Responsavel::whereHas('inscricoes', function($q) use ($request) {
                        $q->where('evento_id', $request->evento_id);
                    })->where('cpf', $value)->exists();
                    
                    if ($exists) {
                        $fail('Este CPF já está cadastrado para este evento.');
                    }
                }
            ],
            'email' => ['required', 'email', 'max:250'],
            'telefone1' => ['required', 'string', 'max:20'],
            'telefone2' => ['nullable', 'string', 'max:20'],
            'nascimento' => ['required', 'date', 'before:today'],
            'ativo' => ['nullable', 'in:on'],
            'comunica' => ['nullable', 'in:on'],
            'device_comunica' => ['required', Rule::in(['email', 'sms', 'whatsapp', 'todos'])],
            'vinculados' => ['nullable', 'array'],
            'vinculados.*.nome' => ['required_with:vinculados.*', 'string', 'max:250'],
            'vinculados.*.nascimento' => ['required_with:vinculados.*', 'date', 'before:today'],
            'vinculados.*.vinculo_id' => ['required_with:vinculados.*', 'exists:vinculos,id'],
            'vinculados.*.lembrar' => ['nullable', 'in:on'],
        ], [
            'cpf_clean.required' => 'O campo CPF é obrigatório.',
            'cpf_clean.max' => 'O CPF deve ter exatamente 11 dígitos.',
            'cpf_clean.min' => 'O CPF deve ter exatamente 11 dígitos.',
            'cpf_clean.unique' => 'Este CPF já está cadastrado para este evento.',
            'vinculados.*.vinculo_id.required_with' => 'O campo tipo de vínculo é obrigatório.',
            'vinculados.*.vinculo_id.exists' => 'O tipo de vínculo selecionado é inválido.',
        ]);

        // Verificar se já existe um responsável com este CPF
        $responsavel = Responsavel::where('cpf', $cpfLimpo)->first();
        
        if (!$responsavel) {
            // Criar novo responsável
            $responsavel = Responsavel::create([
                'nome' => $request->nome,
                'cpf' => $cpfLimpo,
                'email' => $request->email,
                'telefone1' => $request->telefone1,
                'telefone2' => $request->telefone2,
                'nascimento' => $request->nascimento,
            ]);
        }
        
        // Criar inscrição no evento
        $responsavel->inscricoes()->create([
            'evento_id' => $request->evento_id,
            'ativo' => $request->has('ativo'),
            'comunica' => $request->has('comunica'),
            'device_comunica' => $request->device_comunica,
            'data_inscricao' => now(),
        ]);

        // Criar vinculados se fornecidos
        if ($request->filled('vinculados')) {
            foreach ($request->vinculados as $vinculadoData) {
                if (!empty($vinculadoData['nome'])) {
                    $responsavel->vinculados()->create([
                        'nome' => $vinculadoData['nome'],
                        'nascimento' => $vinculadoData['nascimento'],
                        'vinculo_id' => $vinculadoData['vinculo_id'],
                        'lembrar' => isset($vinculadoData['lembrar']),
                    ]);
                }
            }
        }

        return redirect()->route('admin.responsaveis.index', ['evento_id' => $request->evento_id])
            ->with('success', 'Responsável criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Responsavel $responsavel, Request $request)
    {
        // Se não foi especificado evento_id, usar o primeiro evento que o responsável está inscrito
        $eventoId = $request->get('evento_id');
        if (!$eventoId) {
            $primeiraInscricao = $responsavel->inscricoes()->first();
            $eventoId = $primeiraInscricao ? $primeiraInscricao->evento_id : null;
        }
        
        if (!$eventoId) {
            return redirect()->route('admin.responsaveis.index')
                ->with('error', 'Evento não especificado ou responsável não possui inscrições.');
        }
        
        $responsavel->load([
            'inscricoes' => function($query) use ($eventoId) {
                if ($eventoId) {
                    $query->where('evento_id', $eventoId);
                }
            },
            'inscricoes.evento',
            'vinculados.entradas' => function($query) {
                $query->orderBy('datahora_entrada', 'desc')->limit(10);
            },
            'vinculados.vinculo'
        ]);

        // Calcular estatísticas do responsável
        $allEntradas = $responsavel->vinculados->flatMap->entradas;
        $inscricaoAtual = $responsavel->inscricoes->first();
        
        $stats = [
            'total_entradas' => $allEntradas->count(),
            'entradas_ativas' => $allEntradas->where('status', 'ativo')->count(),
            'ultima_visita' => $allEntradas->sortByDesc('datahora_entrada')->first(),
            'tempo_total' => $allEntradas->sum('tempo_permanencia'),
            'vinculados_count' => $responsavel->vinculados()->count(),
        ];

        return view('admin.responsaveis.show', compact('responsavel', 'stats', 'inscricaoAtual', 'eventoId'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Responsavel $responsavel, Request $request)
    {
        // Buscar o evento_id da requisição ou da primeira inscrição
        $eventoId = $request->get('evento_id');
        if (!$eventoId) {
            $primeiraInscricao = $responsavel->inscricoes()->first();
            $eventoId = $primeiraInscricao ? $primeiraInscricao->evento_id : null;
        }
        
        if (!$eventoId) {
            return redirect()->route('admin.responsaveis.index')
                ->with('error', 'Evento não especificado ou responsável não possui inscrições em eventos.');
        }
        
        $responsavel->load([
            'vinculados.vinculo',
            'inscricoes' => function($query) use ($eventoId) {
                $query->where('evento_id', $eventoId);
            }
        ]);
        
        $inscricaoAtual = $responsavel->inscricoes->first();
        $eventos = Evento::where('status', 'ativo')->orderBy('titulo')->get();
        $vinculos = \App\Models\Vinculo::ativos()->ordenadosPorDescricao()->get();
        
        return view('admin.responsaveis.edit', compact('responsavel', 'eventos', 'vinculos', 'inscricaoAtual', 'eventoId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Responsavel $responsavel)
    {
        // Limpar CPF antes da validação
        $cpfLimpo = preg_replace('/\D/', '', $request->cpf);
        $request->merge(['cpf_clean' => $cpfLimpo]);
        
        $request->validate([
            'evento_id' => ['required', 'exists:eventos,id'],
            'nome' => ['required', 'string', 'max:250'],
            'cpf_clean' => [
                'required', 
                'string', 
                'max:11',
                'min:11',
                function ($attribute, $value, $fail) use ($request, $responsavel) {
                    // Verificar se outro responsável já tem este CPF inscrito neste evento
                    $exists = \App\Models\Responsavel::where('id', '!=', $responsavel->id)
                        ->whereHas('inscricoes', function($q) use ($request) {
                            $q->where('evento_id', $request->evento_id);
                        })
                        ->where('cpf', $value)->exists();
                    
                    if ($exists) {
                        $fail('Este CPF já está cadastrado para este evento.');
                    }
                }
            ],
            'email' => ['required', 'email', 'max:250'],
            'telefone1' => ['required', 'string', 'max:20'],
            'telefone2' => ['nullable', 'string', 'max:20'],
            'nascimento' => ['required', 'date', 'before:today'],
            'ativo' => ['nullable', 'in:on'],
            'comunica' => ['nullable', 'in:on'],
            'device_comunica' => ['required', Rule::in(['email', 'sms', 'whatsapp', 'todos'])],
            'vinculados' => ['nullable', 'array'],
            'vinculados.*.id' => ['nullable', 'exists:vinculados,id'],
            'vinculados.*.nome' => ['required_with:vinculados.*', 'string', 'max:250'],
            'vinculados.*.nascimento' => ['required_with:vinculados.*', 'date', 'before:today'],
            'vinculados.*.vinculo_id' => ['required_with:vinculados.*', 'exists:vinculos,id'],
            'vinculados.*.lembrar' => ['nullable', 'in:on'],
            'vinculados.*._delete' => ['nullable', 'in:on'],
        ], [
            'cpf_clean.required' => 'O campo CPF é obrigatório.',
            'cpf_clean.max' => 'O CPF deve ter exatamente 11 dígitos.',
            'cpf_clean.min' => 'O CPF deve ter exatamente 11 dígitos.',
            'cpf_clean.unique' => 'Este CPF já está cadastrado para este evento.',
            'vinculados.*.vinculo_id.required_with' => 'O campo tipo de vínculo é obrigatório.',
            'vinculados.*.vinculo_id.exists' => 'O tipo de vínculo selecionado é inválido.',
        ]);

        // Atualizar dados pessoais do responsável
        $responsavel->update([
            'nome' => $request->nome,
            'cpf' => $cpfLimpo,
            'email' => $request->email,
            'telefone1' => $request->telefone1,
            'telefone2' => $request->telefone2,
            'nascimento' => $request->nascimento,
        ]);
        
        // Atualizar inscrição no evento
        $inscricao = $responsavel->inscricoes()->where('evento_id', $request->evento_id)->first();
        if ($inscricao) {
            $inscricao->update([
                'ativo' => $request->has('ativo'),
                'comunica' => $request->has('comunica'),
                'device_comunica' => $request->device_comunica,
            ]);
        }

        // Gerenciar vinculados
        if ($request->filled('vinculados')) {
            $vinculadoIds = [];
            
            foreach ($request->vinculados as $vinculadoData) {
                if (isset($vinculadoData['_delete']) && $vinculadoData['_delete']) {
                    // Deletar vinculado
                    if (isset($vinculadoData['id'])) {
                        Vinculado::find($vinculadoData['id'])?->delete();
                    }
                    continue;
                }
                
                if (!empty($vinculadoData['nome'])) {
                    if (isset($vinculadoData['id'])) {
                        // Atualizar existente
                        $vinculado = Vinculado::find($vinculadoData['id']);
                        if ($vinculado) {
                            $vinculado->update([
                                'nome' => $vinculadoData['nome'],
                                'nascimento' => $vinculadoData['nascimento'],
                                'vinculo_id' => $vinculadoData['vinculo_id'],
                                'lembrar' => isset($vinculadoData['lembrar']),
                            ]);
                            $vinculadoIds[] = $vinculado->id;
                        }
                    } else {
                        // Criar novo
                        $vinculado = $responsavel->vinculados()->create([
                            'nome' => $vinculadoData['nome'],
                            'nascimento' => $vinculadoData['nascimento'],
                            'vinculo_id' => $vinculadoData['vinculo_id'],
                            'lembrar' => isset($vinculadoData['lembrar']),
                        ]);
                        $vinculadoIds[] = $vinculado->id;
                    }
                }
            }
        }

        return redirect()->route('admin.responsaveis.index', ['evento_id' => $request->evento_id])
            ->with('success', 'Responsável atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Responsavel $responsavel)
    {
        // Verificar se pode deletar (através dos vinculados)
        $hasEntradas = $responsavel->vinculados()->whereHas('entradas')->exists();
        if ($hasEntradas) {
            return response()->json([
                'error' => 'Não é possível excluir responsável que possui entradas registradas.'
            ], 422);
        }

        $eventoId = $responsavel->evento_id;
        $responsavel->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => 'Responsável excluído com sucesso!'
            ]);
        }

        return redirect()->route('admin.responsaveis.index', ['evento_id' => $eventoId])
            ->with('success', 'Responsável excluído com sucesso!');
    }

    /**
     * Calcular estatísticas por evento
     */
    private function calcularEstatisticas($eventoId)
    {
        $totalResponsaveis = Responsavel::whereHas('inscricoes', function($q) use ($eventoId) {
            $q->where('evento_id', $eventoId);
        })->count();
        
        $responsaveisAtivos = Responsavel::whereHas('inscricoes', function($q) use ($eventoId) {
            $q->where('evento_id', $eventoId)->where('ativo', true);
        })->count();
        
        $comVinculados = Responsavel::whereHas('inscricoes', function($q) use ($eventoId) {
            $q->where('evento_id', $eventoId);
        })->comVinculados()->count();
        
        $cadastrosHoje = \App\Models\Inscricao::where('evento_id', $eventoId)
            ->whereDate('created_at', today())->count();
        
        // Responsáveis atualmente no parque (com entrada ativa através dos vinculados)
        $noParque = Responsavel::whereHas('inscricoes', function($q) use ($eventoId) {
                $q->where('evento_id', $eventoId);
            })
            ->whereHas('vinculados.entradas', function($query) {
                $query->whereIn('status', ['ativo'])->whereNull('datahora_saida');
            })->count();

        return [
            'total' => $totalResponsaveis,
            'ativos' => $responsaveisAtivos,
            'com_vinculados' => $comVinculados,
            'cadastros_hoje' => $cadastrosHoje,
            'no_parque' => $noParque,
        ];
    }

    /**
     * Toggle status do responsável
     */
    public function toggleStatus(Responsavel $responsavel, Request $request)
    {
        // Tentar obter evento_id da query string ou do body da requisição
        $eventoId = $request->get('evento_id');
        if (!$eventoId && $request->isJson()) {
            $data = $request->json()->all();
            $eventoId = $data['evento_id'] ?? null;
        }
        
        if (!$eventoId) {
            return response()->json(['error' => 'Evento não especificado'], 400);
        }
        
        $inscricao = $responsavel->inscricoes()->where('evento_id', $eventoId)->first();
        if (!$inscricao) {
            return response()->json(['error' => 'Inscrição não encontrada'], 404);
        }
        
        $novoStatus = !$inscricao->ativo;
        $inscricao->update(['ativo' => $novoStatus]);

        return response()->json([
            'success' => 'Status da inscrição atualizado com sucesso!',
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
            'responsaveis' => ['required', 'array'],
            'responsaveis.*' => ['exists:responsaveis,id'],
            'evento_id' => ['required', 'exists:eventos,id'],
        ]);

        $count = 0;
        $eventoId = $request->evento_id;
        
        foreach ($request->responsaveis as $id) {
            $responsavel = Responsavel::find($id);
            if (!$responsavel) continue;
            
            $inscricao = $responsavel->inscricoes()->where('evento_id', $eventoId)->first();
            if (!$inscricao) continue;
            
            switch ($request->action) {
                case 'activate':
                    $inscricao->update(['ativo' => true]);
                    $count++;
                    break;
                case 'deactivate':
                    $inscricao->update(['ativo' => false]);
                    $count++;
                    break;
                case 'delete':
                    $hasEntradas = $responsavel->vinculados()->whereHas('entradas')->exists();
                    if (!$hasEntradas) {
                        // Deleta apenas a inscrição, não o responsável
                        $inscricao->delete();
                        $count++;
                    }
                    break;
            }
        }

        return response()->json([
            'success' => "{$count} responsável(is) processado(s) com sucesso!"
        ]);
    }
}