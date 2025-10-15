<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class EventoController extends Controller
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
        $query = Evento::with(['userCriacao:id,name']);
        
        // Filtros
        if ($request->filled('search')) {
            $query->where('titulo', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('publico')) {
            $query->where('publico', $request->publico === '1');
        }
        
        if ($request->filled('data_inicio')) {
            $query->whereDate('data_inicio', '>=', $request->data_inicio);
        }
        
        if ($request->filled('data_fim')) {
            $query->whereDate('data_fim', '<=', $request->data_fim);
        }
        
        $eventos = $query->orderBy('data_inicio', 'desc')
                        ->paginate(10)
                        ->appends($request->query());
        
        return view('admin.eventos.index', compact('eventos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.eventos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string', 'max:1000'],
            'local' => ['required', 'string', 'max:250'],
            'cidade' => ['required', 'string', 'max:250'],
            'endereco' => ['nullable', 'string', 'max:500'],
            'data_inicio' => ['required', 'date'],
            'data_fim' => ['required', 'date', 'after_or_equal:data_inicio'],
            'hora_inicio' => ['nullable', 'date_format:H:i'],
            'hora_fim' => ['nullable', 'date_format:H:i'],
            'capacidade' => ['required', 'integer', 'min:1'],
            'preco_padrao' => ['nullable', 'numeric', 'min:0'],
            'observacoes' => ['nullable', 'string', 'max:2500'],
            'status' => ['required', Rule::in(['ativo', 'inativo', 'cancelado', 'finalizado'])],
            'ativo' => ['boolean'],
            'publico' => ['boolean'],
            'permite_prevenda' => ['boolean'],
            'idade_minima' => ['nullable', 'integer', 'min:0', 'max:150'],
            'idade_maxima' => ['nullable', 'integer', 'min:0', 'max:150'],
            'aceita_dinheiro' => ['boolean'],
            'aceita_cartao' => ['boolean'],
            'aceita_pix' => ['boolean'],
            'aceita_gratuito' => ['boolean'],
            'tempo_atualiza' => ['nullable', 'integer', 'min:1'],
            'tempo_tela' => ['nullable', 'integer', 'min:1'],
            'mostra_tempo' => ['boolean'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'regras_home' => ['nullable', 'string', 'max:2500'],
            'regras_cadastro' => ['nullable', 'string', 'max:2500'],
            'regras_parque' => ['nullable', 'string', 'max:2500'],
            'regras_comunica' => ['nullable', 'string', 'max:2500'],
            'msg_fimreserva' => ['nullable', 'string', 'max:2500'],
        ]);
        
        // Validação personalizada: pelo menos um modo de pagamento deve ser selecionado
        if (!$request->aceita_dinheiro && !$request->aceita_cartao && 
            !$request->aceita_pix && !$request->aceita_gratuito) {
            return redirect()->back()
                ->withErrors(['modos_pagamento' => 'Selecione pelo menos um modo de pagamento.'])
                ->withInput();
        }

        $evento = Evento::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'local' => $request->local,
            'cidade' => $request->cidade,
            'endereco' => $request->endereco,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
            'hora_inicio' => $request->hora_inicio,
            'hora_fim' => $request->hora_fim,
            'capacidade' => $request->capacidade,
            'preco_padrao' => $request->preco_padrao_clean ?: ($request->preco_padrao ?? 0.00),
            'status' => $request->status,
            'ativo' => $request->has('ativo') ?? true,
            'publico' => $request->has('publico'),
            'permite_prevenda' => $request->has('permite_prevenda'),
            'idade_minima' => $request->idade_minima,
            'idade_maxima' => $request->idade_maxima,
            'aceita_dinheiro' => $request->has('aceita_dinheiro'),
            'aceita_cartao' => $request->has('aceita_cartao'),
            'aceita_pix' => $request->has('aceita_pix'),
            'aceita_gratuito' => $request->has('aceita_gratuito'),
            'tempo_atualiza' => $request->tempo_atualiza ?? 10,
            'tempo_tela' => $request->tempo_tela ?? 3600,
            'mostra_tempo' => $request->has('mostra_tempo') ?? true,
            'timezone' => $request->timezone ?? 'America/Sao_Paulo',
            'regras_home' => $request->regras_home,
            'regras_cadastro' => $request->regras_cadastro,
            'regras_parque' => $request->regras_parque,
            'regras_comunica' => $request->regras_comunica,
            'msg_fimreserva' => $request->msg_fimreserva,
            'observacoes' => $request->observacoes,
            'hash' => strtoupper(substr(md5($request->titulo . time() . rand()), 0, 10)),
            'user_criacao' => auth()->id(),
            'user_atualiza' => auth()->id(),
        ]);

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Evento $evento)
    {
        $evento->load(['userCriacao', 'userAtualiza']);
        
        // Estatísticas do evento
        $stats = [
            'total_entradas' => $evento->entradas()->count(),
            'pessoas_dentro' => $evento->entradas()
                ->where('status', 'ativo')
                ->whereNull('datahora_saida')
                ->count(),
            'receita_total' => $evento->caixaMovimentos()
                ->where('tipo_item', 'entrada')
                ->where('ativo', true)
                ->sum('valor'),
            'capacidade_utilizada' => $evento->capacidade > 0 
                ? round(($evento->entradas()->where('status', 'ativo')->whereNull('datahora_saida')->count() / $evento->capacidade) * 100, 1)
                : 0
        ];
        
        return view('admin.eventos.show', compact('evento', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evento $evento)
    {
        return view('admin.eventos.edit', compact('evento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evento $evento)
    {
        $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string', 'max:1000'],
            'local' => ['required', 'string', 'max:250'],
            'cidade' => ['required', 'string', 'max:250'],
            'endereco' => ['nullable', 'string', 'max:500'],
            'data_inicio' => ['required', 'date'],
            'data_fim' => ['required', 'date', 'after_or_equal:data_inicio'],
            'hora_inicio' => ['nullable', 'date_format:H:i'],
            'hora_fim' => ['nullable', 'date_format:H:i'],
            'capacidade' => ['required', 'integer', 'min:1'],
            'preco_padrao' => ['nullable', 'numeric', 'min:0'],
            'observacoes' => ['nullable', 'string', 'max:2500'],
            'status' => ['required', Rule::in(['ativo', 'inativo', 'cancelado', 'finalizado'])],
            'ativo' => ['boolean'],
            'publico' => ['boolean'],
            'permite_prevenda' => ['boolean'],
            'idade_minima' => ['nullable', 'integer', 'min:0', 'max:150'],
            'idade_maxima' => ['nullable', 'integer', 'min:0', 'max:150'],
            'aceita_dinheiro' => ['boolean'],
            'aceita_cartao' => ['boolean'],
            'aceita_pix' => ['boolean'],
            'aceita_gratuito' => ['boolean'],
            'tempo_atualiza' => ['nullable', 'integer', 'min:1'],
            'tempo_tela' => ['nullable', 'integer', 'min:1'],
            'mostra_tempo' => ['boolean'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'regras_home' => ['nullable', 'string', 'max:2500'],
            'regras_cadastro' => ['nullable', 'string', 'max:2500'],
            'regras_parque' => ['nullable', 'string', 'max:2500'],
            'regras_comunica' => ['nullable', 'string', 'max:2500'],
            'msg_fimreserva' => ['nullable', 'string', 'max:2500'],
        ]);

        // Validação personalizada: pelo menos um modo de pagamento deve ser selecionado
        if (!$request->aceita_dinheiro && !$request->aceita_cartao && 
            !$request->aceita_pix && !$request->aceita_gratuito) {
            return redirect()->back()
                ->withErrors(['modos_pagamento' => 'Selecione pelo menos um modo de pagamento.'])
                ->withInput();
        }

        $evento->update([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'local' => $request->local,
            'cidade' => $request->cidade,
            'endereco' => $request->endereco,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
            'hora_inicio' => $request->hora_inicio,
            'hora_fim' => $request->hora_fim,
            'capacidade' => $request->capacidade,
            'preco_padrao' => $request->preco_padrao_clean ?: ($request->preco_padrao ?? 0.00),
            'status' => $request->status,
            'ativo' => $request->has('ativo'),
            'publico' => $request->has('publico'),
            'permite_prevenda' => $request->has('permite_prevenda'),
            'idade_minima' => $request->idade_minima,
            'idade_maxima' => $request->idade_maxima,
            'aceita_dinheiro' => $request->has('aceita_dinheiro'),
            'aceita_cartao' => $request->has('aceita_cartao'),
            'aceita_pix' => $request->has('aceita_pix'),
            'aceita_gratuito' => $request->has('aceita_gratuito'),
            'tempo_atualiza' => $request->tempo_atualiza ?? $evento->tempo_atualiza,
            'tempo_tela' => $request->tempo_tela ?? $evento->tempo_tela,
            'mostra_tempo' => $request->has('mostra_tempo'),
            'timezone' => $request->timezone ?? $evento->timezone,
            'regras_home' => $request->regras_home,
            'regras_cadastro' => $request->regras_cadastro,
            'regras_parque' => $request->regras_parque,
            'regras_comunica' => $request->regras_comunica,
            'msg_fimreserva' => $request->msg_fimreserva,
            'observacoes' => $request->observacoes,
            'user_atualiza' => auth()->id(),
        ]);

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evento $evento)
    {
        // Verificar se o evento pode ser excluído
        if ($evento->entradas()->exists()) {
            return response()->json([
                'error' => 'Não é possível excluir um evento que possui entradas registradas.'
            ], 422);
        }

        if ($evento->prevendas()->exists()) {
            return response()->json([
                'error' => 'Não é possível excluir um evento que possui prevendas registradas.'
            ], 422);
        }

        $evento->delete();

        return response()->json([
            'success' => 'Evento excluído com sucesso!'
        ]);
    }


    /**
     * Ativar/Desativar evento
     */
    public function toggleStatus(Evento $evento)
    {
        $newStatus = $evento->status === 'ativo' ? 'inativo' : 'ativo';
        
        $evento->update([
            'status' => $newStatus,
            'user_atualiza' => auth()->id(),
        ]);

        return response()->json([
            'success' => "Evento {$newStatus} com sucesso!",
            'status' => $newStatus
        ]);
    }
}