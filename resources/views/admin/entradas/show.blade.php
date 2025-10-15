@extends('admin.layout')

@section('title', 'Detalhes da Entrada')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detalhes da Entrada #{{ $entrada->id }}</h1>
            <p class="mt-1 text-sm text-gray-500">
                Visualize todas as informações desta entrada
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none flex space-x-2">
            @if($entrada->podeRegistrarSaida())
                <button id="registrar-saida-btn" class="inline-flex items-center justify-center rounded-md border border-transparent bg-orange-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-orange-700">
                    <i class="fas fa-sign-out-alt -ml-1 mr-2 h-4 w-4"></i>
                    Registrar Saída
                </button>
            @endif
            
            @if($entrada->podeSerEditada())
                <a href="{{ route('admin.entradas.edit', $entrada) }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                    <i class="fas fa-edit -ml-1 mr-2 h-4 w-4"></i>
                    Editar
                </a>
            @endif
            
            <a href="{{ route('admin.entradas.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                <i class="fas fa-arrow-left -ml-1 mr-2 h-4 w-4"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informações Principais -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-ticket-alt mr-2 text-indigo-500"></i>
                    Informações da Entrada
                </h3>
                
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">#{{ $entrada->id }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($entrada->status === 'entrada' || $entrada->status === 'presente') bg-green-100 text-green-800
                                @elseif($entrada->status === 'saida') bg-gray-100 text-gray-800
                                @elseif($entrada->status === 'cancelado') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $entrada->status_label }}
                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipo de Entrada</dt>
                        <dd class="mt-1">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($entrada->tipo_entrada === 'individual') bg-blue-100 text-blue-800
                                @elseif($entrada->tipo_entrada === 'pacote') bg-purple-100 text-purple-800
                                @elseif($entrada->tipo_entrada === 'prevenda') bg-green-100 text-green-800
                                @elseif($entrada->tipo_entrada === 'cortesia') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $entrada->tipo_entrada_label }}
                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Valor Pago</dt>
                        <dd class="mt-1 text-sm font-semibold text-green-600">{{ $entrada->valor_formatado }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Forma de Pagamento</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $entrada->forma_pagamento_label }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Ativo</dt>
                        <dd class="mt-1">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $entrada->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $entrada->ativo ? 'Sim' : 'Não' }}
                            </span>
                        </dd>
                    </div>
                    
                    @if($entrada->observacoes)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Observações</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $entrada->observacoes }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Informações do Evento -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-calendar-alt mr-2 text-green-500"></i>
                    Evento
                </h3>
                
                @if($entrada->evento)
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Título</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $entrada->evento->titulo }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Local</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $entrada->evento->local }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Cidade</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $entrada->evento->cidade }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Período</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $entrada->evento->data_inicio->format('d/m/Y') }} a {{ $entrada->evento->data_fim->format('d/m/Y') }}
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status do Evento</dt>
                        <dd class="mt-1">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($entrada->evento->status === 'ativo') bg-green-100 text-green-800
                                @elseif($entrada->evento->status === 'inativo') bg-red-100 text-red-800
                                @elseif($entrada->evento->status === 'finalizado') bg-gray-100 text-gray-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $entrada->evento->status_label }}
                            </span>
                        </dd>
                    </div>
                </dl>
                @else
                <p class="text-sm text-gray-500">Evento não encontrado</p>
                @endif
            </div>
        </div>

        <!-- Informações do Responsável -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-user mr-2 text-blue-500"></i>
                    Responsável
                </h3>
                
                @if($entrada->responsavel)
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nome</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $entrada->responsavel->nome }}</dd>
                    </div>
                    
                    @if($entrada->responsavel->email)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">E-mail</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $entrada->responsavel->email }}</dd>
                    </div>
                    @endif
                    
                    @if($entrada->responsavel->telefone1)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Telefone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $entrada->responsavel->telefone1 }}</dd>
                    </div>
                    @endif
                    
                    @if($entrada->responsavel->cpf)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">CPF</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $entrada->responsavel->cpf_formatted }}</dd>
                    </div>
                    @endif
                </dl>
                @else
                <p class="text-sm text-gray-500">Entrada anônima (sem responsável associado)</p>
                @endif
            </div>
        </div>

        <!-- Controle de Tempo -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-clock mr-2 text-yellow-500"></i>
                    Controle de Tempo
                </h3>
                
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Data de Entrada</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">
                            {{ $entrada->data_entrada->format('d/m/Y H:i:s') }}
                        </dd>
                    </div>
                    
                    @if($entrada->data_saida)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Data de Saída</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">
                            {{ $entrada->data_saida->format('d/m/Y H:i:s') }}
                        </dd>
                    </div>
                    @endif
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tempo de Permanência</dt>
                        <dd class="mt-1 text-sm font-semibold text-blue-600">
                            {{ $entrada->tempo_permanencia_texto }}
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Registrado por</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $entrada->user->name ?? 'N/A' }}
                        </dd>
                    </div>
                    
                    @if($entrada->autorizado_por)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Autorizado por</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $entrada->autorizadoPor->name ?? 'N/A' }}
                        </dd>
                    </div>
                    @endif
                    
                    @if($entrada->data_autorizacao)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Data de Autorização</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $entrada->data_autorizacao->format('d/m/Y H:i:s') }}
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    <!-- Informações Relacionadas -->
    @if($entrada->pacote || $entrada->prevenda)
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-box mr-2 text-purple-500"></i>
                Informações Relacionadas
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($entrada->pacote)
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-2">Pacote</h4>
                    <dl class="grid grid-cols-1 gap-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Descrição</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $entrada->pacote->descricao }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Valor</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $entrada->pacote->valor_formatado }}</dd>
                        </div>
                        @if($entrada->pacote->duracao)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Duração</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $entrada->pacote->duracao_formatada }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
                @endif
                
                @if($entrada->prevenda)
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-2">Pré-venda</h4>
                    <dl class="grid grid-cols-1 gap-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Código</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $entrada->prevenda->codigo }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Valor</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $entrada->prevenda->valor_formatado }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($entrada->prevenda->status === 'utilizada') bg-green-100 text-green-800
                                    @elseif($entrada->prevenda->status === 'pendente') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $entrada->prevenda->status_label }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const registrarSaidaBtn = document.getElementById('registrar-saida-btn');
    
    if (registrarSaidaBtn) {
        registrarSaidaBtn.addEventListener('click', function() {
            if (confirm('Deseja registrar a saída desta entrada? Esta ação atualizará o status para "Saiu" e calculará o tempo de permanência final.')) {
                fetch(`/admin/entradas/{{ $entrada->id }}/saida`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Saída registrada com sucesso!');
                        window.location.reload();
                    } else {
                        alert('Erro ao registrar saída: ' + (data.error || 'Erro desconhecido'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erro ao registrar saída');
                });
            }
        });
    }
});
</script>
@endpush
@endsection