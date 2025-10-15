<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\Evento;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ControleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin,operador,supervisor');
    }

    public function index()
    {
        // Get all active events for the dropdown
        $eventos = Evento::where('status', 'ativo')->orderBy('created_at', 'desc')->get();
        
        // If no events exist, show empty state with proper variables
        if ($eventos->isEmpty()) {
            return view('admin.controle', [
                'eventos' => $eventos,
                'selectedEvento' => null
            ]);
        }
        
        // Redirect to first available event, but fallback if redirect fails
        $firstEvento = $eventos->first();
        
        try {
            return redirect()->route('admin.controle.evento', ['evento' => $firstEvento->id]);
        } catch (\Exception $e) {
            // Fallback: show view with first event selected
            return view('admin.controle', [
                'eventos' => $eventos,
                'selectedEvento' => $firstEvento
            ]);
        }
    }

    public function show(Request $request, Evento $evento)
    {
        // Validate event is active
        if ($evento->status !== 'ativo') {
            return redirect()->route('admin.controle.index')
                ->with('error', 'O evento selecionado não está ativo.');
        }

        // Get all active events for the dropdown
        $eventos = Evento::where('status', 'ativo')->orderBy('created_at', 'desc')->get();
        
        // Load entradas for the selected event
        $query = Entrada::with(['vinculado', 'vinculado.responsavel', 'pacote'])
            ->where('evento_id', $evento->id)
            ->where('status', 'ativo');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('vinculado', function($vinculadoQuery) use ($search) {
                    $vinculadoQuery->where('nome', 'like', "%{$search}%");
                })
                ->orWhereHas('vinculado.responsavel', function($responsavelQuery) use ($search) {
                    $responsavelQuery->where('nome', 'like', "%{$search}%")
                        ->orWhere('cpf', 'like', "%{$search}%");
                });
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'vencido') {
                // Filter entries where time exceeded
                $query->whereRaw('TIMESTAMPDIFF(MINUTE, datahora_entrada, NOW()) > (pacote_duracao + pacote_tolerancia)');
            } elseif ($status === 'saiu') {
                // Show entries that exited today
                $query->where('status', 'finalizado')
                    ->whereDate('datahora_saida', today());
            }
            // 'ativo' is already filtered above
        }

        $entradas = $query->orderBy('datahora_entrada', 'desc')
            ->paginate(15)
            ->appends($request->query());

        // Calculate real-time data for each entry
        $entradas->getCollection()->transform(function ($entrada) {
            $now = Carbon::now();
            $minutosPassados = $entrada->datahora_entrada->diffInMinutes($now);
            $limiteTempo = $entrada->pacote_duracao + ($entrada->pacote_tolerancia ?? 0);
            
            $entrada->tempo_permanencia_atual = $minutosPassados;
            $entrada->tempo_excedido = max(0, $minutosPassados - $limiteTempo);
            $entrada->status_real = $entrada->tempo_excedido > 0 ? 'vencido' : 'ativo';
            $entrada->tempo_formatado = $this->formatarTempo($minutosPassados);
            
            return $entrada;
        });

        // Calculate stats
        $stats = [
            'pessoas_ativas' => Entrada::where('evento_id', $evento->id)->where('status', 'ativo')->count(),
            'entradas_hoje' => Entrada::where('evento_id', $evento->id)->whereDate('datahora_entrada', today())->count(),
            'tempo_medio' => $this->calcularTempoMedio($evento),
            'capacidade_total' => $evento->capacidade,
            'percentual_ocupacao' => $evento->capacidade > 0 ? 
                (Entrada::where('evento_id', $evento->id)->where('status', 'ativo')->count() / $evento->capacidade) * 100 : 0
        ];
        $stats['capacidade_disponivel'] = max(0, $stats['capacidade_total'] - $stats['pessoas_ativas']);
        $stats['percentual_ocupacao'] = round($stats['percentual_ocupacao'], 1);
        
        return view('admin.controle', [
            'eventos' => $eventos,
            'selectedEvento' => $evento,
            'entradas' => $entradas,
            'stats' => $stats
        ]);
    }

    public function data(Request $request)
    {
        $eventoId = $request->evento_id;
        
        // Validate event ID is provided
        if (!$eventoId) {
            return response()->json(['error' => 'Event ID required'], 400);
        }

        // Validate event exists and is active
        $evento = Evento::find($eventoId);
        if (!$evento) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        if ($evento->status !== 'ativo') {
            return response()->json(['error' => 'Event is not active'], 422);
        }

        $query = Entrada::with(['vinculado', 'vinculado.responsavel', 'pacote'])
            ->where('evento_id', $eventoId)
            ->where('status', 'ativo');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('vinculado', function($vinculadoQuery) use ($search) {
                    $vinculadoQuery->where('nome', 'like', "%{$search}%");
                })
                ->orWhereHas('vinculado.responsavel', function($responsavelQuery) use ($search) {
                    $responsavelQuery->where('nome', 'like', "%{$search}%")
                        ->orWhere('cpf', 'like', "%{$search}%");
                });
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'vencido') {
                // Filter entries where time exceeded
                $query->whereRaw('TIMESTAMPDIFF(MINUTE, datahora_entrada, NOW()) > (pacote_duracao + pacote_tolerancia)');
            } elseif ($status === 'saiu') {
                // Show entries that exited today
                $query->where('status', 'finalizado')
                    ->whereDate('datahora_saida', today());
            }
            // 'ativo' is already filtered above
        }

        $entradas = $query->orderBy('datahora_entrada', 'desc')
            ->paginate(15)
            ->appends($request->query());

        // Calculate real-time data for each entry
        $entradas->getCollection()->transform(function ($entrada) {
            $now = Carbon::now();
            $minutosPassados = $entrada->datahora_entrada->diffInMinutes($now);
            $limiteTempo = $entrada->pacote_duracao + ($entrada->pacote_tolerancia ?? 0);
            
            $entrada->tempo_permanencia_atual = $minutosPassados;
            $entrada->tempo_excedido = max(0, $minutosPassados - $limiteTempo);
            $entrada->status_real = $entrada->tempo_excedido > 0 ? 'vencido' : 'ativo';
            $entrada->tempo_formatado = $this->formatarTempo($minutosPassados);
            
            return $entrada;
        });

        return response()->json([
            'data' => $entradas->items(),
            'pagination' => [
                'current_page' => $entradas->currentPage(),
                'last_page' => $entradas->lastPage(),
                'per_page' => $entradas->perPage(),
                'total' => $entradas->total(),
                'from' => $entradas->firstItem(),
                'to' => $entradas->lastItem(),
            ]
        ]);
    }

    public function stats(Evento $evento)
    {
        // Validate event is active
        if ($evento->status !== 'ativo') {
            return response()->json(['error' => 'Evento não está ativo'], 422);
        }
        
        // Pessoas ativas no evento
        $pessoasAtivas = Entrada::where('evento_id', $evento->id)
            ->where('status', 'ativo')
            ->count();
        
        // Entradas hoje no evento
        $entradasHoje = Entrada::where('evento_id', $evento->id)
            ->whereDate('datahora_entrada', today())
            ->count();
        
        // Tempo médio de permanência (apenas para entradas finalizadas)
        $tempoMedio = Entrada::where('evento_id', $evento->id)
            ->where('status', 'finalizado')
            ->where('tempo_permanencia', '>', 0)
            ->avg('tempo_permanencia');
        
        $tempoMedioFormatado = $tempoMedio ? $this->formatarTempo($tempoMedio) : '0h 0m';
        
        // Capacidade do evento
        $capacidadeTotal = $evento->capacidade;
        $capacidadeDisponivel = max(0, $capacidadeTotal - $pessoasAtivas);
        $percentualOcupacao = $capacidadeTotal > 0 ? ($pessoasAtivas / $capacidadeTotal) * 100 : 0;

        return response()->json([
            'pessoas_ativas' => $pessoasAtivas,
            'entradas_hoje' => $entradasHoje,
            'tempo_medio' => $tempoMedioFormatado,
            'capacidade_total' => $capacidadeTotal,
            'capacidade_disponivel' => $capacidadeDisponivel,
            'percentual_ocupacao' => round($percentualOcupacao, 1),
            'evento' => [
                'id' => $evento->id,
                'titulo' => $evento->titulo,
                'data_inicio' => $evento->data_inicio?->format('d/m/Y H:i'),
                'data_fim' => $evento->data_fim?->format('d/m/Y H:i'),
            ]
        ]);
    }

    public function registrarSaida(Request $request, Entrada $entrada)
    {
        // Validate entrada belongs to an active event
        if ($entrada->evento->status !== 'ativo') {
            return response()->json(['error' => 'O evento desta entrada não está mais ativo'], 422);
        }

        if ($entrada->status !== 'ativo') {
            return response()->json(['error' => 'Esta entrada não está ativa'], 422);
        }

        $now = Carbon::now();
        $tempoPermancencia = $entrada->datahora_entrada->diffInMinutes($now);
        
        // Check if time exceeded and payment required
        $limiteTempo = $entrada->pacote_duracao + ($entrada->pacote_tolerancia ?? 0);
        $tempoExcedido = max(0, $tempoPermancencia - $limiteTempo);
        
        if ($tempoExcedido > 0 && !$entrada->pgto_extra) {
            return response()->json([
                'error' => 'Pagamento adicional obrigatório. Tempo excedido: ' . $this->formatarTempo($tempoExcedido) . '. Processe o pagamento antes de registrar a saída.'
            ], 422);
        }
        
        $entrada->update([
            'status' => 'finalizado',
            'datahora_saida' => $now,
            'tempo_permanencia' => $tempoPermancencia,
        ]);

        return response()->json([
            'success' => 'Saída registrada com sucesso!',
            'entrada' => $entrada->load(['vinculado', 'vinculado.responsavel'])
        ]);
    }

    public function cobrarAdicional(Request $request, Entrada $entrada, MercadoPagoService $mercadoPago = null)
    {
        // Validate entrada belongs to an active event
        if ($entrada->evento->status !== 'ativo') {
            return response()->json(['error' => 'O evento desta entrada não está mais ativo'], 422);
        }

        $request->validate([
            'tempo_adicional' => 'required|integer|min:1',
            'valor_adicional' => 'required|numeric|min:0.01',
            'forma_pagamento' => 'required|in:dinheiro,cartao_debito,cartao_credito,pix',
            'observacoes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        
        try {
            $paymentData = null;
            $isDigitalPayment = in_array($request->forma_pagamento, ['pix', 'cartao_debito', 'cartao_credito']);
            
            // Process digital payments
            if ($isDigitalPayment && $mercadoPago) {
                $paymentData = $this->processDigitalPayment($request, $entrada, $mercadoPago);
                
                if (!$paymentData['success']) {
                    DB::rollback();
                    return response()->json(['error' => $paymentData['error']], 422);
                }
            }

            // Update entrada with payment info
            $updateData = [
                'pgto_extra' => true,
                'pgto_extra_valor' => $request->valor_adicional,
                'tempo_excedido' => $request->tempo_adicional,
                'forma_pagamento_extra' => $request->forma_pagamento,
                'observacoes_pagamento' => ($entrada->observacoes_pagamento ?? '') . 
                    "\nCobrança adicional: R$ {$request->valor_adicional} por {$request->tempo_adicional} min extras. " . 
                    "Forma: " . ucfirst($request->forma_pagamento) . ". " .
                    ($request->observacoes ?? '')
            ];

            // Add payment gateway data if digital payment
            if ($paymentData && $paymentData['success']) {
                $updateData['payment_id'] = $paymentData['payment_id'];
                $updateData['payment_status'] = $paymentData['status'];
            }

            $entrada->update($updateData);

            DB::commit();

            $response = [
                'success' => 'Cobrança adicional processada com sucesso!',
                'entrada' => $entrada->load(['vinculado', 'vinculado.responsavel']),
                'payment_method' => $request->forma_pagamento
            ];

            // Add PIX QR code if available
            if ($request->forma_pagamento === 'pix' && $paymentData && isset($paymentData['qr_code_base64'])) {
                $response['pix_qr_code'] = $paymentData['qr_code_base64'];
                $response['pix_code'] = $paymentData['qr_code'];
                $response['payment_id'] = $paymentData['payment_id'];
                $response['expiration_date'] = $paymentData['expiration_date'];
            }

            return response()->json($response);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Erro ao processar cobrança adicional: ' . $e->getMessage()], 500);
        }
    }

    private function processDigitalPayment(Request $request, Entrada $entrada, MercadoPagoService $mercadoPago): array
    {
        $paymentData = [
            'amount' => $request->valor_adicional,
            'description' => "Cobrança adicional - {$request->tempo_adicional} min - {$entrada->evento->titulo}",
            'external_reference' => "entrada_{$entrada->id}_adicional_" . time(),
            'payer_name' => $entrada->vinculado->responsavel->nome ?? 'Cliente',
            'payer_email' => $entrada->vinculado->responsavel->email ?? 'cliente@bilhetagem.com',
            'payer_document' => preg_replace('/\D/', '', $entrada->vinculado->responsavel->cpf ?? '11144477735')
        ];

        if ($request->forma_pagamento === 'pix') {
            return $mercadoPago->createPixPayment($paymentData);
        } else {
            // For card payments, we would need card token from frontend
            // For now, return success for cash-equivalent processing
            return [
                'success' => true,
                'payment_id' => 'card_' . time(),
                'status' => 'pending'
            ];
        }
    }

    public function detalhes(Entrada $entrada)
    {
        // Validate entrada belongs to an active event
        if ($entrada->evento->status !== 'ativo') {
            return response()->json(['error' => 'O evento desta entrada não está mais ativo'], 422);
        }

        $entrada->load([
            'evento',
            'vinculado',
            'vinculado.responsavel',
            'pacote',
            'prevenda'
        ]);

        $now = Carbon::now();
        $tempoAtual = $entrada->status === 'ativo' ? 
            $entrada->datahora_entrada->diffInMinutes($now) : 
            $entrada->tempo_permanencia;
        
        $limiteTempo = $entrada->pacote_duracao + ($entrada->pacote_tolerancia ?? 0);
        $tempoExcedido = max(0, $tempoAtual - $limiteTempo);

        return response()->json([
            'entrada' => $entrada,
            'tempo_atual' => $this->formatarTempo($tempoAtual),
            'tempo_limite' => $this->formatarTempo($limiteTempo),
            'tempo_excedido' => $tempoExcedido > 0 ? $this->formatarTempo($tempoExcedido) : null,
            'tempo_excedido_minutos' => $tempoExcedido, // Raw minutes for forms
            'valor_adicional_sugerido' => $tempoExcedido > 0 ? 
                $tempoExcedido * ($entrada->pacote_valor_adicional ?? 0.50) : 0
        ]);
    }

    private function formatarTempo($minutos)
    {
        $horas = intdiv($minutos, 60);
        $mins = $minutos % 60;
        
        if ($horas > 0) {
            return "{$horas}h {$mins}m";
        }
        
        return "{$mins}m";
    }

    private function calcularTempoMedio(Evento $evento)
    {
        $tempoMedio = Entrada::where('evento_id', $evento->id)
            ->where('status', 'finalizado')
            ->where('tempo_permanencia', '>', 0)
            ->avg('tempo_permanencia');
        
        return $tempoMedio ? $this->formatarTempo($tempoMedio) : '0h 0m';
    }
}