@extends('admin.layout')

@section('title', 'Entradas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Entradas</h1>
            <p class="mt-1 text-sm text-gray-500">
                Gerencie todas as entradas do sistema de bilhetagem
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.entradas.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <i class="fas fa-plus -ml-1 mr-2 h-4 w-4"></i>
                Nova Entrada
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-filter mr-2"></i>
                Filtros
            </h3>
            <form method="GET" action="{{ route('admin.entradas.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                <!-- Evento -->
                <div>
                    <label for="evento_id" class="block text-sm font-medium text-gray-700">Evento</label>
                    <select name="evento_id" id="evento_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Todos os eventos</option>
                        @foreach($eventos as $evento)
                            <option value="{{ $evento->id }}" {{ request('evento_id') == $evento->id ? 'selected' : '' }}>
                                {{ $evento->titulo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Todos os status</option>
                        <option value="entrada" {{ request('status') == 'entrada' ? 'selected' : '' }}>Presente</option>
                        <option value="presente" {{ request('status') == 'presente' ? 'selected' : '' }}>Presente</option>
                        <option value="saida" {{ request('status') == 'saida' ? 'selected' : '' }}>Saiu</option>
                        <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>

                <!-- Tipo de Entrada -->
                <div>
                    <label for="tipo_entrada" class="block text-sm font-medium text-gray-700">Tipo</label>
                    <select name="tipo_entrada" id="tipo_entrada" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Todos os tipos</option>
                        <option value="individual" {{ request('tipo_entrada') == 'individual' ? 'selected' : '' }}>Individual</option>
                        <option value="pacote" {{ request('tipo_entrada') == 'pacote' ? 'selected' : '' }}>Pacote</option>
                        <option value="prevenda" {{ request('tipo_entrada') == 'prevenda' ? 'selected' : '' }}>Pré-venda</option>
                        <option value="cortesia" {{ request('tipo_entrada') == 'cortesia' ? 'selected' : '' }}>Cortesia</option>
                    </select>
                </div>

                <!-- Data Início -->
                <div>
                    <label for="data_inicio" class="block text-sm font-medium text-gray-700">Data Início</label>
                    <input type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Ações dos Filtros -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <i class="fas fa-search -ml-1 mr-2 h-4 w-4"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('admin.entradas.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-times -ml-1 mr-2 h-4 w-4"></i>
                        Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Estatísticas do Evento Selecionado -->
    @if($selectedEvento)
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-chart-bar mr-2"></i>
                Estatísticas - {{ $selectedEvento->titulo }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-blue-900" id="total-entradas">0</div>
                    <div class="text-sm text-blue-600">Total de Entradas</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-green-900" id="presentes-count">0</div>
                    <div class="text-sm text-green-600">Presentes</div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-gray-900" id="saidas-count">0</div>
                    <div class="text-sm text-gray-600">Saíram</div>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-purple-900" id="capacidade-info">0/{{ $selectedEvento->capacidade }}</div>
                    <div class="text-sm text-purple-600">Ocupação</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabela de Entradas -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-list mr-2"></i>
                Lista de Entradas
            </h3>
            
            <div class="overflow-x-auto">
                <div id="loading" class="text-center py-4 hidden">
                    <div class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-indigo-600 mr-2"></div>
                        Carregando entradas...
                    </div>
                </div>
                
                <div id="entradas-table">
                    @if(request()->hasAny(['evento_id', 'status', 'tipo_entrada', 'data_inicio']))
                        <p class="text-center text-gray-500 py-8">
                            <i class="fas fa-search text-2xl mb-2"></i><br>
                            Aplique os filtros para visualizar as entradas
                        </p>
                    @else
                        <p class="text-center text-gray-500 py-8">
                            <i class="fas fa-ticket-alt text-2xl mb-2"></i><br>
                            Selecione um evento para visualizar as entradas
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação para Registrar Saída -->
<div id="saida-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full mx-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-sign-out-alt mr-2 text-orange-500"></i>
                Confirmar Saída
            </h3>
            <p class="text-sm text-gray-600 mb-6">
                Deseja realmente registrar a saída desta entrada? Esta ação não pode ser desfeita.
            </p>
            <div class="flex space-x-3 justify-end">
                <button type="button" id="cancel-saida" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="button" id="confirm-saida" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
                    <i class="fas fa-check -ml-1 mr-2 h-4 w-4"></i>
                    Confirmar Saída
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentEntradaId = null;
    const saidaModal = document.getElementById('saida-modal');
    
    // Auto-submit on filter change
    const filterSelects = document.querySelectorAll('#evento_id, #status, #tipo_entrada, #data_inicio');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
    
    // Load entradas if event is selected
    const eventoId = document.getElementById('evento_id').value;
    if (eventoId) {
        loadEntradas();
        loadEventStats(eventoId);
    }
    
    function loadEntradas() {
        const formData = new FormData(document.querySelector('form'));
        const params = new URLSearchParams(formData).toString();
        
        document.getElementById('loading').classList.remove('hidden');
        
        fetch(`{{ route('admin.entradas.data') }}?${params}`)
            .then(response => response.json())
            .then(data => {
                renderEntradasTable(data.data);
                renderPagination(data.pagination);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('entradas-table').innerHTML = '<p class="text-center text-red-500 py-4">Erro ao carregar entradas</p>';
            })
            .finally(() => {
                document.getElementById('loading').classList.add('hidden');
            });
    }
    
    function renderEntradasTable(entradas) {
        if (!entradas || entradas.length === 0) {
            document.getElementById('entradas-table').innerHTML = '<p class="text-center text-gray-500 py-8">Nenhuma entrada encontrada</p>';
            return;
        }
        
        let html = `
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Evento</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsável</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entrada</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permanência</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
        `;
        
        entradas.forEach(entrada => {
            const statusColors = {
                entrada: 'bg-green-100 text-green-800',
                presente: 'bg-blue-100 text-blue-800', 
                saida: 'bg-gray-100 text-gray-800',
                cancelado: 'bg-red-100 text-red-800'
            };
            
            const tipoColors = {
                individual: 'bg-blue-100 text-blue-800',
                pacote: 'bg-purple-100 text-purple-800',
                prevenda: 'bg-green-100 text-green-800',
                cortesia: 'bg-yellow-100 text-yellow-800'
            };
            
            const statusColor = statusColors[entrada.status] || 'bg-gray-100 text-gray-800';
            const tipoColor = tipoColors[entrada.tipo_entrada] || 'bg-gray-100 text-gray-800';
            
            const tempoPermanencia = calculateTempoPermanencia(entrada);
            
            html += `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${entrada.evento ? entrada.evento.titulo : 'N/A'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${entrada.responsavel ? entrada.responsavel.nome : 'Anônimo'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${tipoColor}">
                            ${entrada.tipo_entrada}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        R$ ${parseFloat(entrada.valor_pago).toLocaleString('pt-BR', {minimumFractionDigits: 2})}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusColor}">
                            ${entrada.status === 'entrada' ? 'Presente' : entrada.status === 'presente' ? 'Presente' : entrada.status === 'saida' ? 'Saiu' : entrada.status}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${formatDateTime(entrada.data_entrada)}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${tempoPermanencia}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            ${(entrada.status === 'entrada' || entrada.status === 'presente') ? 
                                `<button onclick="showSaidaModal(${entrada.id})" class="text-orange-600 hover:text-orange-900">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>` : ''}
                            <a href="/admin/entradas/${entrada.id}/edit" class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/admin/entradas/${entrada.id}" class="text-gray-600 hover:text-gray-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            ${entrada.status !== 'saida' ? 
                                `<button onclick="deleteEntrada(${entrada.id})" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>` : ''}
                        </div>
                    </td>
                </tr>
            `;
        });
        
        html += '</tbody></table>';
        document.getElementById('entradas-table').innerHTML = html;
    }
    
    function calculateTempoPermanencia(entrada) {
        if (entrada.data_saida && entrada.data_entrada) {
            const entrada_date = new Date(entrada.data_entrada);
            const saida_date = new Date(entrada.data_saida);
            const minutos = Math.floor((saida_date - entrada_date) / (1000 * 60));
            const horas = Math.floor(minutos / 60);
            const mins = minutos % 60;
            return `${horas}h ${mins}min`;
        }
        
        if (entrada.status === 'entrada' || entrada.status === 'presente') {
            const entrada_date = new Date(entrada.data_entrada);
            const agora = new Date();
            const minutos = Math.floor((agora - entrada_date) / (1000 * 60));
            const horas = Math.floor(minutos / 60);
            const mins = minutos % 60;
            return `${horas}h ${mins}min (ativo)`;
        }
        
        return 'N/A';
    }
    
    function formatDateTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-BR') + ' ' + date.toLocaleTimeString('pt-BR', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    function renderPagination(pagination) {
        // TODO: Implementar paginação se necessário
    }
    
    function loadEventStats(eventoId) {
        fetch(`/admin/entradas/evento/${eventoId}/data`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-entradas').textContent = data.estatisticas.total_entradas;
                document.getElementById('presentes-count').textContent = data.estatisticas.presentes;
                document.getElementById('saidas-count').textContent = data.estatisticas.saidas;
                document.getElementById('capacidade-info').textContent = `${data.estatisticas.presentes}/${data.evento.capacidade}`;
            })
            .catch(error => {
                console.error('Error loading event stats:', error);
            });
    }
    
    // Modal functions
    window.showSaidaModal = function(entradaId) {
        currentEntradaId = entradaId;
        saidaModal.classList.remove('hidden');
    };
    
    document.getElementById('cancel-saida').addEventListener('click', function() {
        saidaModal.classList.add('hidden');
        currentEntradaId = null;
    });
    
    document.getElementById('confirm-saida').addEventListener('click', function() {
        if (currentEntradaId) {
            registrarSaida(currentEntradaId);
        }
    });
    
    function registrarSaida(entradaId) {
        fetch(`/admin/entradas/${entradaId}/saida`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                saidaModal.classList.add('hidden');
                loadEntradas(); // Reload table
                if (eventoId) loadEventStats(eventoId); // Update stats
            } else {
                alert('Erro ao registrar saída: ' + (data.error || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao registrar saída');
        });
    }
    
    window.deleteEntrada = function(entradaId) {
        if (confirm('Tem certeza que deseja excluir esta entrada?')) {
            fetch(`/admin/entradas/${entradaId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadEntradas(); // Reload table
                    if (eventoId) loadEventStats(eventoId); // Update stats
                } else {
                    alert('Erro ao excluir entrada: ' + (data.error || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao excluir entrada');
            });
        }
    };
});
</script>
@endpush
@endsection