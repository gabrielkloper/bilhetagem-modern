@extends('admin.layout')

@section('title', 'Nova Entrada')

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
        <form id="entry-form" class="bg-white shadow rounded-lg" data-confirm="Confirma o registro desta entrada no evento?" data-store-url="{{ route('admin.entradas.store') }}">
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
<script src="{{ asset('js/entradas-create.js') }}"></script>
@endpush
@endsection
