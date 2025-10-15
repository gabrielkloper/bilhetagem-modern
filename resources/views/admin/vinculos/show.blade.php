@extends('admin.layout')

@section('title', 'Visualizar Vínculo')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $vinculo->descricao }}</h1>
            <p class="mt-1 text-sm text-gray-500">Detalhes do vínculo</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:flex-none space-x-3">
            <a href="{{ route('admin.vinculos.edit', $vinculo) }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                Editar
            </a>
            <a href="{{ route('admin.vinculos.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Voltar
            </a>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <!-- Basic Info -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Status</dt>
                            <dd class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $vinculo->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $vinculo->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usage Stats -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
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
    </div>

    <!-- Details -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Informações do Vínculo</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Detalhes e histórico do vínculo.</p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Descrição</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $vinculo->descricao }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $vinculo->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $vinculo->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Vinculados usando este tipo</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ number_format($stats['vinculados_count']) }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Responsáveis distintos</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ number_format($stats['responsaveis_count']) }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $vinculo->created_at->format('d/m/Y \à\s H:i:s') }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Última atualização</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $vinculo->updated_at->format('d/m/Y \à\s H:i:s') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Vinculados List -->
    @if($vinculo->vinculados->isNotEmpty())
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Vinculados com este tipo</h3>
            <p class="mt-1 text-sm text-gray-500">Lista dos vinculados que usam este tipo de vínculo</p>
        </div>

        <div class="overflow-x-auto">
            <div class="min-w-full divide-y divide-gray-200">
                <div class="bg-gray-50">
                    <div class="px-6 py-3 grid grid-cols-4 gap-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div>Nome</div>
                        <div>Responsável</div>
                        <div>Nascimento</div>
                        <div>Idade</div>
                    </div>
                </div>
                <div class="bg-white divide-y divide-gray-200">
                    @foreach($vinculo->vinculados->take(10) as $vinculado)
                    <div class="px-6 py-4 grid grid-cols-4 gap-4">
                        <div class="text-sm font-medium text-gray-900">{{ $vinculado->nome }}</div>
                        <div class="text-sm text-gray-500">
                            <a href="{{ route('admin.responsaveis.show', $vinculado->responsavel) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ $vinculado->responsavel->nome }}
                            </a>
                        </div>
                        <div class="text-sm text-gray-500">{{ $vinculado->nascimento->format('d/m/Y') }}</div>
                        <div class="text-sm text-gray-500">{{ $vinculado->nascimento->age }} anos</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if($vinculo->vinculados->count() > 10)
        <div class="px-6 py-3 bg-gray-50 text-sm text-gray-500 text-center">
            ... e mais {{ $vinculo->vinculados->count() - 10 }} vinculado(s)
        </div>
        @endif
    </div>
    @endif

    <!-- Actions -->
    <div class="flex justify-end space-x-3">
        @if($vinculo->vinculados->isEmpty())
        <button type="button" class="delete-btn inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" 
                data-url="{{ route('admin.vinculos.destroy', $vinculo) }}" data-name="{{ $vinculo->descricao }}">
            <!-- DEBUG: Generated URL is {{ route('admin.vinculos.destroy', $vinculo) }} -->
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Excluir Vínculo
        </button>
        @else
        <div class="text-sm text-gray-500 bg-gray-100 px-4 py-2 rounded-md">
            <svg class="inline h-4 w-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
            Não é possível excluir. Vínculo está sendo usado.
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete functionality
    const deleteBtn = document.querySelector('.delete-btn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            const deleteUrl = this.dataset.url;
            const name = this.dataset.name;
            
            console.log('DEBUG - Delete button clicked');
            console.log('DEBUG - this.dataset:', this.dataset);
            console.log('DEBUG - deleteUrl from dataset:', deleteUrl);
            console.log('DEBUG - Button element:', this);
            
            if (!deleteUrl) {
                SweetAlert.error('Erro!', 'URL de exclusão não encontrada. Recarregue a página e tente novamente.');
                return;
            }
            
            // Verificar se o vínculo ainda existe antes de mostrar confirmação
            // SweetAlert.loading('Verificando...', 'Aguarde um momento.');
            
            // console.log('DEBUG - About to make HEAD request to:', deleteUrl);
            
            fetch(deleteUrl, { method: 'HEAD' })
                .then(response => {
                    SweetAlert.close();
                    
                    if (response.status === 404 || response.redirected) {
                        SweetAlert.error('Vínculo não encontrado!', 'Este vínculo não existe mais ou já foi excluído.').then(() => {
                            window.location.href = '{{ route("admin.vinculos.index") }}';
                        });
                        return;
                    }
                    
                    // Se vínculo existe, mostrar confirmação
                    SweetAlert.confirmDelete(`o vínculo "${name}"`).then((result) => {
                        if (result.isConfirmed) {
                            SweetAlert.loading('Excluindo...', 'Aguarde enquanto o vínculo é excluído.');
                            
                            console.log('DEBUG - About to make DELETE request to:', deleteUrl);
                            console.log('DEBUG - Final deleteUrl before fetch:', deleteUrl);
                            
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            console.log('DEBUG - CSRF token:', csrfToken);
                            console.log('DEBUG - CSRF meta tag exists:', !!document.querySelector('meta[name="csrf-token"]'));
                            
                            const requestOptions = {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                }
                            };
                            console.log('DEBUG - Request options:', requestOptions);
                            
                            fetch(deleteUrl, requestOptions)
                            .then(response => {
                                SweetAlert.close();
                                
                                console.log('DEBUG - DELETE response received:', response);
                                console.log('DEBUG - Response URL:', response.url);
                                console.log('DEBUG - Response status:', response.status);
                                console.log('DEBUG - Response redirected:', response.redirected);
                                
                                if (!response.ok) {
                                    return response.json().then(errorData => {
                                        throw new Error(errorData.error || 'Erro ao excluir vínculo');
                                    }).catch(() => {
                                        throw new Error('Erro ao excluir vínculo');
                                    });
                                }
                                
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    SweetAlert.success('Sucesso!', 'Vínculo excluído com sucesso!').then(() => {
                                        window.location.href = '{{ route("admin.vinculos.index") }}';
                                    });
                                } else {
                                    SweetAlert.error('Erro!', data.error || 'Erro ao excluir vínculo');
                                }
                            })
                            .catch(error => {
                                SweetAlert.close();
                                SweetAlert.error('Erro!', error.message || 'Erro ao excluir vínculo');
                            });
                        }
                    });
                })
                .catch(error => {
                    SweetAlert.close();
                    SweetAlert.error('Erro!', 'Não foi possível verificar o vínculo. Tente novamente.');
                });
        });
    }
});
</script>
@endsection