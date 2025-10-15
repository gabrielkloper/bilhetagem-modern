@extends('admin.layout')

@section('title', 'Controle de Recreação')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Controle de Recreação</h1>
            <p class="mt-1 text-sm text-gray-500">
                Gerencie entradas e saídas ativas do evento selecionado
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:flex-none">
            <button id="refresh-btn" type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Atualizar Lista
            </button>
        </div>
    </div>

    <!-- Event Selection -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div class="sm:flex sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <div class="min-w-0">
                        <label for="evento-select" class="block text-sm font-medium text-gray-700 mb-2">
                            Evento Ativo
                        </label>
                        <select id="evento-select" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Selecionar Evento...</option>
                            @if(isset($eventos) && $eventos->isNotEmpty())
                                @foreach($eventos as $evento)
                                    <option value="{{ $evento->id }}" 
                                        {{ isset($selectedEvento) && $selectedEvento && $selectedEvento->id == $evento->id ? 'selected' : '' }}>
                                        {{ $evento->titulo }} - {{ $evento->data_inicio?->format('d/m/Y H:i') }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    @if(isset($selectedEvento) && $selectedEvento)
                    <div class="min-w-0">
                        <div class="text-sm text-gray-600">
                            <strong>Capacidade:</strong> 
                            <span id="capacity-info">{{ $selectedEvento->capacidade ?? 0 }} pessoas</span>
                        </div>
                        <div class="text-sm text-gray-500 mt-1">
                            Data: {{ $selectedEvento->data_inicio?->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(isset($selectedEvento) && $selectedEvento)
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Pessoas Ativas -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pessoas Ativas</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ isset($stats) ? $stats['pessoas_ativas'] : '-' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Entradas Hoje -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Entradas Hoje</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ isset($stats) ? $stats['entradas_hoje'] : '-' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tempo Médio -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Tempo Médio</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ isset($stats) ? $stats['tempo_medio'] : '-' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ocupação -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Ocupação</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ isset($stats) ? $stats['percentual_ocupacao'] : '0' }}%</dd>
                            <dd class="text-sm text-gray-500">{{ isset($stats) ? $stats['capacidade_disponivel'] : '0' }}/{{ isset($stats) ? $stats['capacidade_total'] : '0' }} disponíveis</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" action="{{ route('admin.controle.evento', ['evento' => $selectedEvento->id]) }}" class="sm:flex sm:items-center sm:justify-between">
                <div class="sm:flex sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <!-- Search -->
                    <div class="flex-1 min-w-0">
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Buscar por nome, CPF...">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="min-w-0">
                        <select name="status" id="status-filter" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Todos os status</option>
                            <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo (dentro do parque)</option>
                            <option value="vencido" {{ request('status') == 'vencido' ? 'selected' : '' }}>Tempo vencido</option>
                            <option value="saiu" {{ request('status') == 'saiu' ? 'selected' : '' }}>Saiu hoje</option>
                        </select>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex space-x-2">
                        <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Filtrar
                        </button>
                        <button type="button" id="clear-search" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Limpar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Active Entries Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Entradas Ativas
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Lista de pessoas atualmente dentro do parque
            </p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Responsável / Participante
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Entrada
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tempo Permanência
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pacote
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(!isset($selectedEvento) || !$selectedEvento)
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            @if(!isset($eventos) || $eventos->isEmpty())
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 10V11m0 4V11m0-4V3" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum evento ativo</h3>
                                    <p class="mt-1 text-sm text-gray-500">Não há eventos ativos para exibir o controle de recreação.</p>
                                </div>
                            @else
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 10V11m0 4V11m0-4V3" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Selecione um evento</h3>
                                    <p class="mt-1 text-sm text-gray-500">Escolha um evento ativo no dropdown acima para visualizar as entradas.</p>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @elseif(isset($entradas) && $entradas->count() > 0)
                        @foreach($entradas as $entrada)
                        @php
                            $isExpired = $entrada->status_real === 'vencido';
                            $rowClass = $isExpired ? 'hover:bg-gray-50 bg-red-50' : 'hover:bg-gray-50';
                            $timeClass = $isExpired ? 'text-red-600 font-semibold' : 'text-gray-900';
                            $timeDetail = $isExpired ? "excedeu {$entrada->tempo_formatado}" : "de {$entrada->pacote_duracao}min permitidos";
                            $initials = $entrada->vinculado && $entrada->vinculado->nome 
                                ? strtoupper(substr($entrada->vinculado->nome, 0, 1) . (strpos($entrada->vinculado->nome, ' ') !== false ? substr($entrada->vinculado->nome, strpos($entrada->vinculado->nome, ' ') + 1, 1) : ''))
                                : 'NN';
                            $avatarColor = $isExpired ? 'bg-red-500' : 'bg-indigo-500';
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full {{ $avatarColor }} flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">{{ $initials }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $entrada->vinculado->responsavel->nome ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">CPF: {{ $entrada->vinculado->responsavel->cpf ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-400">+ {{ $entrada->vinculado->nome ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $entrada->datahora_entrada->format('H:i') }}</div>
                                <div class="text-sm text-gray-500">{{ $entrada->datahora_entrada->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm {{ $timeClass }}">{{ $entrada->tempo_formatado }}</div>
                                <div class="text-xs text-gray-500">{{ $timeDetail }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $entrada->pacote_nome ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($entrada->status_real === 'vencido')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Vencido</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Ativo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button onclick="controle.showDetails({{ $entrada->id }})" class="text-indigo-600 hover:text-indigo-900" title="Ver detalhes">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    
                                    @if($isExpired && !$entrada->pgto_extra)
                                        {{-- Time expired and no additional payment made - show payment required --}}
                                        <div class="flex flex-col items-center">
                                            <button disabled class="text-gray-400 cursor-not-allowed" title="Pagamento adicional obrigatório antes da saída">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 0h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2zM13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </button>
                                            <span class="text-xs text-red-600 mt-1 whitespace-nowrap">Pagar primeiro</span>
                                        </div>
                                    @else
                                        {{-- Can exit: either time not expired OR additional payment made --}}
                                        <button onclick="controle.registerExit({{ $entrada->id }})" class="text-red-600 hover:text-red-900" title="Registrar saída">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 713 3v1" />
                                            </svg>
                                        </button>
                                    @endif
                                    
                                    @if($isExpired)
                                    <button onclick="controle.chargeAdditional({{ $entrada->id }})" class="text-yellow-600 hover:text-yellow-900" title="Cobrar adicional">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma entrada ativa</h3>
                                    <p class="mt-1 text-sm text-gray-500">Não há pessoas dentro do parque no momento.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($entradas) && $entradas->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $entradas->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Payment Modal -->
<div id="payment-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center" style="z-index: 1000;">
    <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="bg-white rounded-lg">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Cobrar Adicional</h3>
                    <button onclick="controle.closePaymentModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <form id="payment-form" class="px-6 py-4 space-y-4">
                <input type="hidden" id="entrada-id" name="entrada_id" value="">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tempo Adicional (minutos)</label>
                    <input type="number" id="tempo-adicional" name="tempo_adicional" min="1" required
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor Adicional (R$)</label>
                    <input type="number" id="valor-adicional" name="valor_adicional" step="0.01" min="0.01" required
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Forma de Pagamento</label>
                    <select id="forma-pagamento" name="forma_pagamento" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Selecione...</option>
                        <option value="pix">PIX (Instantâneo)</option>
                        <option value="dinheiro">Dinheiro</option>
                        <option value="cartao_debito">Cartão de Débito</option>
                        <option value="cartao_credito">Cartão de Crédito</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                    <textarea id="observacoes" name="observacoes" rows="3" 
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Observações sobre o pagamento (opcional)"></textarea>
                </div>
            </form>
            
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 rounded-b-lg flex justify-end space-x-3">
                <button onclick="controle.closePaymentModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancelar
                </button>
                <button onclick="controle.processPayment()" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Processar Pagamento
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Store routes safely in a global variable to avoid template syntax issues
try {
    window.controleRoutes = {
        @if(isset($selectedEvento) && $selectedEvento)
        evento: @json(route('admin.controle.evento', ['evento' => 'PLACEHOLDER_ID'])),
        @else
        evento: @json(route('admin.controle.index')) + '/evento/PLACEHOLDER_ID',
        @endif
        index: @json(route('admin.controle.index')),
        detalhes: @json(route('admin.controle.detalhes', ['entrada' => 'PLACEHOLDER_ID'])),
        saida: @json(route('admin.controle.saida', ['entrada' => 'PLACEHOLDER_ID'])),
        adicional: @json(route('admin.controle.adicional', ['entrada' => 'PLACEHOLDER_ID']))
    };
} catch (error) {
    console.error('Error defining controle routes:', error);
    window.controleRoutes = {
        evento: '/admin/controle/evento/PLACEHOLDER_ID',
        index: '/admin/controle',
        detalhes: '/admin/controle/detalhes/PLACEHOLDER_ID',
        saida: '/admin/controle/saida/PLACEHOLDER_ID',
        adicional: '/admin/controle/adicional/PLACEHOLDER_ID'
    };
}

function ControleRecreacao() {
    this.autoRefreshInterval = null;
    this.autoRefreshCountdown = null;
    this.isModalOpen = false;
    this.init();
}

ControleRecreacao.prototype.init = function() {
    var self = this;
    // Make sure the initialization happens after DOM is loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            self.bindEvents();
            self.setupAutoRefresh();
        });
    } else {
        this.bindEvents();
        this.setupAutoRefresh();
    }
}

ControleRecreacao.prototype.bindEvents = function() {
    var self = this;
    
    // Event selection
    var eventSelect = document.getElementById('evento-select');
    if (eventSelect) {
        eventSelect.addEventListener('change', function(e) {
            var eventoId = e.target.value;
            
            console.log('Event select changed to:', eventoId);
            
            if (!window.controleRoutes) {
                console.error('controleRoutes not available');
                alert('Erro: Rotas não carregadas. Recarregue a página.');
                return;
            }
            
            try {
                if (eventoId) {
                    var eventUrl = window.controleRoutes.evento.replace('PLACEHOLDER_ID', eventoId);
                    console.log('Redirecting to:', eventUrl);
                    
                    // Validate URL before redirecting
                    if (eventUrl && eventUrl !== window.controleRoutes.evento) {
                        window.location.href = eventUrl;
                    } else {
                        console.error('Invalid event URL generated:', eventUrl);
                        alert('Erro ao gerar URL do evento. Tente novamente.');
                    }
                } else {
                    // No event selected, go to index
                    console.log('No event selected, redirecting to index');
                    window.location.href = window.controleRoutes.index;
                }
            } catch (error) {
                console.error('Error in event selection handler:', error);
                alert('Erro ao mudar evento. Recarregue a página.');
            }
        });
    }

    // Refresh button functionality
    var refreshBtn = document.getElementById('refresh-btn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            try {
                location.reload();
            } catch (error) {
                console.error('Error refreshing page:', error);
            }
        });
    }

    // Clear search functionality
    var clearBtn = document.getElementById('clear-search');
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            try {
                var form = clearBtn.closest('form');
                if (form) {
                    var searchInput = form.querySelector('#search');
                    var statusSelect = form.querySelector('#status-filter');
                    if (searchInput) searchInput.value = '';
                    if (statusSelect) statusSelect.value = '';
                    // Clear URL parameters
                    window.location.href = window.location.pathname;
                }
            } catch (error) {
                console.error('Error clearing search:', error);
            }
        });
    }
};

