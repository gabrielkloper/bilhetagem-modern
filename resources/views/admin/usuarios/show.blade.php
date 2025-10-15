@extends('admin.layout')

@section('title', 'Visualizar Usuário')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $usuario->name }}</h1>
            <p class="mt-1 text-sm text-gray-500">
                Informações detalhadas do usuário
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-3">
            <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                <i class="fas fa-edit -ml-1 mr-2 h-4 w-4"></i>
                Editar
            </a>
            <a href="{{ route('admin.usuarios.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                <i class="fas fa-arrow-left -ml-1 mr-2 h-4 w-4"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Básicas</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nome</dt>
                            <dd class="text-sm text-gray-900">{{ $usuario->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">E-mail</dt>
                            <dd class="text-sm text-gray-900">{{ $usuario->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Função</dt>
                            <dd class="text-sm">
                                @php
                                    $colors = [
                                        'admin' => 'bg-red-100 text-red-800',
                                        'supervisor' => 'bg-purple-100 text-purple-800',
                                        'operador' => 'bg-blue-100 text-blue-800',
                                        'caixa' => 'bg-green-100 text-green-800',
                                    ];
                                    $color = $colors[$usuario->role] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                    {{ $usuario->role_label }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="text-sm">
                                @php
                                    $colors = [
                                        'ativo' => 'bg-green-100 text-green-800',
                                        'inativo' => 'bg-gray-100 text-gray-800',
                                        'suspenso' => 'bg-yellow-100 text-yellow-800',
                                        'bloqueado' => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $colors[$usuario->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                    {{ $usuario->status_label }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- System Info -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Sistema</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Evento vinculado</dt>
                            <dd class="text-sm text-gray-900">
                                {{ $usuario->evento ? $usuario->evento->titulo : 'Todos os eventos' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">E-mail verificado</dt>
                            <dd class="text-sm">
                                @if($usuario->email_verified_at)
                                    <span class="text-green-600">
                                        <i class="fas fa-check-circle"></i> 
                                        {{ $usuario->email_verified_at->format('d/m/Y H:i') }}
                                    </span>
                                @else
                                    <span class="text-red-600">
                                        <i class="fas fa-times-circle"></i> 
                                        Não verificado
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                            <dd class="text-sm text-gray-900">{{ $usuario->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Última atualização</dt>
                            <dd class="text-sm text-gray-900">{{ $usuario->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Log (if needed in future) -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Atividade Recente</h3>
            <div class="text-sm text-gray-500 text-center py-8">
                <i class="fas fa-clock text-gray-300 text-3xl mb-2"></i>
                <p>Histórico de atividades será implementado em breve</p>
            </div>
        </div>
    </div>
</div>
@endsection