@extends('admin.layout')

@section('title', 'Usuários')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Usuários</h1>
            <p class="mt-1 text-sm text-gray-500">
                Gerencie os usuários do sistema
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-3">
            <div class="flex items-center space-x-3">
                <button id="bulk-action-btn" class="hidden inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                    <i class="fas fa-users -ml-1 mr-2 h-4 w-4"></i>
                    Ações em lote
                </button>
                <a href="{{ route('admin.usuarios.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                    <i class="fas fa-plus -ml-1 mr-2 h-4 w-4"></i>
                    Novo Usuário
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" action="{{ route('admin.usuarios.index') }}" id="filters-form">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Nome ou e-mail..." 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Função</label>
                        <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Todas</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="supervisor" {{ request('role') === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                            <option value="operador" {{ request('role') === 'operador' ? 'selected' : '' }}>Operador</option>
                            <option value="caixa" {{ request('role') === 'caixa' ? 'selected' : '' }}>Caixa</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Todos</option>
                            <option value="ativo" {{ request('status') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ request('status') === 'inativo' ? 'selected' : '' }}>Inativo</option>
                            <option value="suspenso" {{ request('status') === 'suspenso' ? 'selected' : '' }}>Suspenso</option>
                            <option value="bloqueado" {{ request('status') === 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="evento_id" class="block text-sm font-medium text-gray-700">Evento</label>
                        <select name="evento_id" id="evento_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Todos</option>
                            @foreach($eventos as $evento)
                                <option value="{{ $evento->id }}" {{ request('evento_id') == $evento->id ? 'selected' : '' }}>
                                    {{ $evento->titulo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex items-center space-x-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-search -ml-1 mr-2 h-4 w-4"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('admin.usuarios.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-times -ml-1 mr-2 h-4 w-4"></i>
                        Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div id="bulk-actions" class="hidden bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                <span id="selected-count" class="text-sm text-yellow-800"></span>
            </div>
            <div class="flex items-center space-x-3">
                <select id="bulk-status" class="rounded-md border-yellow-300 shadow-sm text-sm">
                    <option value="">Alterar status para...</option>
                    <option value="ativo">Ativo</option>
                    <option value="inativo">Inativo</option>
                    <option value="suspenso">Suspenso</option>
                    <option value="bloqueado">Bloqueado</option>
                </select>
                <button id="apply-bulk" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
                    Aplicar
                </button>
                <button id="cancel-bulk" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    <!-- Tabela -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            @if($usuarios->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nome
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    E-mail
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Função
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Evento
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Criado em
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($usuarios as $usuario)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" class="user-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" 
                                           value="{{ $usuario->id }}" data-id="{{ $usuario->id }}">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $usuario->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $usuario->email }}</div>
                                    @if($usuario->email_verified_at)
                                        <div class="text-xs text-green-600">Verificado</div>
                                    @else
                                        <div class="text-xs text-red-600">Não verificado</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $roleColors = [
                                            'admin' => 'bg-red-100 text-red-800',
                                            'supervisor' => 'bg-purple-100 text-purple-800',
                                            'operador' => 'bg-blue-100 text-blue-800',
                                            'caixa' => 'bg-green-100 text-green-800',
                                        ];
                                        $roleColor = $roleColors[$usuario->role] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $roleColor }}">
                                        {{ $usuario->role_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'ativo' => 'bg-green-100 text-green-800',
                                            'inativo' => 'bg-gray-100 text-gray-800',
                                            'suspenso' => 'bg-yellow-100 text-yellow-800',
                                            'bloqueado' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusColor = $statusColors[$usuario->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                                        {{ $usuario->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $usuario->evento ? $usuario->evento->titulo : 'Todos' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $usuario->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.usuarios.show', $usuario) }}" 
                                           class="text-indigo-600 hover:text-indigo-900" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.usuarios.edit', $usuario) }}" 
                                           class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @php
                                            $canDelete = $usuario->id !== auth()->id() && !($usuario->role === 'admin' && App\Models\User::where('role', 'admin')->count() <= 1);
                                        @endphp
                                        @if($canDelete)
                                            <button onclick="deleteUser({{ $usuario->id }})" 
                                                    class="text-red-600 hover:text-red-900" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginação -->
                <div class="mt-6">
                    {{ $usuarios->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum usuário encontrado</h3>
                    <p class="text-gray-500 mb-4">
                        @if(request()->anyFilled(['search', 'role', 'status', 'evento_id']))
                            Nenhum usuário corresponde aos filtros aplicados.
                        @else
                            Ainda não há usuários cadastrados no sistema.
                        @endif
                    </p>
                    @if(!request()->anyFilled(['search', 'role', 'status', 'evento_id']))
                        <a href="{{ route('admin.usuarios.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <i class="fas fa-plus -ml-1 mr-2 h-4 w-4"></i>
                            Criar Primeiro Usuário
                        </a>
                    @else
                        <a href="{{ route('admin.usuarios.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-times -ml-1 mr-2 h-4 w-4"></i>
                            Limpar Filtros
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Bulk actions
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const bulkActionBtn = document.getElementById('bulk-action-btn');
    const selectedCount = document.getElementById('selected-count');
    const applyBulk = document.getElementById('apply-bulk');
    const cancelBulk = document.getElementById('cancel-bulk');
    const bulkStatus = document.getElementById('bulk-status');

    function updateBulkUI() {
        const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            bulkActions.classList.remove('hidden');
            bulkActionBtn.classList.remove('hidden');
            selectedCount.textContent = `${count} usuário(s) selecionado(s)`;
        } else {
            bulkActions.classList.add('hidden');
            bulkActionBtn.classList.add('hidden');
        }
        
        selectAll.indeterminate = count > 0 && count < userCheckboxes.length;
        selectAll.checked = count === userCheckboxes.length;
    }

    selectAll.addEventListener('change', function() {
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkUI();
    });

    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkUI);
    });

    cancelBulk.addEventListener('click', function() {
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectAll.checked = false;
        updateBulkUI();
    });

    applyBulk.addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
        const status = bulkStatus.value;
        
        if (!status) {
            SweetAlert.warning('Atenção!', 'Selecione um status para aplicar.');
            return;
        }
        
        if (selectedIds.length === 0) {
            SweetAlert.warning('Atenção!', 'Selecione pelo menos um usuário.');
            return;
        }
        
        SweetAlert.confirm(
            'Confirmar alteração?',
            `Alterar status de ${selectedIds.length} usuário(s) para "${status}"?`
        ).then((result) => {
            if (result.isConfirmed) {
                bulkUpdateStatus(selectedIds, status);
            }
        });
    });
});

// Delete user function
function deleteUser(id) {
    SweetAlert.confirmDelete('este usuário').then((result) => {
        if (result.isConfirmed) {
            SweetAlert.loading('Excluindo usuário...', 'Por favor, aguarde.');
            
            fetch(`/admin/usuarios/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    SweetAlert.success(
                        'Usuário excluído!',
                        data.success
                    ).then(() => {
                        window.location.reload();
                    });
                } else {
                    SweetAlert.error(
                        'Erro ao excluir!',
                        data.error || 'Não foi possível excluir o usuário.'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                SweetAlert.error(
                    'Erro ao excluir!',
                    'Ocorreu um erro inesperado.'
                );
            });
        }
    });
}

// Bulk update status function
function bulkUpdateStatus(ids, status) {
    SweetAlert.loading('Atualizando status...', 'Por favor, aguarde.');
    
    fetch('/admin/usuarios/bulk-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            ids: ids,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            SweetAlert.success(
                'Status atualizado!',
                data.success
            ).then(() => {
                window.location.reload();
            });
        } else {
            SweetAlert.error(
                'Erro ao atualizar!',
                data.error || 'Não foi possível atualizar o status.'
            );
        }
    })
    .catch(error => {
        console.error('Error:', error);
        SweetAlert.error(
            'Erro ao atualizar!',
            'Ocorreu um erro inesperado.'
        );
    });
}
</script>
@endpush
@endsection