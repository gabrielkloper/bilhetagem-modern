<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Responsavel;
use App\Models\Vinculado;
use App\Models\Entrada;
use App\Models\CaixaMovimento;
use App\Models\User;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $hoje = Carbon::today();
        $user = auth()->user();
        
        // Estatísticas reais
        $stats = [
            'entradas_hoje' => Entrada::whereDate('datahora_entrada', $hoje)->count(),
            'pessoas_ativas' => Entrada::where('status', 'ativo')
                ->whereNull('datahora_saida')
                ->count(),
            'receita_dia' => CaixaMovimento::where('data_caixa', $hoje)
                ->where('tipo_item', 'entrada')
                ->where('ativo', true)
                ->sum('valor'),
            'eventos_ativos' => Evento::where('status', 'ativo')->count()
        ];
        
        // Atividades recentes
        $recentActivity = collect([
            // Entradas recentes
            ...Entrada::with(['vinculado.responsavel', 'pacote'])
                ->orderBy('datahora_entrada', 'desc')
                ->take(3)
                ->get()
                ->map(function($entrada) {
                    return [
                        'type' => 'entrada',
                        'description' => "Nova entrada: {$entrada->vinculado->nome}",
                        'time' => $entrada->datahora_entrada->diffForHumans(),
                        'created_at' => $entrada->datahora_entrada
                    ];
                }),
            // Movimentações de caixa recentes
            ...CaixaMovimento::with('user')
                ->orderBy('datahora_insercao', 'desc')
                ->take(3)
                ->get()
                ->map(function($movimento) {
                    return [
                        'type' => $movimento->tipo_item,
                        'description' => "{$movimento->descricao} - R$ " . number_format($movimento->valor, 2, ',', '.'),
                        'time' => $movimento->datahora_insercao->diffForHumans(),
                        'created_at' => $movimento->datahora_insercao
                    ];
                })
        ])->sortByDesc('created_at')->take(5)->values();
        
        return view('admin.dashboard', compact('stats', 'recentActivity'));
    }

    public function stats()
    {
        $hoje = Carbon::today();
        $ontem = Carbon::yesterday();
        
        $entradasHoje = Entrada::whereDate('datahora_entrada', $hoje)->count();
        $entradasOntem = Entrada::whereDate('datahora_entrada', $ontem)->count();
        $crescimentoEntradas = $entradasOntem > 0 ? round((($entradasHoje - $entradasOntem) / $entradasOntem) * 100, 1) : 0;
        
        $receitaHoje = CaixaMovimento::where('data_caixa', $hoje)
            ->where('tipo_item', 'entrada')
            ->where('ativo', true)
            ->sum('valor');
        $receitaOntem = CaixaMovimento::where('data_caixa', $ontem)
            ->where('tipo_item', 'entrada')
            ->where('ativo', true)
            ->sum('valor');
        $crescimentoReceita = $receitaOntem > 0 ? round((($receitaHoje - $receitaOntem) / $receitaOntem) * 100, 1) : 0;
        
        return response()->json([
            'entradas_hoje' => $entradasHoje,
            'pessoas_ativas' => Entrada::where('status', 'ativo')
                ->whereNull('datahora_saida')
                ->count(),
            'receita_dia' => number_format($receitaHoje, 2, ',', '.'),
            'eventos_ativos' => Evento::where('status', 'ativo')->count(),
            'crescimento_entradas' => $crescimentoEntradas,
            'crescimento_receita' => $crescimentoReceita,
            'eventos_programados' => Evento::where('status', 'inativo')->count(),
            'usuarios_ativos' => User::where('status', 'ativo')->count()
        ]);
    }

    public function activity()
    {
        return response()->json([
            [
                'id' => 1,
                'type' => 'entrada',
                'description' => 'Nova entrada registrada para João Silva',
                'time' => '2 min atrás'
            ],
            [
                'id' => 2,
                'type' => 'pagamento',
                'description' => 'Pagamento processado - R$ 45,00',
                'time' => '5 min atrás'
            ],
            [
                'id' => 3,
                'type' => 'saida',
                'description' => 'Saída registrada para Maria Santos',
                'time' => '8 min atrás'
            ],
            [
                'id' => 4,
                'type' => 'responsavel',
                'description' => 'Novo responsável cadastrado',
                'time' => '15 min atrás'
            ]
        ]);
    }

    public function eventos()
    {
        $eventos = Evento::orderBy('created_at', 'desc')->take(10)->get();
        return response()->json($eventos);
    }

    public function responsaveis()
    {
        $responsaveis = Responsavel::where('ativo', true)->orderBy('created_at', 'desc')->take(10)->get();
        return response()->json($responsaveis);
    }
    
    // Novos métodos para APIs
    public function apiStats()
    {
        return $this->stats();
    }
    
    public function apiRealtime()
    {
        return response()->json([
            'pessoas_dentro' => Entrada::where('status', 'ativo')
                ->whereNull('datahora_saida')
                ->count(),
            'capacidade_total' => Evento::where('status', 'ativo')
                ->sum('capacidade'),
            'ultima_atualizacao' => now()->format('H:i:s')
        ]);
    }
    
    public function apiFinancial()
    {
        $hoje = Carbon::today();
        
        return response()->json([
            'receita_total' => CaixaMovimento::where('data_caixa', $hoje)
                ->where('tipo_item', 'entrada')
                ->where('ativo', true)
                ->sum('valor'),
            'despesas_total' => CaixaMovimento::where('data_caixa', $hoje)
                ->where('tipo_item', 'saida')
                ->where('ativo', true)
                ->sum('valor'),
            'saldo_dia' => CaixaMovimento::where('data_caixa', $hoje)
                ->where('ativo', true)
                ->selectRaw('SUM(CASE WHEN tipo_item = "entrada" THEN valor ELSE -valor END) as saldo')
                ->value('saldo') ?? 0
        ]);
    }
    
    public function apiAlerts()
    {
        $alerts = [];
        
        // Verificar capacidade dos eventos
        $eventosLotacao = Evento::where('status', 'ativo')
            ->get()
            ->filter(function($evento) {
                $pessoasDentro = Entrada::whereHas('vinculado.responsavel.prevendas', function($query) use ($evento) {
                    $query->where('evento_id', $evento->id);
                })
                ->where('status', 'ativo')
                ->whereNull('datahora_saida')
                ->count();
                
                return $pessoasDentro > ($evento->capacidade * 0.9); // 90% da capacidade
            });
            
        foreach($eventosLotacao as $evento) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "Evento {$evento->titulo} próximo da capacidade máxima"
            ];
        }
        
        return response()->json($alerts);
    }
    
    public function apiUsers()
    {
        $users = User::select('id', 'name', 'email', 'role', 'status', 'created_at')
            ->with('evento:id,titulo')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($users);
    }
}
