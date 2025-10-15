@extends('admin.layout')

@section('title', 'Cadastrar Responsável')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Cadastrar Responsável</h1>
            <p class="mt-1 text-sm text-gray-500">
                @if($eventoId)
                    @php
                        $evento = $eventos->firstWhere('id', $eventoId);
                    @endphp
                    Cadastrando responsável para o evento "{{ $evento ? $evento->titulo : 'Evento não encontrado' }}"
                @else
                    Cadastrar um novo responsável no sistema
                @endif
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.responsaveis.index', $eventoId ? ['evento_id' => $eventoId] : []) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Voltar
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form method="POST" action="{{ route('admin.responsaveis.store') }}" class="space-y-6 p-6">
            @csrf

            <!-- Event Selection (if not pre-selected) -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-1">
                <div>
                    <label for="evento_id" class="block text-sm font-medium text-gray-700">Evento *</label>
                    <select name="evento_id" id="evento_id" required 
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md @error('evento_id') border-red-300 @enderror">
                        <option value="">Selecione um evento...</option>
                        @foreach($eventos as $evento)
                            <option value="{{ $evento->id }}" {{ old('evento_id', $eventoId) == $evento->id ? 'selected' : '' }}>
                                {{ $evento->titulo }} - {{ $evento->data_inicio->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                    @error('evento_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Personal Information -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Dados Pessoais</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700">Nome Completo *</label>
                        <input type="text" name="nome" id="nome" required 
                               value="{{ old('nome') }}"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('nome') border-red-300 @enderror"
                               placeholder="Nome completo do responsável">
                        @error('nome')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cpf" class="block text-sm font-medium text-gray-700">CPF *</label>
                        <input type="text" name="cpf" id="cpf" required 
                               value="{{ old('cpf') }}"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('cpf') border-red-300 @enderror"
                               placeholder="000.000.000-00" maxlength="14">
                        @error('cpf')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">E-mail *</label>
                        <input type="email" name="email" id="email" required 
                               value="{{ old('email') }}"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('email') border-red-300 @enderror"
                               placeholder="email@exemplo.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nascimento" class="block text-sm font-medium text-gray-700">Data de Nascimento *</label>
                        <input type="date" name="nascimento" id="nascimento" required 
                               value="{{ old('nascimento') }}"
                               max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('nascimento') border-red-300 @enderror">
                        @error('nascimento')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telefone1" class="block text-sm font-medium text-gray-700">Telefone Principal *</label>
                        <input type="tel" name="telefone1" id="telefone1" required 
                               value="{{ old('telefone1') }}"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('telefone1') border-red-300 @enderror"
                               placeholder="(00) 00000-0000">
                        @error('telefone1')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telefone2" class="block text-sm font-medium text-gray-700">Telefone Alternativo</label>
                        <input type="tel" name="telefone2" id="telefone2" 
                               value="{{ old('telefone2') }}"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('telefone2') border-red-300 @enderror"
                               placeholder="(00) 00000-0000">
                        @error('telefone2')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Communication Settings -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Configurações de Comunicação</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <div class="flex items-center">
                            <input id="comunica" name="comunica" type="checkbox" 
                                   {{ old('comunica') ? 'checked' : '' }}
                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="comunica" class="ml-2 block text-sm text-gray-900">
                                Aceita receber comunicações
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Marque se o responsável autoriza o recebimento de notificações e comunicados
                        </p>
                        @error('comunica')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="device_comunica" class="block text-sm font-medium text-gray-700">Forma de Comunicação *</label>
                        <select name="device_comunica" id="device_comunica" required 
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md @error('device_comunica') border-red-300 @enderror">
                            <option value="email" {{ old('device_comunica') == 'email' ? 'selected' : '' }}>E-mail</option>
                            <option value="sms" {{ old('device_comunica') == 'sms' ? 'selected' : '' }}>SMS</option>
                            <option value="whatsapp" {{ old('device_comunica') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                            <option value="todos" {{ old('device_comunica') == 'todos' ? 'selected' : '' }}>Todos os meios</option>
                        </select>
                        @error('device_comunica')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex items-center">
                        <input id="ativo" name="ativo" type="checkbox" 
                               {{ old('ativo', true) ? 'checked' : '' }}
                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="ativo" class="ml-2 block text-sm text-gray-900">
                            Responsável ativo
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Responsáveis inativos não podem ter entradas registradas
                    </p>
                </div>
            </div>

            <!-- Vinculados Section -->
            <div class="border-t border-gray-200 pt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Pessoas Vinculadas</h3>
                    <button type="button" id="add-vinculado" 
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Adicionar Vinculado
                    </button>
                </div>
                
                <div id="vinculados-container">
                    <!-- Vinculados will be added here via JavaScript -->
                </div>
            </div>

            <!-- Actions -->
            <div class="border-t border-gray-200 pt-6">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.responsaveis.index', $eventoId ? ['evento_id' => $eventoId] : []) }}" 
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Salvar Responsável
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // CPF Mask
    const cpfInput = document.getElementById('cpf');
    cpfInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        e.target.value = value;
    });

    // Phone Masks
    const phoneInputs = document.querySelectorAll('#telefone1, #telefone2');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{4})(\d)/, '$1-$2');
            } else {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });
    });

    // Vinculados management
    let vinculadoIndex = 0;

    document.getElementById('add-vinculado').addEventListener('click', function() {
        addVinculado();
    });

    function addVinculado(data = {}) {
        const container = document.getElementById('vinculados-container');
        const vinculadoHtml = `
            <div class="vinculado-item border border-gray-200 rounded-lg p-4 mb-4" data-index="${vinculadoIndex}">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-sm font-medium text-gray-900">Vinculado #${vinculadoIndex + 1}</h4>
                    <button type="button" onclick="removeVinculado(this)" 
                            class="text-red-600 hover:text-red-900">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nome *</label>
                        <input type="text" name="vinculados[${vinculadoIndex}][nome]" 
                               value="${data.nome || ''}"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                               placeholder="Nome do vinculado" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Data de Nascimento *</label>
                        <input type="date" name="vinculados[${vinculadoIndex}][nascimento]" 
                               value="${data.nascimento || ''}"
                               max="${new Date().toISOString().split('T')[0]}"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo de Vínculo *</label>
                        <select name="vinculados[${vinculadoIndex}][vinculo_id]" 
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                            <option value="">Selecione...</option>
                            @foreach($vinculos as $vinculo)
                                <option value="{{ $vinculo->id }}" ${data.vinculo_id == {{ $vinculo->id }} ? 'selected' : ''}>{{ $vinculo->descricao }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="mt-3">
                    <div class="flex items-center">
                        <input type="checkbox" name="vinculados[${vinculadoIndex}][lembrar]" 
                               ${data.lembrar ? 'checked' : ''}
                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">
                            Lembrar de incluir nas comunicações
                        </label>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', vinculadoHtml);
        vinculadoIndex++;
    }

    window.removeVinculado = function(button) {
        if (confirm('Tem certeza que deseja remover este vinculado?')) {
            button.closest('.vinculado-item').remove();
        }
    };

    // Load existing vinculados if editing
    @if(old('vinculados'))
        @foreach(old('vinculados') as $index => $vinculado)
            addVinculado(@json($vinculado));
        @endforeach
    @endif
});
</script>
@endpush
@endsection