ControleRecreacao.prototype.showDetails = async function(entradaId) {
    try {
        if (!entradaId) {
            throw new Error('Entrada ID is required');
        }
        if (!window.controleRoutes) {
            throw new Error('Routes not available');
        }
        const detailsUrl = window.controleRoutes.detalhes.replace('PLACEHOLDER_ID', entradaId);
        const response = await fetch(detailsUrl);
        
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error('HTTP ' + response.status + ': ' + errorText);
        }
        
        const data = await response.json();
        this.openDetailsModal(data);
        
    } catch (error) {
        console.error('Error loading details:', error);
        const message = error.message || 'Erro ao carregar detalhes da entrada';
        if (window.SweetAlert) {
            window.SweetAlert.toast('error', message);
        } else {
            alert(message);
        }
    }
};

ControleRecreacao.prototype.registerExit = async function(entradaId) {
    try {
        if (!window.controleRoutes) {
            throw new Error('Routes not available');
        }
        // Get details first for confirmation
        const detailsUrl = window.controleRoutes.detalhes.replace('PLACEHOLDER_ID', entradaId);
        const response = await fetch(detailsUrl);
        
        if (!response.ok) throw new Error('Failed to load details');
        
        const data = await response.json();
        this.openExitConfirmationModal(entradaId, data);
        
    } catch (error) {
        console.error('Error loading exit confirmation:', error);
        // Fallback to simple confirmation
        if (confirm('Confirma o registro da saída?')) {
            this.processExit(entradaId);
        }
    }
};

