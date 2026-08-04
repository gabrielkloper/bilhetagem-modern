<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\Evento;
use App\Models\Pacote;
use App\Models\PerfilAcesso;
use App\Models\Prevenda;
use App\Models\Responsavel;
use App\Models\Vinculado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EntradaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin,operador,supervisor');
    }

    public function index(Request $request)
    {
        $eventos = Evento::where('status', 'ativo')->get();
        $selectedEvento = null;

        if ($request->has('evento_id') && $request->evento_id) {
            $selectedEvento = Evento::find($request->evento_id);
        }

        return view('admin.entradas.index', compact('eventos', 'selectedEvento'));
    }

    public function create()
    {
        $eventos = Evento::where('status', 'ativo')->get();

        return view('admin.entradas.create', compact('eventos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'evento_id' => ['required', 'exists:eventos,id'],
            'vinculado_id' => ['required', 'exists:vinculados,id'],
            'pacote_id' => ['required', 'exists:pacotes,id'],
            'perfil_acesso_id' => ['nullable', 'exists:perfis_acesso,id'],
            'prevenda_id' => ['nullable', 'exists:prevendas,id'],
            'payment_method' => ['required', Rule::in(['dinheiro', 'cartao_debito', 'cartao_credito', 'pix', 'gratuito'])],
            'amount_paid' => ['nullable', 'string'],
            'payment_notes' => ['nullable', 'string', 'max:500'],
        ]);

        DB::beginTransaction();

        try {
            $evento = Evento::findOrFail($request->evento_id);
            $vinculado = Vinculado::with('responsavel')->findOrFail($request->vinculado_id);
            $pacote = Pacote::findOrFail($request->pacote_id);

            // Validar se o responsável está inscrito e ativo no evento
            if (! $vinculado->responsavelInscritoNoEvento($request->evento_id)) {
                DB::rollback();

                return response()->json([
                    'error' => 'O responsável não está inscrito neste evento ou a inscrição está inativa. Por favor, realize a inscrição primeiro.',
                ], 422);
            }

            // Process payment information
            $paymentData = $this->processPayment($request, $pacote);

            // Verificar capacidade
            $entradasAtuais = Entrada::where('evento_id', $request->evento_id)
                ->where('status', 'ativo')
                ->count();

            if ($entradasAtuais >= $evento->capacidade) {
                return response()->json([
                    'error' => 'Capacidade máxima do evento atingida',
                ], 422);
            }

            // Verificar se o vinculado já está ativo no evento
            $entradaExistente = Entrada::where('evento_id', $request->evento_id)
                ->where('vinculado_id', $request->vinculado_id)
                ->where('status', 'ativo')
                ->first();

            if ($entradaExistente) {
                return response()->json([
                    'error' => 'Esta criança já está presente no evento',
                ], 422);
            }

            // Determinar perfil de acesso se não fornecido
            $perfilAcessoId = $request->perfil_acesso_id;
            $perfilAcessoEnum = 'crianca'; // Default value

            if ($perfilAcessoId) {
                $perfilAcesso = PerfilAcesso::find($perfilAcessoId);
                // Map perfil tipo to enum value
                $perfilAcessoEnum = match ($perfilAcesso->tipo ?? 'padrao') {
                    'adulto' => 'adulto',
                    'adolescente' => 'adolescente',
                    'idoso' => 'idoso',
                    'pcd' => 'pcd',
                    'funcionario' => 'funcionario',
                    default => 'crianca'
                };
            } else {
                // Auto-determine based on age
                $idade = $vinculado->idade;
                $perfilAcessoEnum = match (true) {
                    $idade >= 60 => 'idoso',
                    $idade >= 18 => 'adulto',
                    $idade >= 13 => 'adolescente',
                    default => 'crianca'
                };
            }

            // Criar entrada
            $entrada = Entrada::create([
                'evento_id' => $request->evento_id,
                'vinculado_id' => $request->vinculado_id,
                'pacote_id' => $request->pacote_id,
                'prevenda_id' => $request->prevenda_id,
                'perfil_acesso' => $perfilAcessoEnum,
                'datahora_entrada' => now(),
                'status' => 'ativo',
                'tempo_excedido' => 0,
                'tempo_permanencia' => 0,
                'pgto_extra' => false,
                'pgto_extra_valor' => 0.00,
                'ativo' => true,
                'pacote_valor' => $pacote->valor,
                'pacote_duracao' => $pacote->duracao,
                'pacote_valor_adicional' => $pacote->valor_minuto_adicional,
                'pacote_tolerancia' => $pacote->tolerancia,
                'pacote_nome' => $pacote->descricao,
                'pacote_qtde_compra' => 1,
                'pacote_minutos_compra' => $pacote->duracao,
                'autorizado' => true,
                'datahora_autorizacao' => now(),
                // Payment information
                'forma_pagamento' => $paymentData['method'],
                'valor_pago' => $paymentData['amount_paid'],
                'valor_troco' => $paymentData['change'],
                'observacoes_pagamento' => $paymentData['notes'],
                'pagamento_confirmado' => $paymentData['confirmed'],
            ]);

            // Create cash movement for initial payment
            if ($entrada->valor_pago > 0 && $entrada->pagamento_confirmado) {
                \App\Models\CaixaMovimento::create([
                    'evento_id' => $entrada->evento_id,
                    'user_id' => auth()->id(),
                    'data_caixa' => now()->toDateString(),
                    'tipo_item' => 'entrada',
                    'descricao' => "Entrada #{$entrada->id} - {$entrada->pacote_nome} - ".
                        ucfirst($entrada->forma_pagamento),
                    'valor' => $entrada->valor_pago,
                    'ativo' => true,
                ]);
            }

            // Se for prevenda, marcar como utilizada
            if ($request->prevenda_id) {
                Prevenda::where('id', $request->prevenda_id)
                    ->update(['status' => 'utilizada']);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => 'Entrada registrada com sucesso!',
                    'entrada' => $entrada->load(['evento', 'vinculado.responsavel', 'pacote', 'prevenda']),
                ]);
            }

            return redirect()->route('admin.entradas.index')
                ->with('success', 'Entrada registrada com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();

            if ($request->ajax()) {
                return response()->json(['error' => 'Erro ao registrar entrada'], 500);
            }

            return back()->withInput()->with('error', 'Erro ao registrar entrada');
        }
    }

    public function show(Entrada $entrada)
    {
        $entrada->load(['evento', 'vinculado.responsavel', 'pacote', 'prevenda']);

        return view('admin.entradas.show', compact('entrada'));
    }

    public function edit(Entrada $entrada)
    {
        $eventos = Evento::where('status', 'ativo')->get();
        $entrada->load(['evento', 'responsavel', 'pacote', 'prevenda']);

        return view('admin.entradas.edit', compact('entrada', 'eventos'));
    }

    public function update(Request $request, Entrada $entrada)
    {
        $request->validate([
            'evento_id' => ['required', 'exists:eventos,id'],
            'responsavel_id' => ['nullable', 'exists:responsaveis,id'],
            'pacote_id' => ['nullable', 'exists:pacotes,id'],
            'prevenda_id' => ['nullable', 'exists:prevendas,id'],
            'tipo_entrada' => ['required', Rule::in(['individual', 'pacote', 'prevenda', 'cortesia'])],
            'valor_pago' => ['required', 'numeric', 'min:0'],
            'forma_pagamento' => ['required', Rule::in(['dinheiro', 'cartao', 'pix', 'transferencia', 'gratuito'])],
            'status' => ['required', Rule::in(['entrada', 'saida', 'presente'])],
            'observacoes' => ['nullable', 'string', 'max:1000'],
        ]);

        $entrada->update([
            'evento_id' => $request->evento_id,
            'responsavel_id' => $request->responsavel_id,
            'pacote_id' => $request->pacote_id,
            'prevenda_id' => $request->prevenda_id,
            'tipo_entrada' => $request->tipo_entrada,
            'valor_pago' => $request->valor_pago,
            'forma_pagamento' => $request->forma_pagamento,
            'status' => $request->status,
            'observacoes' => $request->observacoes,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => 'Entrada atualizada com sucesso!',
                'entrada' => $entrada->load(['evento', 'responsavel', 'pacote', 'prevenda']),
            ]);
        }

        return redirect()->route('admin.entradas.index')
            ->with('success', 'Entrada atualizada com sucesso!');
    }

    public function destroy(Entrada $entrada)
    {
        // Só permite deletar se não houve saída
        if ($entrada->status === 'saida') {
            return response()->json(['error' => 'Não é possível deletar entrada com saída registrada'], 422);
        }

        // Se era prevenda, voltar status
        if ($entrada->prevenda_id) {
            Prevenda::where('id', $entrada->prevenda_id)
                ->update(['status' => 'pendente']);
        }

        $entrada->delete();

        return response()->json(['success' => 'Entrada deletada com sucesso!']);
    }

    // Registrar saída
    public function saida(Request $request, Entrada $entrada)
    {
        if ($entrada->status !== 'ativo') {
            return response()->json(['error' => 'Esta entrada não está ativa'], 422);
        }

        // Calculate time spent
        $now = Carbon::now();
        $tempoPermancencia = $entrada->datahora_entrada->diffInMinutes($now);

        $entrada->update([
            'status' => 'finalizado',
            'datahora_saida' => $now,
            'tempo_permanencia' => $tempoPermancencia,
        ]);

        return response()->json([
            'success' => 'Saída registrada com sucesso!',
            'entrada' => $entrada->load(['vinculado.responsavel', 'evento', 'pacote']),
        ]);
    }

    // Buscar dados com paginação nativa do Laravel (substituindo DataTables)
    public function data(Request $request)
    {
        $query = Entrada::with(['evento:id,titulo', 'vinculado.responsavel:id,nome', 'pacote:id,descricao', 'prevenda:id'])
            ->orderBy('datahora_entrada', 'desc');

        // Filtros
        if ($request->filled('evento_id')) {
            $query->where('evento_id', $request->evento_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('datahora_entrada', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('datahora_entrada', '<=', $request->data_fim);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('evento', function ($eq) use ($request) {
                    $eq->where('titulo', 'like', '%'.$request->search.'%');
                })
                    ->orWhereHas('vinculado.responsavel', function ($rq) use ($request) {
                        $rq->where('nome', 'like', '%'.$request->search.'%')
                            ->orWhere('cpf', 'like', '%'.$request->search.'%');
                    })
                    ->orWhereHas('vinculado', function ($vq) use ($request) {
                        $vq->where('nome', 'like', '%'.$request->search.'%');
                    })
                    ->orWhere('observacoes_pagamento', 'like', '%'.$request->search.'%');
            });
        }

        $entradas = $query->paginate(15)->appends($request->query());

        return response()->json([
            'data' => $entradas->items(),
            'pagination' => [
                'current_page' => $entradas->currentPage(),
                'last_page' => $entradas->lastPage(),
                'per_page' => $entradas->perPage(),
                'total' => $entradas->total(),
                'from' => $entradas->firstItem(),
                'to' => $entradas->lastItem(),
                'prev_page_url' => $entradas->previousPageUrl(),
                'next_page_url' => $entradas->nextPageUrl(),
            ],
        ]);
    }

    // Buscar dados para formulários
    public function getEventData($eventoId)
    {
        try {
            $evento = Evento::with(['pacotes' => function ($query) {
                $query->where('ativo', true);
            }])->findOrFail($eventoId);

            // Get responsaveis through inscricoes relationship
            // Only show responsaveis who are inscribed in the event AND have at least one vinculado without active entry
            $responsaveis = Responsavel::whereHas('inscricoes', function ($query) use ($eventoId) {
                $query->where('evento_id', $eventoId)->where('ativo', true);
            })
                ->whereHas('vinculados', function ($query) use ($eventoId) {
                    // Has at least one vinculado without active entry in this event
                    $query->whereDoesntHave('entradas', function ($subQuery) use ($eventoId) {
                        $subQuery->where('evento_id', $eventoId)->where('status', 'ativo');
                    });
                })
                ->get(['id', 'nome', 'email', 'telefone1']);

            $prevendas = Prevenda::where('evento_id', $eventoId)
                ->where('status', 'pendente')
                ->get(['id', 'valor_pagamento as valor', 'responsavel_id']);

            // Fixed status queries to use 'ativo' instead of old statuses
            $estatisticas = [
                'total_entradas' => Entrada::where('evento_id', $eventoId)->count(),
                'presentes' => Entrada::where('evento_id', $eventoId)->where('status', 'ativo')->count(),
                'saidas' => Entrada::where('evento_id', $eventoId)->where('status', 'finalizado')->count(),
                'capacidade_disponivel' => max(0, $evento->capacidade - Entrada::where('evento_id', $eventoId)->where('status', 'ativo')->count()),
            ];

            // Get perfis_acesso with error handling
            $perfisAcesso = PerfilAcesso::ativos()->porEvento($eventoId)->get();

            // If no perfis exist, create a default one
            if ($perfisAcesso->isEmpty()) {
                $perfilDefault = PerfilAcesso::create([
                    'evento_id' => $eventoId,
                    'titulo' => 'Perfil Padrão',
                    'descricao' => 'Perfil de acesso padrão para todas as idades',
                    'ativo' => true,
                    'padrao_evento' => true,
                    'preco_base' => 0,
                    'tipo' => 'padrao',
                ]);
                $perfisAcesso = collect([$perfilDefault]);
            }

            return response()->json([
                'evento' => $evento,
                'responsaveis' => $responsaveis->map(function ($resp) use ($eventoId) {
                    $inscricao = $resp->inscricoes()->where('evento_id', $eventoId)->first();

                    return [
                        'id' => $resp->id,
                        'nome' => $resp->nome,
                        'email' => $resp->email,
                        'telefone1' => $resp->telefone1,
                        'inscricao_ativa' => $inscricao?->ativo ?? false,
                        'data_inscricao' => $inscricao?->data_inscricao?->format('d/m/Y'),
                    ];
                }),
                'prevendas' => $prevendas,
                'estatisticas' => $estatisticas,
                'perfis_acesso' => $perfisAcesso,
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao carregar dados do evento: '.$e->getMessage());

            return response()->json([
                'error' => 'Erro ao carregar dados do evento',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getVinculados(Request $request)
    {
        try {
            $responsavelId = $request->responsavel_id;
            $eventoId = $request->evento_id;

            if (! $responsavelId) {
                return response()->json(['vinculados' => []]);
            }

            // Load all perfis for the event once to avoid repeated queries
            $perfisAcesso = PerfilAcesso::ativos()->porEvento($eventoId)->get();
            $perfilPadrao = $perfisAcesso->where('padrao_evento', true)->first();

            // Only show vinculados that don't have active entries in this event yet
            $vinculados = Vinculado::where('responsavel_id', $responsavelId)
                ->with(['vinculo'])
                ->whereDoesntHave('entradas', function ($query) use ($eventoId) {
                    $query->where('evento_id', $eventoId)->where('status', 'ativo');
                })
                ->get()
                ->map(function ($vinculado) use ($perfisAcesso, $perfilPadrao) {
                    // Find appropriate profile for this vinculado
                    $perfilSugerido = $this->findPerfilForVinculado($vinculado, $perfisAcesso, $perfilPadrao);

                    return [
                        'id' => $vinculado->id,
                        'nome' => $vinculado->nome,
                        'idade' => $vinculado->idade,
                        'nascimento' => $vinculado->nascimento->format('d/m/Y'),
                        'vinculo' => $vinculado->vinculo ? $vinculado->vinculo->descricao : 'N/A',
                        'perfil_sugerido_id' => $perfilSugerido?->id,
                        'perfil_sugerido' => $perfilSugerido?->titulo,
                    ];
                });

            return response()->json(['vinculados' => $vinculados]);
        } catch (\Exception $e) {
            \Log::error('Erro ao carregar vinculados: '.$e->getMessage());

            return response()->json([
                'error' => 'Erro ao carregar vinculados',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function findPerfilForVinculado($vinculado, $perfisAcesso, $perfilPadrao)
    {
        $idade = $vinculado->idade;

        // Find the best matching profile
        foreach ($perfisAcesso as $perfil) {
            if ($perfil->tipo === 'idade' && $idade !== null) {
                $minimaOk = $perfil->idade_minima === null || $idade >= $perfil->idade_minima;
                $maximaOk = $perfil->idade_maxima === null || $idade <= $perfil->idade_maxima;
                if ($minimaOk && $maximaOk) {
                    return $perfil;
                }
            }
        }

        // Return default profile if no specific match
        return $perfilPadrao;
    }

    private function processPayment(Request $request, $pacote)
    {
        $paymentMethod = $request->payment_method;
        $packagePrice = floatval($pacote->valor);
        $paymentNotes = $request->payment_notes;

        $paymentData = [
            'method' => $paymentMethod,
            'amount_paid' => $packagePrice,
            'change' => 0.00,
            'notes' => $paymentNotes,
            'confirmed' => true,
        ];

        if ($paymentMethod === 'dinheiro') {
            // Parse amount paid from formatted input (R$ 10,50)
            $amountPaidStr = $request->amount_paid;
            if ($amountPaidStr) {
                $amountPaid = floatval(str_replace(['R$ ', '.', ','], ['', '', '.'], $amountPaidStr));

                if ($amountPaid < $packagePrice) {
                    throw new \Exception('Valor recebido insuficiente para o pacote selecionado.');
                }

                $paymentData['amount_paid'] = $amountPaid;
                $paymentData['change'] = $amountPaid - $packagePrice;
            } else {
                throw new \Exception('Valor recebido é obrigatório para pagamentos em dinheiro.');
            }
        } elseif ($paymentMethod === 'gratuito') {
            $paymentData['amount_paid'] = 0.00;
            $paymentData['change'] = 0.00;
        }

        return $paymentData;
    }

    public function getPerfisAcesso($eventoId)
    {
        $perfis = PerfilAcesso::ativos()->porEvento($eventoId)->get();

        return response()->json(['perfis' => $perfis]);
    }

    public function checkDuplicate(Request $request)
    {
        try {
            $eventoId = $request->evento_id;
            $vinculadoId = $request->vinculado_id;

            if (! $eventoId || ! $vinculadoId) {
                return response()->json(['exists' => false]);
            }

            $existingEntry = Entrada::where('evento_id', $eventoId)
                ->where('vinculado_id', $vinculadoId)
                ->where('status', 'ativo')
                ->with(['pacote'])
                ->first();

            if ($existingEntry) {
                return response()->json([
                    'exists' => true,
                    'entry_time' => $existingEntry->datahora_entrada->format('d/m/Y H:i'),
                    'package_name' => $existingEntry->pacote_nome ?? $existingEntry->pacote->descricao ?? 'N/A',
                ]);
            }

            return response()->json(['exists' => false]);
        } catch (\Exception $e) {
            \Log::error('Erro ao verificar entrada duplicada: '.$e->getMessage());

            return response()->json(['exists' => false]);
        }
    }
}
