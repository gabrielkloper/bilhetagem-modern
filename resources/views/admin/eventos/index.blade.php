@extends('admin.layout')

@section('title', 'Eventos')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Eventos</h1>
            <p class="mt-1 text-sm text-gray-500">
                Gerencie os eventos do sistema
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.eventos.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                <i class="fas fa-plus -ml-1 mr-2 h-4 w-4"></i>
                Novo Evento
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" action="{{ route('admin.eventos.index') }}" id="filters-form">
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Título do evento..." 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Todos</option>
                            <option value="ativo" {{ request('status') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ request('status') === 'inativo' ? 'selected' : '' }}>Inativo</option>
                            <option value="cancelado" {{ request('status') === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            <option value="finalizado" {{ request('status') === 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="publico" class="block text-sm font-medium text-gray-700">Tipo</label>
                        <select name="publico" id="publico" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Todos</option>
                            <option value="1" {{ request('publico') === '1' ? 'selected' : '' }}>Público</option>
                            <option value="0" {{ request('publico') === '0' ? 'selected' : '' }}>Privado</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="data_inicio" class="block text-sm font-medium text-gray-700">Data Início</label>
                        <input type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="mt-4 flex items-center space-x-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-search -ml-1 mr-2 h-4 w-4"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('admin.eventos.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-times -ml-1 mr-2 h-4 w-4"></i>
                        Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            @if($eventos->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Evento
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Período
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ocupação
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Características
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($eventos as $evento)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $evento->titulo }}</div>
                                        @if($evento->descricao)
                                            <div class="text-sm text-gray-500">{{ Str::limit($evento->descricao, 50) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($evento->data_inicio && $evento->data_fim)
                                        @php
                                            $inicio = $evento->data_inicio->format('d/m/Y');
                                            $fim = $evento->data_fim->format('d/m/Y');
                                        @endphp
                                        {{ $inicio === $fim ? $inicio : $inicio . ' a ' . $fim }}
                                    @else
                                        <span class="text-gray-400">Não informado</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $ocupados = $evento->entradas()->where('status', 'ativo')->whereNull('datahora_saida')->count();
                                        $percentual = $evento->capacidade > 0 ? round(($ocupados / $evento->capacidade) * 100, 1) : 0;
                                        $colorClass = $percentual >= 90 ? 'text-red-600' : ($percentual >= 70 ? 'text-yellow-600' : 'text-green-600');
                                    @endphp
                                    <span class="{{ $colorClass }} font-medium">
                                        {{ number_format($ocupados) }}/{{ number_format($evento->capacidade) }} ({{ $percentual }}%)
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-1">
                                        @if($evento->publico)
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Público</span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Privado</span>
                                        @endif
                                        
                                        @if($evento->permite_prevenda)
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">Prevenda</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.eventos.show', $evento) }}" 
                                           class="text-indigo-600 hover:text-indigo-900" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.eventos.edit', $evento) }}" 
                                           class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(!$evento->entradas()->exists() && !$evento->prevendas()->exists())
                                            <button onclick="deleteEvento({{ $evento->id }})" 
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
                    {{ $eventos->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum evento encontrado</h3>
                    <p class="text-gray-500 mb-4">
                        @if(request()->anyFilled(['search', 'status', 'publico', 'data_inicio']))
                            Nenhum evento corresponde aos filtros aplicados.
                        @else
                            Ainda não há eventos cadastrados no sistema.
                        @endif
                    </p>
                    @if(!request()->anyFilled(['search', 'status', 'publico', 'data_inicio']))
                        <a href="{{ route('admin.eventos.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <i class="fas fa-plus -ml-1 mr-2 h-4 w-4"></i>
                            Criar Primeiro Evento
                        </a>
                    @else
                        <a href="{{ route('admin.eventos.index') }}" 
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
// Delete evento function
function deleteEvento(id) {
    SweetAlert.confirmDelete('este evento').then((result) => {
        if (result.isConfirmed) {
            SweetAlert.loading('Excluindo evento...', 'Por favor, aguarde.');
            
            fetch(`/admin/eventos/${id}`, {
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
                        'Evento excluído!',
                        data.success
                    ).then(() => {
                        window.location.reload();
                    });
                } else {
                    SweetAlert.error(
                        'Erro ao excluir!',
                        data.error || 'Não foi possível excluir o evento.'
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
</script>
@endpush
@endsection