@extends('admin.layout')

@section('title', 'Gestão de Responsáveis')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestão de Responsáveis</h1>
            <p class="mt-1 text-sm text-gray-500">
                @if($selectedEvento)
                    Gerencie os responsáveis do evento "{{ $selectedEvento->titulo }}"
                @else
                    Selecione um evento para gerenciar os responsáveis
                @endif
            </p>
        </div>
        @if($selectedEvento)
        <div class="mt-4 sm:mt-0 sm:flex-none space-x-3">
            <button type="button" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Exportar
            </button>
            <a href="{{ route('admin.responsaveis.create', ['evento_id' => $selectedEvento->id]) }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Novo Responsável
            </a>
        </div>
        @endif
    </div>

    <!-- Event Selector -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" class="space-y-4">
                <div class="sm:flex sm:items-center sm:space-x-4">
                    <div class="flex-1">
                        <label for="evento_id" class="block text-sm font-medium text-gray-700">Selecione um evento</label>
                        <select name="evento_id" id="evento_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" onchange="this.form.submit()">
                            <option value="">Selecione um evento...</option>
                            @foreach($eventos as $evento)
                                <option value="{{ $evento->id }}" {{ request('evento_id') == $evento->id ? 'selected' : '' }}>
                                    {{ $evento->titulo }} - {{ $evento->data_inicio->format('d/m/Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($selectedEvento && count($stats) > 0)
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-5">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-md bg-blue-500 text-white">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Responsáveis</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ number_format($stats['total'] ?? 0) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-md bg-green-500 text-white">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Ativos</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ number_format($stats['ativos'] ?? 0) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-md bg-purple-500 text-white">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Com Vinculados</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ number_format($stats['com_vinculados'] ?? 0) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-md bg-yellow-500 text-white">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Cadastros Hoje</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ number_format($stats['cadastros_hoje'] ?? 0) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-md bg-red-500 text-white">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">No Parque</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ number_format($stats['no_parque'] ?? 0) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($selectedEvento)
    <!-- Filters -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" class="space-y-4" id="filter-form">
                <input type="hidden" name="evento_id" value="{{ $selectedEvento->id }}">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="sm:flex sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                        <!-- Search -->
                        <div class="flex-1 min-w-0">
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" id="search-icon">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none hidden" id="search-loading">
                                    <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Buscar por nome, CPF, email..." id="search-input">
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="min-w-0">
                            <select name="status" id="status-filter" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Status</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Ativo</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inativo</option>
                            </select>
                        </div>

                        <!-- Has Participants -->
                        <div class="min-w-0">
                            <select name="vinculados" id="vinculados-filter" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Vinculados</option>
                                <option value="with" {{ request('vinculados') == 'with' ? 'selected' : '' }}>Com vinculados</option>
                                <option value="without" {{ request('vinculados') == 'without' ? 'selected' : '' }}>Sem vinculados</option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div class="min-w-0">
                            <select name="periodo" id="periodo-filter" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Período de cadastro</option>
                                <option value="today" {{ request('periodo') == 'today' ? 'selected' : '' }}>Hoje</option>
                                <option value="week" {{ request('periodo') == 'week' ? 'selected' : '' }}>Esta semana</option>
                                <option value="month" {{ request('periodo') == 'month' ? 'selected' : '' }}>Este mês</option>
                            </select>
                        </div>

                        <div class="flex space-x-2">
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Filtrar
                            </button>
                            <a href="{{ route('admin.responsaveis.index', ['evento_id' => $selectedEvento->id]) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Limpar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Responsáveis Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="sm:flex sm:items-center sm:justify-between">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Lista de Responsáveis
                    @if($selectedEvento && $responsaveis->total() > 0)
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ number_format($responsaveis->total()) }} 
                            {{ $responsaveis->total() === 1 ? 'responsável' : 'responsáveis' }}
                            @if(request()->hasAny(['search', 'status', 'vinculados', 'periodo']))
                                <span class="ml-1 text-indigo-600">filtrado{{ $responsaveis->total() === 1 ? '' : 's' }}</span>
                            @endif
                        </span>
                    @endif
                </h3>
                <div class="mt-3 sm:mt-0 sm:ml-4">
                    <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                        </svg>
                        Ordenar
                    </button>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Responsável
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contato
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Vinculados
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Última Entrada
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
                    @if($selectedEvento && $responsaveis->count() > 0)
                        @foreach($responsaveis as $responsavel)
                            @php
                                $allEntradas = $responsavel->vinculados->flatMap->entradas;
                                $noParque = $allEntradas->where('status', 'ativo')->count() > 0;
                                $ultimaEntrada = $allEntradas->sortByDesc('datahora_entrada')->first();
                                $iniciais = collect(explode(' ', $responsavel->nome))->map(fn($name) => strtoupper(substr($name, 0, 1)))->take(2)->join('');
                                $colors = ['from-blue-500 to-purple-600', 'from-pink-500 to-red-600', 'from-green-500 to-blue-600', 'from-yellow-500 to-orange-600', 'from-purple-500 to-indigo-600'];
                                $colorIndex = abs(crc32($responsavel->nome)) % count($colors);
                                $inscricaoAtual = $responsavel->inscricoes->first();
                                $isAtivo = $inscricaoAtual ? $inscricaoAtual->ativo : false;
                            @endphp
                            <tr class="hover:bg-gray-50 {{ $noParque ? 'bg-yellow-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="responsaveis[]" value="{{ $responsavel->id }}" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br {{ $colors[$colorIndex] }} flex items-center justify-center">
                                                <span class="text-sm font-medium text-white">{{ $iniciais }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $responsavel->nome }}</div>
                                            <div class="text-sm text-gray-500">CPF: {{ $responsavel->cpf_formatted }}</div>
                                            <div class="text-xs text-gray-400">Nascimento: {{ $responsavel->nascimento->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $responsavel->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $responsavel->telefone1 }}</div>
                                    @if($responsavel->telefone2)
                                        <div class="text-sm text-gray-400">{{ $responsavel->telefone2 }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($responsavel->vinculados->count() > 0)
                                        <div class="flex items-center space-x-2 flex-wrap">
                                            @php
                                                $vinculos = $responsavel->vinculados->groupBy('vinculo.descricao');
                                                $colors = [
                                                    'Criança' => 'bg-blue-100 text-blue-800',
                                                    'Adolescente' => 'bg-purple-100 text-purple-800',
                                                    'Adulto' => 'bg-green-100 text-green-800',
                                                    'Idoso' => 'bg-yellow-100 text-yellow-800',
                                                    'PCD' => 'bg-red-100 text-red-800',
                                                    'Cônjuge' => 'bg-pink-100 text-pink-800',
                                                    'Familiar' => 'bg-indigo-100 text-indigo-800'
                                                ];
                                            @endphp
                                            @foreach($vinculos as $vinculoDescricao => $vinculados)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colors[$vinculoDescricao] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $vinculados->count() }} {{ $vinculoDescricao }}{{ $vinculados->count() > 1 ? 's' : '' }}
                                                </span>
                                            @endforeach
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            @foreach($responsavel->vinculados->take(3) as $vinculado)
                                                {{ $vinculado->nome }}@if(!$loop->last && $loop->index < 2), @endif
                                            @endforeach
                                            @if($responsavel->vinculados->count() > 3)
                                                ... (+{{ $responsavel->vinculados->count() - 3 }} mais)
                                            @endif
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Sem vinculados
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($ultimaEntrada)
                                        @if($noParque)
                                            <div class="text-sm text-gray-900 font-semibold text-green-600">ATIVO AGORA</div>
                                            <div class="text-sm text-gray-500">Entrada: {{ \Carbon\Carbon::parse($ultimaEntrada->datahora_entrada)->format('H:i') }} - {{ $ultimaEntrada->evento->titulo }}</div>
                                        @else
                                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($ultimaEntrada->datahora_entrada)->format('d/m/Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($ultimaEntrada->datahora_entrada)->format('H:i') }} - {{ $ultimaEntrada->evento->titulo }}</div>
                                        @endif
                                    @else
                                        <div class="text-sm text-gray-500">Nunca visitou</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($noParque)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            No parque
                                        </span>
                                    @elseif($isAtivo)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Ativo
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Inativo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.responsaveis.show', ['responsavel' => $responsavel->id, 'evento_id' => $selectedEvento->id]) }}" class="text-indigo-600 hover:text-indigo-900" title="Ver detalhes">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.responsaveis.edit', ['responsavel' => $responsavel->id, 'evento_id' => $selectedEvento->id]) }}" class="text-green-600 hover:text-green-900" title="Editar">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <button onclick="toggleStatus({{ $responsavel->id }}, {{ $selectedEvento->id }})" class="text-{{ $isAtivo ? 'red' : 'green' }}-600 hover:text-{{ $isAtivo ? 'red' : 'green' }}-900" title="{{ $isAtivo ? 'Desativar' : 'Ativar' }}">
                                        @if($isAtivo)
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728A9 9 0 115.636 5.636" />
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @elseif($selectedEvento)
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum responsável encontrado</h3>
                                    <p class="mt-1 text-sm text-gray-500">Não há responsáveis cadastrados para este evento com os filtros selecionados.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('admin.responsaveis.create', ['evento_id' => $selectedEvento->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Cadastrar primeiro responsável
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 14v-2m6-6V9a6 6 0 00-12 0v4h8z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Selecione um evento</h3>
                                    <p class="mt-1 text-sm text-gray-500">Para gerenciar os responsáveis, você precisa selecionar um evento primeiro.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if($selectedEvento && $responsaveis->count() > 0)
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-700">
                    Mostrando <span class="font-medium">{{ $responsaveis->firstItem() ?? 0 }}</span> a <span class="font-medium">{{ $responsaveis->lastItem() ?? 0 }}</span> de
                    <span class="font-medium">{{ $responsaveis->total() }}</span> responsáveis
                </div>
                <div class="flex space-x-2">
                    {{ $responsaveis->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Bulk Actions -->
    <div class="fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-white rounded-lg shadow-lg border border-gray-200 px-6 py-4 hidden" id="bulk-actions">
        <div class="flex items-center space-x-4">
            <span class="text-sm font-medium text-gray-900">3 itens selecionados</span>
            <div class="flex space-x-2">
                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                    Exportar
                </button>
                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200">
                    Desativar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function toggleStatus(responsavelId, eventoId) {
    Swal.fire({
        title: 'Alterar Status',
        text: 'Tem certeza que deseja alterar o status deste responsável?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, alterar!',
        cancelButtonText: 'Cancelar' 
        }).then((result) => {
        if (result.isConfirmed) {
        fetch(`/admin/responsaveis/${responsavelId}/toggle-status?evento_id=${eventoId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                evento_id: eventoId
            })
        })
        .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Sucesso!',
                        'Status alterado com sucesso!',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire(
                        'Erro!',
                        data.error || 'Erro ao alterar status',
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'Erro!',
                    'Erro ao alterar status',
                    'error'
                );
            });
        }
    });
}

// Search and Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    const vinculadosFilter = document.getElementById('vinculados-filter');
    const periodoFilter = document.getElementById('periodo-filter');
    const filterForm = document.getElementById('filter-form');
    
    let searchTimeout;
    
    // Function to show loading state
    function showLoading() {
        const searchIcon = document.getElementById('search-icon');
        const searchLoading = document.getElementById('search-loading');
        if (searchIcon) searchIcon.classList.add('hidden');
        if (searchLoading) searchLoading.classList.remove('hidden');
    }
    
    // Submit form on enter key in search input
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                showLoading();
                filterForm.submit();
            }
        });
    }
    
    // Manual filter submission only - removed auto-submit on filter change
    
    // Bulk actions
    const checkboxes = document.querySelectorAll('input[name="responsaveis[]"]');
    const bulkActions = document.getElementById('bulk-actions');
    const selectAll = document.querySelector('input[type="checkbox"]:not([name])');
    
    // Select all functionality
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkActions();
        });
    }
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkActions);
    });
    
    function updateBulkActions() {
        const selected = Array.from(checkboxes).filter(cb => cb.checked);
        if (selected.length > 0) {
            bulkActions.classList.remove('hidden');
            bulkActions.querySelector('span').textContent = `${selected.length} ${selected.length === 1 ? 'item selecionado' : 'itens selecionados'}`;
        } else {
            bulkActions.classList.add('hidden');
        }
        
        // Update select all checkbox state
        if (selectAll) {
            selectAll.indeterminate = selected.length > 0 && selected.length < checkboxes.length;
            selectAll.checked = selected.length === checkboxes.length;
        }
    }
});
</script>
@endpush
@endsection