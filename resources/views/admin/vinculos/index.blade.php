@extends('admin.layout')

@section('title', 'Gestão de Vínculos')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestão de Vínculos</h1>
            <p class="mt-1 text-sm text-gray-500">Gerencie os tipos de vínculos disponíveis no sistema</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:flex-none">
            <a href="{{ route('admin.vinculos.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Novo Vínculo
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total</dt>
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
                        <svg class="h-8 w-8 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
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
                        <svg class="h-8 w-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Inativos</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ number_format($stats['inativos'] ?? 0) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

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
                            <dt class="text-sm font-medium text-gray-500 truncate">Em Uso</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ number_format($stats['em_uso'] ?? 0) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" class="space-y-4">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="sm:flex sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                        <!-- Search -->
                        <div class="flex-1 min-w-0">
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Buscar por descrição...">
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="min-w-0">
                            <select name="status" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Status</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Ativo</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inativo</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 sm:mt-0 sm:flex-none">
                        <button type="submit" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                            Filtrar
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.vinculos.index') }}" class="ml-3 inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                                Limpar
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="min-w-full divide-y divide-gray-200">
            <div class="bg-gray-50">
                <div class="px-6 py-3 grid grid-cols-12 gap-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <div class="col-span-1">
                        <input type="checkbox" id="select-all" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    </div>
                    <div class="col-span-4">Descrição</div>
                    <div class="col-span-2">Status</div>
                    <div class="col-span-2">Em Uso</div>
                    <div class="col-span-2">Criado em</div>
                    <div class="col-span-1">Ações</div>
                </div>
            </div>
            <div class="bg-white divide-y divide-gray-200">
                @forelse($vinculos as $vinculo)
                <div class="px-6 py-4 grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-1">
                        <input type="checkbox" name="selected_vinculos[]" value="{{ $vinculo->id }}" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded vinculo-checkbox">
                    </div>
                    <div class="col-span-4">
                        <div class="text-sm font-medium text-gray-900">{{ $vinculo->descricao }}</div>
                    </div>
                    <div class="col-span-2">
                        <button type="button" class="status-toggle" data-id="{{ $vinculo->id }}" data-status="{{ $vinculo->ativo }}">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $vinculo->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $vinculo->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </button>
                    </div>
                    <div class="col-span-2">
                        <span class="text-sm text-gray-500">{{ $vinculo->vinculados_count ?? 0 }} vinculados</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-sm text-gray-500">{{ $vinculo->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="col-span-1">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.vinculos.edit', $vinculo) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                Editar
                            </a>
                            {{-- <button type="button" class="delete-btn text-red-600 hover:text-red-900 text-sm font-medium" data-url="{{ route('admin.vinculos.destroy', $vinculo) }}" data-name="{{ $vinculo->descricao }}">
                                Excluir
                            </button> --}}
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-4 text-center text-sm text-gray-500">
                    Nenhum vínculo encontrado.
                </div>
                @endforelse
            </div>
        </div>

        @if($vinculos->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $vinculos->links() }}
        </div>
        @endif
    </div>

    <!-- Bulk Actions -->
    <div id="bulk-actions" class="hidden fixed inset-x-0 bottom-0 pb-2 sm:pb-5">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="p-2 rounded-lg bg-indigo-600 shadow-lg sm:p-3">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="w-0 flex-1 flex items-center">
                        <span class="flex p-2 rounded-lg bg-indigo-800">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <p class="ml-3 font-medium text-white truncate">
                            <span id="selected-count">0</span> vínculo(s) selecionado(s)
                        </p>
                    </div>
                    <div class="order-3 mt-2 flex-shrink-0 w-full sm:order-2 sm:mt-0 sm:w-auto">
                        <div class="flex space-x-2">
                            <button type="button" id="bulk-activate" class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-indigo-50">
                                Ativar
                            </button>
                            <button type="button" id="bulk-deactivate" class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-indigo-50">
                                Desativar
                            </button>
                            <button type="button" id="bulk-delete" class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-red-600 bg-white hover:bg-red-50">
                                Excluir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all functionality
    const selectAllCheckbox = document.getElementById('select-all');
    const vinculoCheckboxes = document.querySelectorAll('.vinculo-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    
    function updateBulkActions() {
        const checked = document.querySelectorAll('.vinculo-checkbox:checked');
        if (checked.length > 0) {
            bulkActions.classList.remove('hidden');
            selectedCount.textContent = checked.length;
        } else {
            bulkActions.classList.add('hidden');
        }
    }
    
    selectAllCheckbox.addEventListener('change', function() {
        vinculoCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });
    
    vinculoCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
    
    // Status toggle
    document.querySelectorAll('.status-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const currentStatus = this.dataset.status === '1';
            
            fetch(`/admin/vinculos/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    SweetAlert.success('Sucesso!', 'Status alterado com sucesso!').then(() => {
                        location.reload();
                    });
                } else {
                    SweetAlert.error('Erro!', data.error || 'Erro ao alterar status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                SweetAlert.error('Erro!', 'Erro ao alterar status');
            });
        });
    });
    
    // Delete functionality
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const deleteUrl = this.dataset.url;
            const name = this.dataset.name;
            
            if (!deleteUrl) {
                SweetAlert.error('Erro!', 'URL de exclusão não encontrada. Recarregue a página e tente novamente.');
                return;
            }
            
            // Verificar se o vínculo ainda existe antes de mostrar confirmação
            SweetAlert.loading('Verificando...', 'Aguarde um momento.');
            
            fetch(deleteUrl, { method: 'HEAD' })
                .then(response => {
                    SweetAlert.close();
                    
                    if (response.status === 404 || response.redirected) {
                        SweetAlert.error('Vínculo não encontrado!', 'Este vínculo não existe mais ou já foi excluído. A página será recarregada.').then(() => {
                            location.reload();
                        });
                        return;
                    }
                    
                    // Se vínculo existe, mostrar confirmação
                    SweetAlert.confirmDelete(`o vínculo "${name}"`).then((result) => {
                        if (result.isConfirmed) {
                            SweetAlert.loading('Excluindo...', 'Aguarde enquanto o vínculo é excluído.');
                            
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                }
                            })
                            .then(response => {
                                SweetAlert.close();
                                
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
                                        location.reload();
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
    });
    
    // Bulk actions
    function bulkAction(action) {
        const selected = Array.from(document.querySelectorAll('.vinculo-checkbox:checked')).map(cb => cb.value);
        
        if (selected.length === 0) {
            SweetAlert.warning('Atenção!', 'Selecione ao menos um vínculo');
            return;
        }
        
        let confirmMessage = '';
        switch(action) {
            case 'activate':
                confirmMessage = `Ativar ${selected.length} vínculo(s)?`;
                break;
            case 'deactivate':
                confirmMessage = `Desativar ${selected.length} vínculo(s)?`;
                break;
            case 'delete':
                confirmMessage = `Excluir ${selected.length} vínculo(s)? Esta ação não pode ser desfeita.`;
                break;
        }
        
        SweetAlert.confirm('Confirmar ação', confirmMessage).then((result) => {
            if (result.isConfirmed) {
                fetch('/admin/vinculos/bulk-action', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        action: action,
                        vinculos: selected
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        SweetAlert.success('Sucesso!', data.success).then(() => {
                            location.reload();
                        });
                    } else {
                        SweetAlert.error('Erro!', data.error || 'Erro ao processar ação');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    SweetAlert.error('Erro!', 'Erro ao processar ação');
                });
            }
        });
    }
    
    document.getElementById('bulk-activate').addEventListener('click', () => bulkAction('activate'));
    document.getElementById('bulk-deactivate').addEventListener('click', () => bulkAction('deactivate'));
    document.getElementById('bulk-delete').addEventListener('click', () => bulkAction('delete'));
});
</script>
@endsection