ControleRecreacao.prototype.openExitConfirmationModal = async function(entradaId, data) {
    const entrada = data.entrada;
    const tempoAtual = data.tempo_atual;
    const tempoExcedido = data.tempo_excedido;
    const valorAdicional = data.valor_adicional_sugerido;
    
    const hasExtraCharge = valorAdicional > 0;
    
    let message = 'Confirmar saída de ' + (entrada.vinculado?.responsavel?.nome || 'N/A') + '?\n\nTempo no parque: ' + tempoAtual;
    if (hasExtraCharge) {
        message += '\nTempo excedido! Valor adicional sugerido: R$ ' + valorAdicional.toFixed(2);
    }
    
    const result = await SweetAlert.confirm('Confirmar Saída', message);
    if (result.isConfirmed) {
        this.processExit(entradaId);
    }
};

ControleRecreacao.prototype.processExit = async function(entradaId) {
    try {
        if (!window.controleRoutes) {
            throw new Error('Routes not available');
        }
        const exitUrl = window.controleRoutes.saida.replace('PLACEHOLDER_ID', entradaId);
        
        const response = await fetch(exitUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        
        if (!response.ok) throw new Error('Failed to register exit');
        
        const result = await response.json();
        
        SweetAlert.toast('success', result.success || 'Saída registrada com sucesso!');
        location.reload();
        
    } catch (error) {
        console.error('Error processing exit:', error);
        SweetAlert.toast('error', 'Erro ao registrar saída: ' + error.message);
    }
};

ControleRecreacao.prototype.chargeAdditional = async function(entradaId) {
    try {
        if (!window.controleRoutes) {
            throw new Error('Routes not available');
        }
        // First get the details to calculate suggested values
        const detailsUrl = window.controleRoutes.detalhes.replace('PLACEHOLDER_ID', entradaId);
        const response = await fetch(detailsUrl);
        
        if (!response.ok) throw new Error('Failed to load details');
        
        const data = await response.json();
        this.openPaymentModal(entradaId, data);
        
    } catch (error) {
        console.error('Error loading payment modal:', error);
        SweetAlert.toast('error', 'Erro ao carregar dados para cobrança adicional');
    }
};

ControleRecreacao.prototype.openPaymentModal = function(entradaId, data) {
    const entrada = data.entrada;
    const tempoExcedidoMinutos = data.tempo_excedido_minutos; // Use raw minutes
    const valorAdicionalSugerido = data.valor_adicional_sugerido;
    
    // Fill the payment form
    const entradaIdInput = document.getElementById('entrada-id');
    const tempoAdicionalInput = document.getElementById('tempo-adicional');
    const valorAdicionalInput = document.getElementById('valor-adicional');
    
    if (entradaIdInput) entradaIdInput.value = entradaId;
    if (tempoAdicionalInput) tempoAdicionalInput.value = Math.ceil(parseFloat(tempoExcedidoMinutos) || 0);
    if (valorAdicionalInput) valorAdicionalInput.value = valorAdicionalSugerido?.toFixed(2) || '0.00';
    
    // Show the payment modal
    const modal = document.getElementById('payment-modal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        this.isModalOpen = true;
    }
};

ControleRecreacao.prototype.closePaymentModal = function() {
    const modal = document.getElementById('payment-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
    
    // Reset form
    const form = document.getElementById('payment-form');
    if (form) form.reset();
    
    this.isModalOpen = false;
};

ControleRecreacao.prototype.processPayment = async function() {
    let submitButton = null;
    try {
        const form = document.getElementById('payment-form');
        if (!form) throw new Error('Formulário não encontrado');
        
        const formData = new FormData(form);
        const entradaId = formData.get('entrada_id');
        
        if (!entradaId) throw new Error('ID da entrada não encontrado');
        
        if (!window.controleRoutes) {
            throw new Error('Rotas não disponíveis');
        }
        const paymentUrl = window.controleRoutes.adicional.replace('PLACEHOLDER_ID', entradaId);
        
        // Show loading state - find button more reliably
        submitButton = document.querySelector('button[onclick*="processPayment"]') || 
                      form.querySelector('button[type="button"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Processando...';
        }
        
        const response = await fetch(paymentUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error || 'Payment processing failed');
        }
        
        const result = await response.json();
        
        // Handle PIX payment
        if (result.pix_qr_code) {
            this.showPixPayment(result);
        } else {
            // Regular payment success
            SweetAlert.toast('success', result.success || 'Cobrança processada com sucesso!');
            this.closePaymentModal();
            location.reload();
        }
        
    } catch (error) {
        console.error('Error processing payment:', error);
        const message = 'Erro ao processar pagamento: ' + (error.message || 'Erro desconhecido');
        
        if (window.SweetAlert) {
            window.SweetAlert.toast('error', message);
        } else {
            alert(message);
        }
        
        // Reset button
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = 'Processar Pagamento';
        }
    }
};

