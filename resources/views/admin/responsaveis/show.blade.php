@extends('admin.layout')

@section('title', 'Detalhes do Responsável')

@section('content')
@php
    $allEntradas = $responsavel->vinculados->flatMap->entradas;
@endphp
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $responsavel->nome }}</h1>
            <p class="mt-1 text-sm text-gray-500">
                @if($inscricaoAtual && $inscricaoAtual->evento)
                    Responsável no evento "{{ $inscricaoAtual->evento->titulo }}"
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $inscricaoAtual->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $inscricaoAtual->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                @else
                    Detalhes do responsável
                @endif
            </p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('admin.responsaveis.edit', ['responsavel' => $responsavel, 'evento_id' => $eventoId]) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Editar
            </a>
            <a href="{{ route('admin.responsaveis.index', ['evento_id' => $eventoId]) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Voltar
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-5">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-md bg-blue-500 text-white">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Entradas</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ number_format($stats['total_entradas']) }}</dd>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Entradas Ativas</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ number_format($stats['entradas_ativas']) }}</dd>
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Vinculados</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ number_format($stats['vinculados_count']) }}</dd>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Última Visita</dt>
                            <dd class="text-lg font-semibold text-gray-900">
                                @if($stats['ultima_visita'])
                                    {{ \Carbon\Carbon::parse($stats['ultima_visita']->datahora_entrada)->format('d/m/Y') }}
                                @else
                                    Nunca
                                @endif
                            </dd>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Status</dt>
                            <dd class="text-lg font-semibold">
                                @if($inscricaoAtual && $inscricaoAtual->ativo)
                                    <span class="text-green-600">Ativo</span>
                                @else
                                    <span class="text-red-600">Inativo</span>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Personal Information -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Dados Pessoais</h3>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nome Completo</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $responsavel->nome }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">CPF</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $responsavel->cpf_formatted }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">E-mail</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $responsavel->email }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Data de Nascimento</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $responsavel->nascimento->format('d/m/Y') }}
                                <span class="text-gray-500">({{ $responsavel->nascimento->age }} anos)</span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Telefone Principal</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $responsavel->telefone1 }}</dd>
                        </div>

                        @if($responsavel->telefone2)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Telefone Alternativo</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $responsavel->telefone2 }}</dd>
                        </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Evento</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $inscricaoAtual && $inscricaoAtual->evento ? $inscricaoAtual->evento->titulo : 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Data de Cadastro</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $responsavel->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-lg leading-6 font-medium text-gray-900 mb-4">Configurações de Comunicação</h4>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Aceita Comunicações</dt>
                                <dd class="mt-1">
                                    @if($inscricaoAtual && $inscricaoAtual->comunica)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Sim
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Não
                                        </span>
                                    @endif
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Forma de Comunicação</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($inscricaoAtual)
                                        @switch($inscricaoAtual->device_comunica)
                                            @case('email')
                                                E-mail
                                                @break
                                            @case('sms')
                                                SMS
                                                @break
                                            @case('whatsapp')
                                                WhatsApp
                                                @break
                                            @case('todos')
                                                Todos os meios
                                                @break
                                            @default
                                                {{ $inscricaoAtual->device_comunica }}
                                        @endswitch
                                    @else
                                        N/A
                                    @endif
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($responsavel->vinculados->count() > 0)
            <!-- Vinculados -->
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Pessoas Vinculadas</h3>
                    
                    <div class="space-y-4">
                        @foreach($responsavel->vinculados as $vinculado)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $vinculado->nome }}</h4>
                                        <p class="text-sm text-gray-500">
                                            {{ $vinculado->nascimento->format('d/m/Y') }} 
                                            ({{ $vinculado->nascimento->age }} anos) - 
                                            <span class="capitalize">{{ $vinculado->vinculo ? $vinculado->vinculo->descricao : 'N/A' }}</span>
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @php
                                            $vinculoDescricao = $vinculado->vinculo ? strtolower($vinculado->vinculo->descricao) : '';
                                            $colors = [
                                                'criança' => 'bg-blue-100 text-blue-800',
                                                'adolescente' => 'bg-purple-100 text-purple-800',
                                                'adulto' => 'bg-green-100 text-green-800',
                                                'idoso' => 'bg-yellow-100 text-yellow-800',
                                                'pcd' => 'bg-red-100 text-red-800',
                                                'cônjuge' => 'bg-pink-100 text-pink-800',
                                                'familiar' => 'bg-indigo-100 text-indigo-800'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colors[$vinculoDescricao] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $vinculado->vinculo ? $vinculado->vinculo->descricao : 'N/A' }}
                                        </span>
                                        @if($vinculado->lembrar)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                Comunicar
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Recent Activities -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Histórico de Entradas</h3>
                    
                    @php
                        $allEntradas = $responsavel->vinculados->flatMap->entradas->sortByDesc('datahora_entrada')->take(10);
                    @endphp
                    @if($allEntradas->count() > 0)
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach($allEntradas as $entrada)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                @if($entrada->status == 'ativo')
                                                    <span class="bg-green-500 h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                        </svg>
                                                    </span>
                                                @else
                                                    <span class="bg-gray-400 h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1" />
                                                        </svg>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-900">{{ $entrada->evento->titulo }}</p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ \Carbon\Carbon::parse($entrada->datahora_entrada)->format('d/m/Y H:i') }}
                                                        @if($entrada->datahora_saida)
                                                            - {{ \Carbon\Carbon::parse($entrada->datahora_saida)->format('H:i') }}
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    @if($entrada->status == 'ativo')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Ativo
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            Finalizado
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma entrada</h3>
                            <p class="mt-1 text-sm text-gray-500">Este responsável ainda não fez nenhuma entrada em eventos.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Ações Rápidas</h3>
                    
                    <div class="space-y-3">
                        <button onclick="toggleStatus({{ $responsavel->id }}, {{ $eventoId }})" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white {{ ($inscricaoAtual && $inscricaoAtual->ativo) ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            @if($inscricaoAtual && $inscricaoAtual->ativo)
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728A9 9 0 015.636 5.636" />
                                </svg>
                                Desativar Responsável
                            @else
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Ativar Responsável
                            @endif
                        </button>
                        
                        @if($allEntradas->count() == 0)
                        <button onclick="deleteResponsavel({{ $responsavel->id }}, {{ $eventoId }})" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Excluir Responsável
                        </button>
                        @endif
                    </div>
                </div>
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

function deleteResponsavel(responsavelId, eventoId) {
    Swal.fire({
        title: 'Excluir Responsável',
        text: 'Tem certeza que deseja excluir este responsável? Esta ação não pode ser desfeita.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/responsaveis/${responsavelId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Excluído!',
                        'Responsável excluído com sucesso!',
                        'success'
                    ).then(() => {
                        window.location.href = '{{ route("admin.responsaveis.index", ["evento_id" => $eventoId]) }}';
                    });
                } else {
                    Swal.fire(
                        'Erro!',
                        data.error || 'Erro ao excluir responsável',
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'Erro!',
                    'Erro ao excluir responsável',
                    'error'
                );
            });
        }
    });
}
</script>
@endpush
@endsection