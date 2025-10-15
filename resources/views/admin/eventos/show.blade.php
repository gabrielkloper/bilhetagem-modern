@extends('admin.layout')

@section('title', 'Visualizar Evento')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $evento->titulo }}</h1>
            <p class="mt-1 text-sm text-gray-500">
                Informações detalhadas do evento
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-3">
            <a href="{{ route('admin.eventos.edit', $evento) }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                <i class="fas fa-edit -ml-1 mr-2 h-4 w-4"></i>
                Editar
            </a>
            <a href="{{ route('admin.eventos.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                <i class="fas fa-arrow-left -ml-1 mr-2 h-4 w-4"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Estatísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-gray-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total de Entradas</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_entradas']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-check text-green-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pessoas Dentro</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['pessoas_dentro']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-dollar-sign text-yellow-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Receita Total</dt>
                            <dd class="text-lg font-medium text-gray-900">R$ {{ number_format($stats['receita_total'], 2, ',', '.') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-chart-pie text-blue-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Ocupação</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['capacidade_utilizada'] }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações do Evento -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informações Básicas -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Básicas</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Título</dt>
                            <dd class="text-sm text-gray-900">{{ $evento->titulo }}</dd>
                        </div>
                        @if($evento->descricao)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Descrição</dt>
                            <dd class="text-sm text-gray-900">{{ $evento->descricao }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="text-sm">
                                @php
                                    $colors = [
                                        'ativo' => 'bg-green-100 text-green-800',
                                        'inativo' => 'bg-gray-100 text-gray-800',
                                        'cancelado' => 'bg-red-100 text-red-800',
                                        'finalizado' => 'bg-blue-100 text-blue-800',
                                    ];
                                    $color = $colors[$evento->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                    {{ $evento->status_label }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Características</dt>
                            <dd class="text-sm flex space-x-2">
                                @if($evento->publico)
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Público</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Privado</span>
                                @endif
                                
                                @if($evento->permite_prevenda)
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">Prevenda</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Datas e Horários -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Datas e Horários</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Período</dt>
                            <dd class="text-sm text-gray-900">
                                @if($evento->data_inicio->format('Y-m-d') === $evento->data_fim->format('Y-m-d'))
                                    {{ $evento->data_inicio->format('d/m/Y') }}
                                @else
                                    {{ $evento->data_inicio->format('d/m/Y') }} a {{ $evento->data_fim->format('d/m/Y') }}
                                @endif
                            </dd>
                        </div>
                        @if($evento->hora_inicio || $evento->hora_fim)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Horário</dt>
                            <dd class="text-sm text-gray-900">
                                @if($evento->hora_inicio && $evento->hora_fim)
                                    {{ $evento->hora_inicio }} às {{ $evento->hora_fim }}
                                @elseif($evento->hora_inicio)
                                    A partir de {{ $evento->hora_inicio }}
                                @elseif($evento->hora_fim)
                                    Até {{ $evento->hora_fim }}
                                @endif
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Capacidade e Preços -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Capacidade e Valores</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Capacidade Máxima</dt>
                            <dd class="text-sm text-gray-900">{{ number_format($evento->capacidade) }} pessoas</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Ocupação Atual</dt>
                            <dd class="text-sm">
                                @php
                                    $percentual = $stats['capacidade_utilizada'];
                                    $colorClass = $percentual >= 90 ? 'text-red-600' : ($percentual >= 70 ? 'text-yellow-600' : 'text-green-600');
                                @endphp
                                <span class="{{ $colorClass }} font-medium">
                                    {{ $stats['pessoas_dentro'] }}/{{ $evento->capacidade }} ({{ $percentual }}%)
                                </span>
                            </dd>
                        </div>
                        @if($evento->preco_padrao > 0)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Preço Padrão</dt>
                            <dd class="text-sm text-gray-900">R$ {{ number_format($evento->preco_padrao, 2, ',', '.') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Restrições e Localização -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Restrições e Localização</h3>
                    <dl class="space-y-3">
                        @if($evento->idade_minima || $evento->idade_maxima)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Faixa Etária</dt>
                            <dd class="text-sm text-gray-900">
                                @if($evento->idade_minima && $evento->idade_maxima)
                                    {{ $evento->idade_minima }} a {{ $evento->idade_maxima }} anos
                                @elseif($evento->idade_minima)
                                    A partir de {{ $evento->idade_minima }} anos
                                @elseif($evento->idade_maxima)
                                    Até {{ $evento->idade_maxima }} anos
                                @endif
                            </dd>
                        </div>
                        @endif
                        @if($evento->endereco)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Endereço</dt>
                            <dd class="text-sm text-gray-900">{{ $evento->endereco }}</dd>
                        </div>
                        @endif
                        @if($evento->observacoes)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Observações</dt>
                            <dd class="text-sm text-gray-900">{{ $evento->observacoes }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações do Sistema -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Sistema</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                <div>
                    <strong>Criado em:</strong><br>
                    {{ $evento->created_at->format('d/m/Y H:i') }}
                </div>
                <div>
                    <strong>Última atualização:</strong><br>
                    {{ $evento->updated_at->format('d/m/Y H:i') }}
                </div>
                <div>
                    <strong>Criado por:</strong><br>
                    {{ $evento->userCriacao->name ?? 'Sistema' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Seção de Ações Rápidas (futuro) -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Ações Rápidas</h3>
            <div class="text-sm text-gray-500 text-center py-8">
                <i class="fas fa-cogs text-gray-300 text-3xl mb-2"></i>
                <p>Ações rápidas para gestão do evento serão implementadas em breve</p>
                <p class="mt-1">Controle de entradas, relatórios em tempo real, etc.</p>
            </div>
        </div>
    </div>
</div>
@endsection