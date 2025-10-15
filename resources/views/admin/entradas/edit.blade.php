@extends('admin.layout')

@section('title', 'Editar Entrada')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Editar Entrada</h1>
            <p class="mt-1 text-sm text-gray-500">
                Edite os dados da entrada no sistema de bilhetagem
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.entradas.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                <i class="fas fa-arrow-left -ml-1 mr-2 h-4 w-4"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Entry Info -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-info-circle mr-2"></i>
                Informações da Entrada
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">ID:</span>
                    <span class="font-semibold">#{{ $entrada->id }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Data de Entrada:</span>
                    <span class="font-semibold">{{ $entrada->data_entrada->format('d/m/Y H:i') }}</span>
                </div>
                @if($entrada->data_saida)
                <div>
                    <span class="text-gray-500">Data de Saída:</span>
                    <span class="font-semibold">{{ $entrada->data_saida->format('d/m/Y H:i') }}</span>
                </div>
                @endif
                <div>
                    <span class="text-gray-500">Permanência:</span>
                    <span class="font-semibold">{{ $entrada->tempo_permanencia_texto }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Registrado por:</span>
                    <span class="font-semibold">{{ $entrada->user->name ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Status Atual:</span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        @if($entrada->status === 'entrada' || $entrada->status === 'presente') bg-green-100 text-green-800
                        @elseif($entrada->status === 'saida') bg-gray-100 text-gray-800
                        @elseif($entrada->status === 'cancelado') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $entrada->status_label }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.entradas.update', $entrada) }}" method="POST" class="bg-white shadow rounded-lg">
        @csrf
        @method('PUT')
        
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">
                <i class="fas fa-edit mr-2"></i>
                Editar Dados da Entrada
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Evento -->
                <div>
                    <label for="evento_id" class="block text-sm font-medium text-gray-700">Evento</label>
                    <select name="evento_id" id="evento_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        <option value="">Selecione um evento</option>
                        @foreach($eventos as $evento)
                            <option value="{{ $evento->id }}" {{ old('evento_id', $entrada->evento_id) == $evento->id ? 'selected' : '' }}>
                                {{ $evento->titulo }}
                            </option>
                        @endforeach
                    </select>
                    @error('evento_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Tipo de Entrada -->
                <div>
                    <label for="tipo_entrada" class="block text-sm font-medium text-gray-700">Tipo de Entrada</label>
                    <select name="tipo_entrada" id="tipo_entrada" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        <option value="">Selecione o tipo</option>
                        <option value="individual" {{ old('tipo_entrada', $entrada->tipo_entrada) === 'individual' ? 'selected' : '' }}>Individual</option>
                        <option value="pacote" {{ old('tipo_entrada', $entrada->tipo_entrada) === 'pacote' ? 'selected' : '' }}>Pacote</option>
                        <option value="prevenda" {{ old('tipo_entrada', $entrada->tipo_entrada) === 'prevenda' ? 'selected' : '' }}>Pré-venda</option>
                        <option value="cortesia" {{ old('tipo_entrada', $entrada->tipo_entrada) === 'cortesia' ? 'selected' : '' }}>Cortesia</option>
                    </select>
                    @error('tipo_entrada')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Responsável -->
                <div>
                    <label for="responsavel_id" class="block text-sm font-medium text-gray-700">Responsável</label>
                    <select name="responsavel_id" id="responsavel_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Anônimo</option>
                        @if($entrada->evento)
                            @foreach($entrada->evento->responsaveis()->where('ativo', true)->get() as $responsavel)
                                <option value="{{ $responsavel->id }}" {{ old('responsavel_id', $entrada->responsavel_id) == $responsavel->id ? 'selected' : '' }}>
                                    {{ $responsavel->nome }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('responsavel_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Pacote (se aplicável) -->
                <div id="pacote-container" class="{{ old('tipo_entrada', $entrada->tipo_entrada) === 'pacote' ? '' : 'hidden' }}">
                    <label for="pacote_id" class="block text-sm font-medium text-gray-700">Pacote</label>
                    <select name="pacote_id" id="pacote_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Selecione um pacote</option>
                        @if($entrada->evento)
                            @foreach($entrada->evento->pacotes()->where('ativo', true)->get() as $pacote)
                                <option value="{{ $pacote->id }}" {{ old('pacote_id', $entrada->pacote_id) == $pacote->id ? 'selected' : '' }}>
                                    {{ $pacote->descricao }} - R$ {{ number_format($pacote->valor, 2, ',', '.') }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('pacote_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Pré-venda (se aplicável) -->
                <div id="prevenda-container" class="{{ old('tipo_entrada', $entrada->tipo_entrada) === 'prevenda' ? '' : 'hidden' }}">
                    <label for="prevenda_id" class="block text-sm font-medium text-gray-700">Pré-venda</label>
                    <select name="prevenda_id" id="prevenda_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Selecione uma pré-venda</option>
                        @if($entrada->evento)
                            @foreach($entrada->evento->prevendas()->where('status', 'pendente')->get() as $prevenda)
                                <option value="{{ $prevenda->id }}" {{ old('prevenda_id', $entrada->prevenda_id) == $prevenda->id ? 'selected' : '' }}>
                                    {{ $prevenda->codigo }} - R$ {{ number_format($prevenda->valor, 2, ',', '.') }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('prevenda_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Valor Pago -->
                <div>
                    <label for="valor_pago" class="block text-sm font-medium text-gray-700">Valor Pago (R$)</label>
                    <input type="text" 
                           name="valor_pago" 
                           id="valor_pago" 
                           value="{{ old('valor_pago', number_format($entrada->valor_pago, 2, ',', '.')) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                           placeholder="0,00"
                           onkeyup="formatMoeda(this)"
                           required>
                    @error('valor_pago')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Forma de Pagamento -->
                <div>
                    <label for="forma_pagamento" class="block text-sm font-medium text-gray-700">Forma de Pagamento</label>
                    <select name="forma_pagamento" id="forma_pagamento" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        <option value="">Selecione</option>
                        <option value="dinheiro" {{ old('forma_pagamento', $entrada->forma_pagamento) === 'dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                        <option value="cartao" {{ old('forma_pagamento', $entrada->forma_pagamento) === 'cartao' ? 'selected' : '' }}>Cartão</option>
                        <option value="pix" {{ old('forma_pagamento', $entrada->forma_pagamento) === 'pix' ? 'selected' : '' }}>PIX</option>
                        <option value="transferencia" {{ old('forma_pagamento', $entrada->forma_pagamento) === 'transferencia' ? 'selected' : '' }}>Transferência</option>
                        <option value="gratuito" {{ old('forma_pagamento', $entrada->forma_pagamento) === 'gratuito' ? 'selected' : '' }}>Gratuito</option>
                    </select>
                    @error('forma_pagamento')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        <option value="entrada" {{ old('status', $entrada->status) === 'entrada' ? 'selected' : '' }}>Presente (Entrada)</option>
                        <option value="presente" {{ old('status', $entrada->status) === 'presente' ? 'selected' : '' }}>Presente</option>
                        <option value="saida" {{ old('status', $entrada->status) === 'saida' ? 'selected' : '' }}>Saiu</option>
                        <option value="cancelado" {{ old('status', $entrada->status) === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Observações -->
                <div class="md:col-span-2 lg:col-span-3">
                    <label for="observacoes" class="block text-sm font-medium text-gray-700">Observações</label>
                    <textarea name="observacoes" id="observacoes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Informações adicionais (opcional)">{{ old('observacoes', $entrada->observacoes) }}</textarea>
                    @error('observacoes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Actions -->
            <div class="mt-6 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    @if($entrada->podeRegistrarSaida())
                        <button type="button" id="registrar-saida-btn" class="inline-flex items-center px-4 py-2 border border-orange-300 shadow-sm text-sm font-medium rounded-md text-orange-700 bg-orange-50 hover:bg-orange-100">
                            <i class="fas fa-sign-out-alt -ml-1 mr-2 h-4 w-4"></i>
                            Registrar Saída
                        </button>
                    @endif
                </div>
                
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.entradas.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save -ml-1 mr-2 h-4 w-4"></i>
                        Salvar Alterações
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Brazilian Real currency formatting
    function formatMoeda(input) {
        let valor = input.value.replace(/\D/g, '');
        if (valor === '') {
            input.value = '';
            return;
        }
        
        valor = (parseInt(valor) / 100).toFixed(2);
        valor = valor.replace('.', ',');
        valor = valor.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        input.value = valor;
    }
    
    // Make formatMoeda available globally
    window.formatMoeda = formatMoeda;
    
    // Tipo entrada change handler
    document.getElementById('tipo_entrada').addEventListener('change', function() {
        const tipo = this.value;
        
        // Hide all conditional fields
        document.getElementById('pacote-container').classList.add('hidden');
        document.getElementById('prevenda-container').classList.add('hidden');
        
        // Show relevant fields
        if (tipo === 'pacote') {
            document.getElementById('pacote-container').classList.remove('hidden');
        } else if (tipo === 'prevenda') {
            document.getElementById('prevenda-container').classList.remove('hidden');
        }
        
        // Auto-set valor for cortesia
        if (tipo === 'cortesia') {
            document.getElementById('valor_pago').value = '0,00';
        }
    });
    
    // Registrar saída button
    const registrarSaidaBtn = document.getElementById('registrar-saida-btn');
    if (registrarSaidaBtn) {
        registrarSaidaBtn.addEventListener('click', function() {
            if (confirm('Deseja registrar a saída desta entrada? Esta ação atualizará o status para "Saiu" e não poderá ser desfeita.')) {
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
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const valorPago = document.getElementById('valor_pago').value;
        if (!valorPago || valorPago === '0,00') {
            const tipoEntrada = document.getElementById('tipo_entrada').value;
            if (tipoEntrada !== 'cortesia') {
                alert('Por favor, informe o valor pago para este tipo de entrada.');
                e.preventDefault();
                return false;
            }
        }
        
        // Convert currency format back to decimal for submission
        const valorInput = document.getElementById('valor_pago');
        if (valorInput.value) {
            const cleanValue = valorInput.value.replace(/\./g, '').replace(',', '.');
            
            // Create a hidden field for the clean value
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'valor_pago_clean';
            hiddenInput.value = cleanValue;
            this.appendChild(hiddenInput);
        }
    });
});
</script>
@endpush
@endsection