ControleRecreacao.prototype.showPixPayment = function(paymentData) {
    // Show PIX QR code modal
    const pixModal = '<div id="pix-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center" style="z-index: 1050;">' +
            '<div class="relative p-5 border w-full max-w-md m-auto flex-col flex rounded-md shadow-lg bg-white">' +
                '<div class="mt-3 text-center">' +
                    '<h3 class="text-lg font-medium text-gray-900">Pagamento PIX</h3>' +
                    '<div class="mt-4">' +
                        '<img src="data:image/png;base64,' + paymentData.pix_qr_code + '" class="mx-auto mb-4" alt="QR Code PIX">' +
                        '<p class="text-sm text-gray-600 mb-2">Escaneie o código QR ou copie o código PIX:</p>' +
                        '<div class="bg-gray-100 p-2 rounded text-xs break-all mb-4">' +
                            paymentData.pix_code +
                        '</div>' +
                        '<p class="text-xs text-gray-500 mt-2">Valor: R$ ' + parseFloat(paymentData.entrada?.pgto_extra_valor || 0).toFixed(2) + '</p>' +
                        '<p class="text-xs text-gray-500">Expira em: ' + paymentData.expiration_date + '</p>' +
                    '</div>' +
                    '<div class="items-center px-4 py-3">' +
                        '<button onclick="controle.closePixModal(\'' + paymentData.payment_id + '\')" class="px-4 py-2 bg-indigo-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">' +
                            'Fechar' +
                        '</button>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', pixModal);
    this.isModalOpen = true;
    
    // Start polling payment status
    this.pollPaymentStatus(paymentData.payment_id);
};

