@extends('admin.layout')

@section('title', 'Nova Entrada')

@push('head')
    <x-sweet-alert />
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Nova Entrada</h1>
            <p class="mt-1 text-sm text-gray-500">
                Registre uma nova entrada no sistema de bilhetagem
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.entradas.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                <i class="fas fa-arrow-left -ml-1 mr-2 h-4 w-4"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Event Selector -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-calendar-alt mr-2"></i>
                Selecionar Evento
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="evento_select" class="block text-sm font-medium text-gray-700">Evento</label>
                    <select id="evento_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        <option value="">Selecione um evento</option>
                        @foreach($eventos as $evento)
                            <option value="{{ $evento->id }}">{{ $evento->titulo }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="event-stats" class="hidden">
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Presentes:</span>
                            <span id="presentes-count" class="font-semibold text-green-600">0</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Capacidade:</span>
                            <span id="capacidade-info" class="font-semibold">0/0</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Disponível:</span>
                            <span id="disponivel-count" class="font-semibold text-blue-600">0</span>
                        </div>
                    </div>
                    
                    <!-- Capacity Alerts -->
                    <div id="capacity-alert-container" class="mt-3 hidden">
                        <div id="capacity-alert" class="p-3 rounded-md border">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i id="capacity-alert-icon" class="fas fa-info-circle"></i>
                                </div>
                                <div class="ml-3">
                                    <p id="capacity-alert-text" class="text-sm font-medium"></p>
                                    <p id="capacity-alert-detail" class="text-xs mt-1"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Entry Form -->
    <div id="entry-form-container" class="hidden">
        <form id="entry-form" class="bg-white shadow rounded-lg" data-confirm="Confirma o registro desta entrada no evento?">
            @csrf
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">
                    <i class="fas fa-ticket-alt mr-2"></i>
                    Registrar Entrada
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Responsável -->
                    <div>
                        <label for="responsavel_id" class="block text-sm font-medium text-gray-700">
                            Responsável <span class="text-red-500">*</span>
                            <span class="text-xs text-gray-500 font-normal ml-1">(Alt+N para focar)</span>
                        </label>
                        <select name="responsavel_id" id="responsavel_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            <option value="">Selecione um responsável</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Apenas responsáveis inscritos no evento aparecem aqui</p>
                    </div>
                    
                    <!-- Criança/Vinculado -->
                    <div id="vinculado-container" class="hidden">
                        <label for="vinculado_id" class="block text-sm font-medium text-gray-700">Criança <span class="text-red-500">*</span></label>
                        <select name="vinculado_id" id="vinculado_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            <option value="">Selecione uma criança</option>
                        </select>
                        <div id="vinculado-info" class="mt-2 text-sm text-gray-600 hidden">
                            <div class="flex justify-between">
                                <span>Idade: <span id="vinculado-idade"></span> anos</span>
                                <span>Vínculo: <span id="vinculado-vinculo"></span></span>
                            </div>
                        </div>
                        
                        <!-- Duplicate Entry Warning -->
                        <div id="duplicate-warning" class="hidden mt-3 p-3 bg-amber-50 border-l-4 border-amber-400 rounded-r-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-amber-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-amber-800 font-medium">
                                        <span id="duplicate-child-name"></span> já está presente neste evento!
                                    </p>
                                    <p class="text-xs text-amber-600 mt-1">
                                        Entrada registrada em: <span id="duplicate-entry-time"></span>
                                    </p>
                                    <p class="text-xs text-amber-600">
                                        Pacote: <span id="duplicate-package-name"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Perfil de Acesso -->
                    <div id="perfil-container" class="hidden">
                        <label for="perfil_acesso_id" class="block text-sm font-medium text-gray-700">Perfil de Acesso</label>
                        <select name="perfil_acesso_id" id="perfil_acesso_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Perfil sugerido automático</option>
                        </select>
                        <div id="perfil-info" class="mt-1 text-sm text-green-600 hidden">
                            Perfil sugerido: <span id="perfil-sugerido"></span>
                        </div>
                    </div>
                    
                    <!-- Pacote -->
                    <div id="pacote-container" class="hidden">
                        <label for="pacote_id" class="block text-sm font-medium text-gray-700">Pacote <span class="text-red-500">*</span></label>
                        <select name="pacote_id" id="pacote_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            <option value="">Selecione um pacote</option>
                        </select>
                        <div id="pacote-info" class="mt-2 text-sm text-gray-600 hidden">
                            <div class="flex justify-between">
                                <span>Duração: <span id="pacote-duracao"></span> min</span>
                                <span>Valor: <span id="pacote-valor"></span></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pré-venda (opcional) -->
                    <div id="prevenda-container" class="hidden">
                        <label for="prevenda_id" class="block text-sm font-medium text-gray-700">Pré-venda (opcional)</label>
                        <select name="prevenda_id" id="prevenda_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Nenhuma pré-venda</option>
                        </select>
                    </div>
                </div>
                
                <!-- Payment Section -->
                <div id="payment-section" class="hidden bg-blue-50 p-4 rounded-lg border-2 border-blue-200">
                    <h4 class="text-lg font-medium text-blue-900 mb-4">
                        <i class="fas fa-credit-card mr-2"></i>
                        Informações de Pagamento
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Payment Method -->
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Forma de Pagamento <span class="text-red-500">*</span></label>
                            <select name="payment_method" id="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="">Selecione a forma</option>
                            </select>
                        </div>
                        
                        <!-- Package Price Display -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Valor do Pacote</label>
                            <div class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm">
                                <span id="package-price" class="font-semibold text-green-600">R$ 0,00</span>
                            </div>
                        </div>
                        
                        <!-- Amount Paid -->
                        <div id="amount-paid-container" class="hidden">
                            <label for="amount_paid" class="block text-sm font-medium text-gray-700">Valor Recebido <span class="text-red-500">*</span></label>
                            <input type="text" name="amount_paid" id="amount_paid" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="R$ 0,00">
                        </div>
                        
                        <!-- Change Display -->
                        <div id="change-container" class="hidden">
                            <label class="block text-sm font-medium text-gray-700">Troco</label>
                            <div class="mt-1 block w-full px-3 py-2 rounded-md text-sm" id="change-display">
                                <span id="change-amount" class="font-semibold">R$ 0,00</span>
                            </div>
                        </div>
                        
                        <!-- Payment Status -->
                        <div id="payment-status-container" class="hidden">
                            <label class="block text-sm font-medium text-gray-700">Status do Pagamento</label>
                            <div class="mt-1 flex items-center">
                                <span id="payment-status" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                    <i class="fas fa-clock mr-1"></i>
                                    Aguardando...
                                </span>
                            </div>
                        </div>
                        
                        <!-- Payment Notes -->
                        <div class="lg:col-span-2">
                            <label for="payment_notes" class="block text-sm font-medium text-gray-700">Observações do Pagamento</label>
                            <input type="text" name="payment_notes" id="payment_notes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Observações adicionais (opcional)">
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="mt-6 flex items-center justify-end space-x-3">
                    <button type="button" id="clear-form" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-times -ml-1 mr-2 h-4 w-4"></i>
                        Limpar
                    </button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save -ml-1 mr-2 h-4 w-4"></i>
                        Registrar Entrada
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Success Message with Quick Actions -->
    <div id="success-message" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800" id="success-text"></p>
                    <p class="text-xs text-green-600 mt-1" id="success-details"></p>
                </div>
            </div>
            <div class="flex space-x-2 ml-4">
                <button type="button" id="generate-receipt" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-purple-700 bg-purple-100 hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <i class="fas fa-receipt mr-1"></i>
                    Comprovante
                </button>
                <button type="button" id="add-another-entry" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-plus mr-1"></i>
                    Outra Entrada
                </button>
                <button type="button" id="same-family-entry" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-users mr-1"></i>
                    Mesmo Responsável
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                <span class="text-gray-700">Processando entrada...</span>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div id="receipt-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="bg-indigo-600 px-6 py-4 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-white">
                        <i class="fas fa-receipt mr-2"></i>
                        Comprovante de Entrada
                    </h3>
                    <button type="button" id="close-receipt" class="text-indigo-200 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div id="receipt-content" class="p-6 font-mono text-sm bg-white">
                <!-- Receipt content will be dynamically generated here -->
            </div>
            
            <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex justify-between space-x-3">
                <button type="button" id="print-receipt" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-print mr-2"></i>
                    Imprimir
                </button>
                <button type="button" id="close-receipt-btn" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentEventData = null;
    
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
    
    // Event selection handler
    const eventoSelect = document.getElementById('evento_select');
    const entryFormContainer = document.getElementById('entry-form-container');
    const eventStats = document.getElementById('event-stats');
    const loadingOverlay = document.getElementById('loading-overlay');
    
    eventoSelect.addEventListener('change', function() {
        const eventoId = this.value;
        if (eventoId) {
            loadEventData(eventoId);
        } else {
            entryFormContainer.classList.add('hidden');
            eventStats.classList.add('hidden');
        }
    });
    
    // Load event data
    function loadEventData(eventoId) {
        loadingOverlay.classList.remove('hidden');
        
        fetch(`/admin/entradas/evento/${eventoId}/data`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na requisição: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                currentEventData = data;
                populateEventData(data);
                entryFormContainer.classList.remove('hidden');
                eventStats.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Erro ao carregar dados do evento:', error);
                alert('Erro ao carregar dados do evento: ' + error.message);
            })
            .finally(() => {
                loadingOverlay.classList.add('hidden');
            });
    }
    
    // Populate form with event data
    function populateEventData(data) {
        // Store event data for payment processing
        window.currentEventData = data;
        
        try {
            // Update stats - check if elements exist first
            const presentes = data.estatisticas.presentes;
            const capacidade = data.evento.capacidade;
            const disponivel = capacidade - presentes;
            
            const presentesEl = document.getElementById('presentes-count');
            const capacidadeEl = document.getElementById('capacidade-info');
            const disponivelEl = document.getElementById('disponivel-count');
            
            if (presentesEl) presentesEl.textContent = presentes;
            if (capacidadeEl) capacidadeEl.textContent = `${presentes}/${capacidade}`;
            if (disponivelEl) disponivelEl.textContent = disponivel;
            
            // Update colors based on capacity and show alerts
            if (disponivelEl) {
                updateCapacityAlerts(disponivel, capacidade);
                
                if (disponivel <= 0) {
                    disponivelEl.className = 'font-semibold text-red-600';
                } else if (disponivel <= 10) {
                    disponivelEl.className = 'font-semibold text-orange-600';
                } else {
                    disponivelEl.className = 'font-semibold text-blue-600';
                }
            }
            
            // Populate payment methods based on event settings
            populatePaymentMethods(data.evento);
            
            // Populate responsáveis
            const responsavelSelect = document.getElementById('responsavel_id');
            if (responsavelSelect) {
                responsavelSelect.innerHTML = '<option value="">Selecione um responsável</option>';
                if (data.responsaveis && Array.isArray(data.responsaveis)) {
                    data.responsaveis.forEach(function(responsavel) {
                        try {
                            const option = document.createElement('option');
                            option.value = responsavel.id;
                            option.textContent = responsavel.nome;
                            responsavelSelect.appendChild(option);
                        } catch (error) {
                            console.error('Error adding responsavel option:', error);
                        }
                    });
                }
            }
            
            // Populate pacotes
            const pacoteSelect = document.getElementById('pacote_id');
            if (pacoteSelect) {
                pacoteSelect.innerHTML = '<option value="">Selecione um pacote</option>';
                if (data.evento.pacotes && Array.isArray(data.evento.pacotes)) {
                    data.evento.pacotes.forEach(function(pacote) {
                        try {
                            const option = document.createElement('option');
                            option.value = pacote.id;
                            option.textContent = `${pacote.descricao} - R$ ${parseFloat(pacote.valor).toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
                            pacoteSelect.appendChild(option);
                        } catch (error) {
                            console.error('Error adding pacote option:', error);
                        }
                    });
                }
            }
            
            // Populate pré-vendas
            const prevendaSelect = document.getElementById('prevenda_id');
            if (prevendaSelect) {
                prevendaSelect.innerHTML = '<option value="">Nenhuma pré-venda</option>';
                if (data.prevendas && Array.isArray(data.prevendas)) {
                    data.prevendas.forEach(function(prevenda) {
                        try {
                            const option = document.createElement('option');
                            option.value = prevenda.id;
                            option.textContent = `ID: ${prevenda.id} - R$ ${parseFloat(prevenda.valor).toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
                            prevendaSelect.appendChild(option);
                        } catch (error) {
                            console.error('Error adding prevenda option:', error);
                        }
                    });
                }
            }

            // Populate perfis de acesso
            const perfilSelect = document.getElementById('perfil_acesso_id');
            if (perfilSelect) {
                perfilSelect.innerHTML = '<option value="">Perfil sugerido automático</option>';
                if (data.perfis_acesso && Array.isArray(data.perfis_acesso)) {
                    data.perfis_acesso.forEach(function(perfil) {
                        try {
                            const option = document.createElement('option');
                            option.value = perfil.id;
                            option.textContent = perfil.titulo;
                            perfilSelect.appendChild(option);
                        } catch (error) {
                            console.error('Error adding perfil option:', error);
                        }
                    });
                }
            }
        } catch (error) {
            console.error('Error populating event data:', error);
            alert('Erro ao carregar dados do evento. Tente novamente.');
        }
    }
    
    // Responsável selection handler
    const responsavelSelect = document.getElementById('responsavel_id');
    const vinculadoContainer = document.getElementById('vinculado-container');
    const vinculadoSelect = document.getElementById('vinculado_id');
    
    responsavelSelect.addEventListener('change', function() {
        const responsavelId = this.value;
        const eventoId = eventoSelect.value;
        
        if (!responsavelId) {
            vinculadoContainer.classList.add('hidden');
            hideSubsequentContainers();
            return;
        }
        
        // Load vinculados for selected responsável
        fetch(`/admin/entradas/vinculados?responsavel_id=${responsavelId}&evento_id=${eventoId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (vinculadoSelect) {
                    vinculadoSelect.innerHTML = '<option value="">Selecione uma criança</option>';
                    if (data.vinculados && Array.isArray(data.vinculados)) {
                        data.vinculados.forEach(function(vinculado) {
                            try {
                                const option = document.createElement('option');
                                option.value = vinculado.id;
                                option.textContent = `${vinculado.nome} (${vinculado.idade} anos)`;
                                option.setAttribute('data-idade', vinculado.idade);
                                option.setAttribute('data-vinculo', vinculado.vinculo);
                                option.setAttribute('data-perfil-sugerido-id', vinculado.perfil_sugerido_id || '');
                                option.setAttribute('data-perfil-sugerido', vinculado.perfil_sugerido || '');
                                vinculadoSelect.appendChild(option);
                            } catch (error) {
                                console.error('Error creating vinculado option:', error);
                            }
                        });
                    }
                    
                    if (vinculadoContainer) {
                        vinculadoContainer.classList.remove('hidden');
                    }
                    hideSubsequentContainers();
                } else {
                    console.error('vinculadoSelect element not found');
                }
            })
            .catch(error => {
                console.error('Erro ao carregar vinculados:', error);
                if (window.SweetAlert) {
                    SweetAlert.error('Erro!', 'Não foi possível carregar as crianças do responsável.');
                } else {
                    alert('Erro ao carregar crianças do responsável: ' + error.message);
                }
            });
    });
    
    // Vinculado selection handler
    if (vinculadoSelect) {
        vinculadoSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const vinculadoId = this.value;
            
            if (!vinculadoId) {
                hideVinculadoInfo();
                hideSubsequentContainers();
                return;
            }
            
            // Show vinculado info
            const idade = selectedOption.getAttribute('data-idade');
            const vinculo = selectedOption.getAttribute('data-vinculo');
            const perfilSugeridoId = selectedOption.getAttribute('data-perfil-sugerido-id');
            const perfilSugerido = selectedOption.getAttribute('data-perfil-sugerido');
            
            const vinculadoIdadeEl = document.getElementById('vinculado-idade');
            const vinculadoVinculoEl = document.getElementById('vinculado-vinculo');
            const vinculadoInfoEl = document.getElementById('vinculado-info');
            
            if (vinculadoIdadeEl) vinculadoIdadeEl.textContent = idade;
            if (vinculadoVinculoEl) vinculadoVinculoEl.textContent = vinculo;
            if (vinculadoInfoEl) vinculadoInfoEl.classList.remove('hidden');
            
            // Check for duplicate entry
            checkDuplicateEntry(vinculadoId, selectedOption.textContent.split(' (')[0]);
            
            // Show perfil container and set suggested perfil
            const perfilContainer = document.getElementById('perfil-container');
            const perfilInfo = document.getElementById('perfil-info');
            const perfilSugeridoSpan = document.getElementById('perfil-sugerido');
            
            if (perfilContainer) perfilContainer.classList.remove('hidden');
            
            if (perfilSugerido && perfilInfo && perfilSugeridoSpan) {
                perfilInfo.classList.remove('hidden');
                perfilSugeridoSpan.textContent = perfilSugerido;
                
                // Pre-select suggested perfil
                if (perfilSugeridoId) {
                    const perfilAcessoEl = document.getElementById('perfil_acesso_id');
                    if (perfilAcessoEl) perfilAcessoEl.value = perfilSugeridoId;
                }
            } else if (perfilInfo) {
                perfilInfo.classList.add('hidden');
            }
            
            // Show pacote container
            const pacoteContainer = document.getElementById('pacote-container');
            const prevendaContainer = document.getElementById('prevenda-container');
            if (pacoteContainer) pacoteContainer.classList.remove('hidden');
            if (prevendaContainer) prevendaContainer.classList.remove('hidden');
        });
    }
    
    // Pacote selection handler
    const pacoteSelect = document.getElementById('pacote_id');
    if (pacoteSelect) {
        pacoteSelect.addEventListener('change', function() {
            const pacoteId = this.value;
            
            if (pacoteId && currentEventData && currentEventData.evento.pacotes) {
                const pacote = currentEventData.evento.pacotes.find(p => p.id == pacoteId);
                if (pacote) {
                    const pacoteDuracaoEl = document.getElementById('pacote-duracao');
                    const pacoteValorEl = document.getElementById('pacote-valor');
                    const pacoteInfoEl = document.getElementById('pacote-info');
                    
                    if (pacoteDuracaoEl) pacoteDuracaoEl.textContent = pacote.duracao;
                    if (pacoteValorEl) pacoteValorEl.textContent = `R$ ${parseFloat(pacote.valor).toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
                    if (pacoteInfoEl) pacoteInfoEl.classList.remove('hidden');
                    
                    // Show payment section and update price
                    showPaymentSection(pacote);
                }
            } else {
                const pacoteInfoEl = document.getElementById('pacote-info');
                if (pacoteInfoEl) pacoteInfoEl.classList.add('hidden');
                hidePaymentSection();
            }
        });
    }
    
    function hideVinculadoInfo() {
        const vinculadoInfoEl = document.getElementById('vinculado-info');
        const duplicateWarningEl = document.getElementById('duplicate-warning');
        if (vinculadoInfoEl) vinculadoInfoEl.classList.add('hidden');
        if (duplicateWarningEl) duplicateWarningEl.classList.add('hidden');
    }
    
    function hideSubsequentContainers() {
        const containers = [
            'perfil-container',
            'pacote-container', 
            'prevenda-container',
            'perfil-info',
            'pacote-info',
            'duplicate-warning'
        ];
        
        containers.forEach(function(containerId) {
            const element = document.getElementById(containerId);
            if (element) element.classList.add('hidden');
        });
        
        hidePaymentSection();
    }
    
    // Check for duplicate entry
    function checkDuplicateEntry(vinculadoId, vinculadoNome) {
        const eventoId = eventoSelect ? eventoSelect.value : null;
        
        if (!eventoId) {
            console.error('Evento ID not found');
            return;
        }
        
        fetch(`/admin/entradas/check-duplicate?evento_id=${eventoId}&vinculado_id=${vinculadoId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                const duplicateWarning = document.getElementById('duplicate-warning');
                const duplicateChildName = document.getElementById('duplicate-child-name');
                const duplicateEntryTime = document.getElementById('duplicate-entry-time');
                const duplicatePackageName = document.getElementById('duplicate-package-name');
                
                if (data.exists && duplicateWarning) {
                    // Show duplicate warning
                    if (duplicateChildName) duplicateChildName.textContent = vinculadoNome;
                    if (duplicateEntryTime) duplicateEntryTime.textContent = data.entry_time;
                    if (duplicatePackageName) duplicatePackageName.textContent = data.package_name;
                    duplicateWarning.classList.remove('hidden');
                } else if (duplicateWarning) {
                    duplicateWarning.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Erro ao verificar entrada duplicada:', error);
                const duplicateWarning = document.getElementById('duplicate-warning');
                if (duplicateWarning) duplicateWarning.classList.add('hidden');
            });
    }
    
    // Update capacity alerts based on available spots
    function updateCapacityAlerts(disponivel, capacidade) {
        const alertContainer = document.getElementById('capacity-alert-container');
        const alertElement = document.getElementById('capacity-alert');
        const alertIcon = document.getElementById('capacity-alert-icon');
        const alertText = document.getElementById('capacity-alert-text');
        const alertDetail = document.getElementById('capacity-alert-detail');
        
        // Check if all required elements exist
        if (!alertContainer || !alertElement || !alertIcon || !alertText || !alertDetail) {
            console.warn('Some capacity alert elements not found');
            return;
        }
        
        const percentageUsed = ((capacidade - disponivel) / capacidade) * 100;
        
        if (disponivel <= 0) {
            // Full capacity - critical alert
            alertContainer.classList.remove('hidden');
            alertElement.className = 'p-3 rounded-md border bg-red-50 border-red-200';
            alertIcon.className = 'fas fa-exclamation-triangle text-red-400';
            alertText.className = 'text-sm font-medium text-red-800';
            alertText.textContent = 'Capacidade Esgotada!';
            alertDetail.className = 'text-xs mt-1 text-red-600';
            alertDetail.textContent = 'O evento atingiu a capacidade máxima. Novas entradas podem ser bloqueadas.';
        } else if (disponivel <= 5) {
            // Very low capacity - warning alert
            alertContainer.classList.remove('hidden');
            alertElement.className = 'p-3 rounded-md border bg-orange-50 border-orange-200';
            alertIcon.className = 'fas fa-exclamation-circle text-orange-400';
            alertText.className = 'text-sm font-medium text-orange-800';
            alertText.textContent = `Capacidade Crítica! Apenas ${disponivel} vagas restantes`;
            alertDetail.className = 'text-xs mt-1 text-orange-600';
            alertDetail.textContent = `${percentageUsed.toFixed(1)}% da capacidade ocupada. Monitore as entradas de perto.`;
        } else if (disponivel <= 15) {
            // Moderate capacity - info alert
            alertContainer.classList.remove('hidden');
            alertElement.className = 'p-3 rounded-md border bg-yellow-50 border-yellow-200';
            alertIcon.className = 'fas fa-info-circle text-yellow-400';
            alertText.className = 'text-sm font-medium text-yellow-800';
            alertText.textContent = `Atenção: ${disponivel} vagas disponíveis`;
            alertDetail.className = 'text-xs mt-1 text-yellow-600';
            alertDetail.textContent = `${percentageUsed.toFixed(1)}% da capacidade ocupada. Prepare-se para possível lotação.`;
        } else if (percentageUsed >= 50) {
            // Half capacity reached - light info
            alertContainer.classList.remove('hidden');
            alertElement.className = 'p-3 rounded-md border bg-blue-50 border-blue-200';
            alertIcon.className = 'fas fa-info-circle text-blue-400';
            alertText.className = 'text-sm font-medium text-blue-800';
            alertText.textContent = `Evento com boa ocupação: ${disponivel} vagas`;
            alertDetail.className = 'text-xs mt-1 text-blue-600';
            alertDetail.textContent = `${percentageUsed.toFixed(1)}% da capacidade ocupada. Evento está se aproximando da metade.`;
        } else {
            // Good capacity available - hide alert
            alertContainer.classList.add('hidden');
        }
    }
    
    // Payment Management Functions
    function populatePaymentMethods(evento) {
        const paymentSelect = document.getElementById('payment_method');
        if (!paymentSelect) {
            console.error('Payment method select element not found');
            return;
        }
        
        paymentSelect.innerHTML = '<option value="">Selecione a forma</option>';
        
        if (evento.aceita_dinheiro) {
            paymentSelect.appendChild(new Option('Dinheiro', 'dinheiro'));
        }
        if (evento.aceita_cartao) {
            paymentSelect.appendChild(new Option('Cartão de Débito', 'cartao_debito'));
            paymentSelect.appendChild(new Option('Cartão de Crédito', 'cartao_credito'));
        }
        if (evento.aceita_pix) {
            paymentSelect.appendChild(new Option('PIX', 'pix'));
        }
        if (evento.aceita_gratuito) {
            paymentSelect.appendChild(new Option('Gratuito', 'gratuito'));
        }
    }
    
    function showPaymentSection(pacote) {
        const paymentSection = document.getElementById('payment-section');
        if (!paymentSection) {
            console.error('Payment section element not found');
            return;
        }
        
        paymentSection.classList.remove('hidden');
        
        // Update package price display
        const packagePrice = parseFloat(pacote.valor);
        const packagePriceEl = document.getElementById('package-price');
        if (packagePriceEl) {
            packagePriceEl.textContent = `R$ ${packagePrice.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
        }
        
        // Store current package data
        window.currentPackage = pacote;
        
        // Show payment status
        const paymentStatusContainer = document.getElementById('payment-status-container');
        if (paymentStatusContainer) {
            paymentStatusContainer.classList.remove('hidden');
            updatePaymentStatus('pending', 'Aguardando forma de pagamento');
        }
    }
    
    function hidePaymentSection() {
        const paymentElements = [
            'payment-section',
            'amount-paid-container', 
            'change-container',
            'payment-status-container'
        ];
        
        paymentElements.forEach(function(elementId) {
            const element = document.getElementById(elementId);
            if (element) element.classList.add('hidden');
        });
    }
    
    function updatePaymentStatus(status, message) {
        const statusElement = document.getElementById('payment-status');
        if (!statusElement) {
            console.error('Payment status element not found');
            return;
        }
        
        const statusClasses = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'valid': 'bg-green-100 text-green-800', 
            'invalid': 'bg-red-100 text-red-800',
            'processing': 'bg-blue-100 text-blue-800'
        };
        
        const statusIcons = {
            'pending': 'fas fa-clock',
            'valid': 'fas fa-check-circle',
            'invalid': 'fas fa-exclamation-triangle',
            'processing': 'fas fa-spinner fa-spin'
        };
        
        statusElement.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClasses[status] || statusClasses.pending}`;
        statusElement.innerHTML = `<i class="${statusIcons[status] || statusIcons.pending} mr-1"></i>${message}`;
    }
    
    // Payment method selection handler
    const paymentMethodSelect = document.getElementById('payment_method');
    if (paymentMethodSelect) {
        paymentMethodSelect.addEventListener('change', function() {
            const paymentMethod = this.value;
            const amountPaidContainer = document.getElementById('amount-paid-container');
            const changeContainer = document.getElementById('change-container');
            
            if (paymentMethod === 'dinheiro') {
                if (amountPaidContainer) amountPaidContainer.classList.remove('hidden');
                if (changeContainer) changeContainer.classList.remove('hidden');
                updatePaymentStatus('pending', 'Digite o valor recebido');
            } else if (paymentMethod === 'gratuito') {
                if (amountPaidContainer) amountPaidContainer.classList.add('hidden');
                if (changeContainer) changeContainer.classList.add('hidden');
                updatePaymentStatus('valid', 'Entrada gratuita');
            } else if (paymentMethod) {
                if (amountPaidContainer) amountPaidContainer.classList.add('hidden');
                if (changeContainer) changeContainer.classList.add('hidden');
                updatePaymentStatus('valid', 'Pagamento confirmado');
            } else {
                if (amountPaidContainer) amountPaidContainer.classList.add('hidden');
                if (changeContainer) changeContainer.classList.add('hidden');
                updatePaymentStatus('pending', 'Selecione forma de pagamento');
            }
        });
    }
    
    // Amount paid input handler with real-time change calculation
    const amountPaidInput = document.getElementById('amount_paid');
    if (amountPaidInput) {
        amountPaidInput.addEventListener('input', function() {
            const input = this;
            
            // Format as currency
            let value = input.value.replace(/\D/g, '');
            if (value === '') {
                input.value = '';
                updateChangeDisplay(0);
                return;
            }
            
            value = (parseInt(value) / 100).toFixed(2);
            input.value = `R$ ${value.replace('.', ',')}`;
            
            // Calculate and display change
            const amountPaid = parseFloat(value);
            updateChangeDisplay(amountPaid);
        });
    }
    
    function updateChangeDisplay(amountPaid) {
        if (!window.currentPackage) return;
        
        const packagePrice = parseFloat(window.currentPackage.valor);
        const change = amountPaid - packagePrice;
        const changeElement = document.getElementById('change-amount');
        const changeDisplay = document.getElementById('change-display');
        
        if (amountPaid === 0) {
            changeElement.textContent = 'R$ 0,00';
            changeDisplay.className = 'mt-1 block w-full px-3 py-2 rounded-md text-sm bg-gray-50 border border-gray-300';
            updatePaymentStatus('pending', 'Digite o valor recebido');
        } else if (change < 0) {
            changeElement.textContent = `Falta: R$ ${Math.abs(change).toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
            changeDisplay.className = 'mt-1 block w-full px-3 py-2 rounded-md text-sm bg-red-50 border border-red-300';
            updatePaymentStatus('invalid', 'Valor insuficiente');
        } else {
            changeElement.textContent = `R$ ${change.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
            changeDisplay.className = 'mt-1 block w-full px-3 py-2 rounded-md text-sm bg-green-50 border border-green-300';
            updatePaymentStatus('valid', change > 0 ? 'Troco calculado' : 'Valor exato');
        }
    }
    
    // Form submission
    const entryForm = document.getElementById('entry-form');
    entryForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!currentEventData) {
            alert('Selecione um evento primeiro');
            return;
        }
        
        // Validate required fields
        const vinculadoId = document.getElementById('vinculado_id').value;
        const pacoteId = document.getElementById('pacote_id').value;
        const paymentMethod = document.getElementById('payment_method').value;
        
        if (!vinculadoId) {
            SweetAlert.warning('Atenção!', 'Selecione uma criança para continuar.');
            return;
        }
        
        if (!pacoteId) {
            SweetAlert.warning('Atenção!', 'Selecione um pacote para continuar.');
            return;
        }
        
        if (!paymentMethod) {
            SweetAlert.warning('Atenção!', 'Selecione a forma de pagamento.');
            return;
        }
        
        // Validate payment for cash payments
        if (paymentMethod === 'dinheiro') {
            const amountPaidInput = document.getElementById('amount_paid').value;
            if (!amountPaidInput) {
                SweetAlert.warning('Atenção!', 'Digite o valor recebido em dinheiro.');
                return;
            }
            
            const amountPaid = parseFloat(amountPaidInput.replace('R$ ', '').replace(',', '.'));
            const packagePrice = parseFloat(window.currentPackage.valor);
            
            if (amountPaid < packagePrice) {
                SweetAlert.warning('Atenção!', 'Valor recebido é insuficiente para o pacote selecionado.');
                return;
            }
        }
        
        // Check capacity
        if (currentEventData.estatisticas.capacidade_disponivel <= 0) {
            if (!confirm('Capacidade máxima atingida. Deseja continuar?')) {
                return;
            }
        }
        
        const formData = new FormData(this);
        const eventoId = eventoSelect.value;
        
        // Add additional data
        formData.append('evento_id', eventoId);
        
        loadingOverlay.classList.remove('hidden');
        
        fetch('{{ route("admin.entradas.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Store last successful entry data for quick actions and receipt
                window.lastEntryData = {
                    entrada_id: data.entrada.id,
                    responsavel_id: document.getElementById('responsavel_id').value,
                    responsavel_nome: document.getElementById('responsavel_id').options[document.getElementById('responsavel_id').selectedIndex].text,
                    vinculado_nome: document.getElementById('vinculado_id').options[document.getElementById('vinculado_id').selectedIndex].text,
                    pacote_nome: document.getElementById('pacote_id').options[document.getElementById('pacote_id').selectedIndex].text,
                    evento_nome: currentEventData.evento.titulo,
                    data_entrada: new Date().toLocaleDateString('pt-BR') + ' ' + new Date().toLocaleTimeString('pt-BR'),
                    payment_method: document.getElementById('payment_method').value,
                    package_price: window.currentPackage ? window.currentPackage.valor : 0,
                    amount_paid: document.getElementById('amount_paid').value || window.currentPackage?.valor || 0,
                    change: 0
                };
                
                // Calculate change for receipt
                if (window.lastEntryData.payment_method === 'dinheiro' && document.getElementById('amount_paid').value) {
                    const amountPaid = parseFloat(document.getElementById('amount_paid').value.replace('R$ ', '').replace(',', '.'));
                    const packagePrice = parseFloat(window.currentPackage.valor);
                    window.lastEntryData.change = amountPaid - packagePrice;
                    window.lastEntryData.amount_paid = amountPaid;
                } else if (window.lastEntryData.payment_method === 'gratuito') {
                    window.lastEntryData.amount_paid = 0;
                }
                
                // Show enhanced success message
                document.getElementById('success-text').textContent = data.success;
                document.getElementById('success-details').textContent = `${window.lastEntryData.vinculado_nome} - ${window.lastEntryData.pacote_nome}`;
                document.getElementById('success-message').classList.remove('hidden');
                
                // Clear form
                clearForm();
                
                // Reload event data to update stats
                loadEventData(eventoId);
                
                // Auto-hide success message after 8 seconds (longer for quick actions)
                setTimeout(function() {
                    document.getElementById('success-message').classList.add('hidden');
                }, 8000);
            } else {
                throw new Error(data.error || 'Erro desconhecido');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao registrar entrada: ' + error.message);
        })
        .finally(() => {
            loadingOverlay.classList.add('hidden');
        });
    });
    
    // Clear form
    document.getElementById('clear-form').addEventListener('click', function() {
        clearForm();
    });
    
    // Quick action buttons
    document.getElementById('generate-receipt').addEventListener('click', function() {
        if (window.lastEntryData) {
            generateReceipt();
        }
    });
    
    document.getElementById('add-another-entry').addEventListener('click', function() {
        // Hide success message and focus on event selector for quick new entry
        document.getElementById('success-message').classList.add('hidden');
        document.getElementById('responsavel_id').focus();
    });
    
    document.getElementById('same-family-entry').addEventListener('click', function() {
        // Hide success message and pre-select the same responsável
        document.getElementById('success-message').classList.add('hidden');
        
        if (window.lastEntryData && window.lastEntryData.responsavel_id) {
            const responsavelSelect = document.getElementById('responsavel_id');
            responsavelSelect.value = window.lastEntryData.responsavel_id;
            
            // Trigger the change event to load vinculados
            responsavelSelect.dispatchEvent(new Event('change'));
            
            // Focus on vinculado selector
            setTimeout(() => {
                document.getElementById('vinculado_id').focus();
            }, 500);
        }
    });
    
    // Receipt modal handlers
    document.getElementById('close-receipt').addEventListener('click', closeReceiptModal);
    document.getElementById('close-receipt-btn').addEventListener('click', closeReceiptModal);
    
    document.getElementById('print-receipt').addEventListener('click', function() {
        const receiptContent = document.getElementById('receipt-content').innerHTML;
        
        // Try to open print window
        const printWindow = window.open('', '_blank', 'width=400,height=600');
        
        // Check if popup was blocked
        if (!printWindow || printWindow.closed) {
            alert('Por favor, permita pop-ups para imprimir o comprovante ou use Ctrl+P para imprimir esta página.');
            return;
        }
        
        try {
            printWindow.document.write(
                '<html>' +
                    '<head>' +
                        '<title>Comprovante de Entrada</title>' +
                        '<meta charset="utf-8">' +
                        '<style>' +
                            'body { font-family: \"Courier New\", monospace; margin: 20px; font-size: 12px; }' +
                            '.receipt { max-width: 300px; margin: 0 auto; }' +
                            '@media print { body { margin: 0; } .receipt { max-width: none; } }' +
                        '</style>' +
                    '</head>' +
                    '<body onload="window.print(); setTimeout(function(){ window.close(); }, 100);">' +
                        '<div class="receipt">' + receiptContent + '</div>' +
                    '</body>' +
                '</html>'
            );
            printWindow.document.close();
        } catch (error) {
            console.error('Erro ao abrir janela de impressão:', error);
            alert('Erro ao imprimir. Tente usar Ctrl+P para imprimir esta página.');
            printWindow.close();
        }
    });
    
    function closeReceiptModal() {
        document.getElementById('receipt-modal').classList.add('hidden');
    }
    
    function generateReceipt() {
        const data = window.lastEntryData;
        const paymentMethodLabels = {
            'dinheiro': 'Dinheiro',
            'cartao_debito': 'Cartão Débito',
            'cartao_credito': 'Cartão Crédito',
            'pix': 'PIX',
            'gratuito': 'Gratuito'
        };
        
        let receiptContent = '<div class="text-center border-b-2 border-dashed border-gray-400 pb-4 mb-4">' +
                '<h4 class="font-bold text-lg">COMPROVANTE DE ENTRADA</h4>' +
                '<p class="text-sm">' + data.evento_nome + '</p>' +
            '</div>' +
            
            '<div class="space-y-2 border-b-2 border-dashed border-gray-400 pb-4 mb-4">' +
                '<div class="flex justify-between">' +
                    '<span>Data/Hora:</span>' +
                    '<span>' + data.data_entrada + '</span>' +
                '</div>' +
                '<div class="flex justify-between">' +
                    '<span>Entrada Nº:</span>' +
                    '<span>' + data.entrada_id + '</span>' +
                '</div>' +
            '</div>' +
            
            '<div class="space-y-2 border-b-2 border-dashed border-gray-400 pb-4 mb-4">' +
                '<div class="flex justify-between">' +
                    '<span>Criança:</span>' +
                    '<span>' + data.vinculado_nome + '</span>' +
                '</div>' +
                '<div class="flex justify-between">' +
                    '<span>Responsável:</span>' +
                    '<span>' + data.responsavel_nome + '</span>' +
                '</div>' +
            '</div>' +
            
            '<div class="space-y-2 border-b-2 border-dashed border-gray-400 pb-4 mb-4">' +
                '<div class="flex justify-between">' +
                    '<span>Pacote:</span>' +
                    '<span>' + data.pacote_nome + '</span>' +
                '</div>' +
                '<div class="flex justify-between">' +
                    '<span>Valor:</span>' +
                    '<span>R$ ' + parseFloat(data.package_price).toLocaleString('pt-BR', {minimumFractionDigits: 2}) + '</span>' +
                '</div>' +
            '</div>' +
            
            '<div class="space-y-2 border-b-2 border-dashed border-gray-400 pb-4 mb-4">' +
                '<div class="flex justify-between">' +
                    '<span>Forma Pgto:</span>' +
                    '<span>' + (paymentMethodLabels[data.payment_method] || data.payment_method) + '</span>' +
                '</div>';
        
        if (data.payment_method === 'dinheiro') {
            receiptContent += '<div class="flex justify-between">' +
                    '<span>Valor Recebido:</span>' +
                    '<span>R$ ' + parseFloat(data.amount_paid).toLocaleString('pt-BR', {minimumFractionDigits: 2}) + '</span>' +
                '</div>' +
                '<div class="flex justify-between font-bold">' +
                    '<span>Troco:</span>' +
                    '<span>R$ ' + parseFloat(data.change).toLocaleString('pt-BR', {minimumFractionDigits: 2}) + '</span>' +
                '</div>';
        }
        
        receiptContent += '</div>' +
            
            '<div class="text-center text-xs">' +
                '<p>Sistema de Bilhetagem</p>' +
                '<p>Entrada válida apenas para este evento</p>' +
                '<p>Guarde este comprovante</p>' +
            '</div>';
        
        document.getElementById('receipt-content').innerHTML = receiptContent;
        document.getElementById('receipt-modal').classList.remove('hidden');
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Alt + N = New Entry (focus responsável)
        if (e.altKey && e.key === 'n') {
            e.preventDefault();
            document.getElementById('responsavel_id').focus();
        }
        
        // Alt + S = Same Family (trigger same family button if visible)
        if (e.altKey && e.key === 's') {
            e.preventDefault();
            const sameFamilyBtn = document.getElementById('same-family-entry');
            if (!sameFamilyBtn.closest('#success-message').classList.contains('hidden')) {
                sameFamilyBtn.click();
            }
        }
    });
    
    function clearForm() {
        entryForm.reset();
        hideSubsequentContainers();
        hideVinculadoInfo();
        
        // Reset form to initial state
        vinculadoContainer.classList.add('hidden');
        document.getElementById('success-message').classList.add('hidden');
    }
});
</script>
@endpush
@endsection