ControleRecreacao.prototype.closePixModal = function(paymentId) {
    const pixModal = document.getElementById('pix-modal');
    if (pixModal) {
        pixModal.remove();
    }
    this.isModalOpen = false;
    this.closePaymentModal();
    location.reload(); // Reload to see updated status
};

ControleRecreacao.prototype.pollPaymentStatus = async function(paymentId) {
    const pollInterval = setInterval(async () => {
        try {
            const response = await fetch('/api/payment-status/' + paymentId);
            if (response.ok) {
                const data = await response.json();
                if (data.status === 'approved') {
                    clearInterval(pollInterval);
                    SweetAlert.toast('success', 'Pagamento PIX confirmado!');
                    this.closePixModal(paymentId);
                }
            }
        } catch (error) {
            console.error('Error polling payment status:', error);
        }
    }, 5000); // Poll every 5 seconds
    
    // Stop polling after 10 minutes
    setTimeout(() => {
        clearInterval(pollInterval);
    }, 600000);
};

ControleRecreacao.prototype.openDetailsModal = function(data) {
    const entrada = data.entrada;
    const tempoAtual = data.tempo_atual;
    const tempoLimite = data.tempo_limite;
    const tempoExcedido = data.tempo_excedido;
    const tempoExcedidoMinutos = data.tempo_excedido_minutos;
    
    // Check if time is expired and payment required
    const isExpired = tempoExcedidoMinutos > 0;
    const paymentRequired = isExpired && !entrada.pgto_extra;
    
    const statusColor = entrada.status_real === 'vencido' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';
    const statusText = entrada.status_real === 'vencido' ? 'Tempo Vencido' : 'Ativo no Parque';
    const statusIcon = entrada.status_real === 'vencido' 
        ? '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"></path></svg>'
        : '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>';

    const modalContent = '<div id="details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center p-4" style="z-index: 1050;">' +
            '<div class="relative w-full max-w-2xl bg-white rounded-xl shadow-2xl transform transition-all">' +
                '<!-- Header -->' +
                '<div class="relative bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 rounded-t-xl">' +
                    '<div class="flex items-center justify-between">' +
                        '<div class="flex items-center space-x-3">' +
                            '<div class="p-2 bg-white bg-opacity-20 rounded-lg">' +
                                '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>' +
                                '</svg>' +
                            '</div>' +
                            '<h3 class="text-xl font-semibold text-white">Detalhes da Entrada</h3>' +
                        '</div>' +
                        '<button onclick="controle.closeDetailsModal()" class="text-white hover:text-gray-200 transition-colors">' +
                            '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />' +
                            '</svg>' +
                        '</button>' +
                    '</div>' +
                    
                    '<!-- Status Badge -->' +
                    '<div class="mt-4 inline-flex items-center space-x-2 ' + statusColor + ' px-3 py-1 rounded-full">' +
                        statusIcon +
                        '<span class="text-sm font-medium">' + statusText + '</span>' +
                    '</div>' +
                '</div>' +

                '<!-- Body -->' +
                '<div class="p-6">' +
                    '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">' +
                        '<!-- Dados Pessoais -->' +
                        '<div class="space-y-4">' +
                            '<h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Dados Pessoais</h4>' +
                            
                            '<div class="space-y-3">' +
                                '<div class="flex items-center space-x-3">' +
                                    '<div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">' +
                                        '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>' +
                                        '</svg>' +
                                    '</div>' +
                                    '<div class="flex-1">' +
                                        '<p class="text-sm text-gray-500">Responsável</p>' +
                                        '<p class="font-medium text-gray-900">' + (entrada.vinculado?.responsavel?.nome || 'N/A') + '</p>' +
                                    '</div>' +
                                '</div>' +

                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-500">CPF</p>
                                        <p class="font-medium text-gray-900 font-mono">${entrada.vinculado?.responsavel?.cpf || 'N/A'}</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.01M15 10h1.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-500">Participante</p>
                                        <p class="font-medium text-gray-900">${entrada.vinculado?.nome || 'N/A'}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dados do Atendimento -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Dados do Atendimento</h4>
                            
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-500">Pacote</p>
                                        <p class="font-medium text-gray-900">${entrada.pacote_nome || 'N/A'}</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-500">Horário de Entrada</p>
                                        <p class="font-medium text-gray-900">${new Date(entrada.datahora_entrada).toLocaleString('pt-BR')}</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-500">Tempo no Parque</p>
                                        <p class="font-medium text-gray-900">${tempoAtual}</p>
                                        <p class="text-xs text-gray-400">Limite: ${tempoLimite}</p>
                                    </div>
                                </div>

                                ${tempoExcedido ? `
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm text-red-600 font-medium">Tempo Excedido</p>
                                            <p class="font-semibold text-red-700">${tempoExcedido}</p>
                                        </div>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex justify-end space-x-3">
                    <button onclick="controle.closeDetailsModal()" 
                            class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                        Fechar
                    </button>
                    ${paymentRequired ? `
                        <div class="flex flex-col items-center">
                            <button disabled class="px-6 py-2 bg-gray-400 text-gray-200 rounded-lg cursor-not-allowed font-medium flex items-center space-x-2" title="Pagamento adicional obrigatório antes da saída">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 0h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2zM13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>Registrar Saída</span>
                            </button>
                            <span class="text-xs text-red-600 mt-1">Pagar primeiro</span>
                        </div>
                    ` : `
                        <button onclick="controle.registerExit(${entrada.id}); controle.closeDetailsModal();" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Registrar Saída</span>
                    </button>
                </div>
            </div>
        </div>
    `; 

    document.body.insertAdjacentHTML('beforeend', modalContent);
    this.isModalOpen = true;
};

ControleRecreacao.prototype.closeDetailsModal = function() {
    const modal = document.getElementById('details-modal');
    if (modal) {
        modal.remove();
    }
    this.isModalOpen = false;
};

ControleRecreacao.prototype.setupAutoRefresh = function() {
    // Only setup auto-refresh if we have an active event
    const selectedEvent = document.getElementById('evento-select');
    if (!selectedEvent || !selectedEvent.value) {
        return;
    }

    this.startAutoRefresh();
};

ControleRecreacao.prototype.startAutoRefresh = function() {
    // Clear any existing intervals
    this.stopAutoRefresh();

    // Start 60-second countdown
    var countdown = 60;
    
    // Create or update countdown display
    this.updateCountdownDisplay(countdown);

    var self = this;
    this.autoRefreshCountdown = setInterval(function() {
        countdown--;
        self.updateCountdownDisplay(countdown);

        if (countdown <= 0) {
            // Only refresh if no modal is open
            if (!self.isModalOpen) {
                location.reload();
            } else {
                // Reset countdown if modal is open
                countdown = 60;
            }
        }
    }, 1000);
};

ControleRecreacao.prototype.stopAutoRefresh = function() {
    if (this.autoRefreshInterval) {
        clearInterval(this.autoRefreshInterval);
        this.autoRefreshInterval = null;
    }
    if (this.autoRefreshCountdown) {
        clearInterval(this.autoRefreshCountdown);
        this.autoRefreshCountdown = null;
    }
    this.removeCountdownDisplay();
};

ControleRecreacao.prototype.updateCountdownDisplay = function(seconds) {
    var countdownEl = document.getElementById('auto-refresh-countdown');
    if (!countdownEl) {
        // Create countdown display
        var refreshBtn = document.getElementById('refresh-btn');
        if (refreshBtn) {
            countdownEl = document.createElement('div');
            countdownEl.id = 'auto-refresh-countdown';
            countdownEl.className = 'text-xs text-gray-500 mt-1 text-center';
            refreshBtn.parentNode.appendChild(countdownEl);
        }
    }

    if (countdownEl) {
        countdownEl.textContent = 'Atualização automática em ' + seconds + 's';
    }
};

ControleRecreacao.prototype.removeCountdownDisplay = function() {
    var countdownEl = document.getElementById('auto-refresh-countdown');
    if (countdownEl) {
        countdownEl.remove();
    }
};

// Create a comprehensive fallback object first
window.controle = {
    showDetails: function(id) { 
        console.error('Controle not initialized properly'); 
        alert('Sistema não inicializado. Recarregue a página.');
    },
    registerExit: function(id) { 
        console.error('Controle not initialized properly'); 
        alert('Sistema não inicializado. Recarregue a página.');
    },
    chargeAdditional: function(id) { 
        console.error('Controle not initialized properly'); 
        alert('Sistema não inicializado. Recarregue a página.');
    },
    closePaymentModal: function() { 
        console.error('Controle not initialized properly'); 
    },
    processPayment: function() { 
        console.error('Controle not initialized properly'); 
        alert('Sistema não inicializado. Recarregue a página.');
    },
    closePixModal: function(id) {
        console.error('Controle not initialized properly');
    },
    closeDetailsModal: function() {
        console.error('Controle not initialized properly');
    }
};

// Initialize when DOM is ready
function initializeControle() {
    try {
        console.log('Initializing ControleRecreacao...');
        
        // Check dependencies
        if (typeof window.SweetAlert === 'undefined') {
            console.warn('SweetAlert not available, some features may not work');
        }
        
        if (!window.controleRoutes) {
            console.error('controleRoutes not defined, check route configuration');
            return false;
        }
        
        // Initialize the controle object
        const controleInstance = new ControleRecreacao();
        window.controle = controleInstance;
        
        console.log('ControleRecreacao initialized successfully');
        return true;
        
    } catch (error) {
        console.error('Error initializing ControleRecreacao:', error);
        return false;
    }
}

// Try immediate initialization
if (document.readyState === 'loading') {
    // DOM not ready yet
    document.addEventListener('DOMContentLoaded', initializeControle);
} else {
    // DOM already ready
    initializeControle();
}

// Safety fallback - try again after a short delay
setTimeout(function() {
    if (typeof window.controle.showDetails === 'function' && 
        window.controle.showDetails.toString().includes('not initialized')) {
        console.warn('Controle still using fallback, retrying initialization...');
        initializeControle();
    }
}, 500);
</script>
@endpush
@endsection