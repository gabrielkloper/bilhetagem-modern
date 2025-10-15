@extends('admin.layout')

@section('title', 'Novo Evento')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Novo Evento</h1>
            <p class="mt-1 text-sm text-gray-500">
                Crie um novo evento no sistema
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.eventos.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                <i class="fas fa-arrow-left -ml-1 mr-2 h-4 w-4"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.eventos.store') }}" method="POST" id="evento-form">
                @csrf
                
                <!-- Tabs Navigation -->
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button type="button" class="tab-btn active border-b-2 border-indigo-500 py-2 px-1 text-sm font-medium text-indigo-600" data-tab="dados">
                            <i class="fas fa-info-circle mr-2"></i>Dados Básicos
                        </button>
                        <button type="button" class="tab-btn border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700" data-tab="pagamento">
                            <i class="fas fa-credit-card mr-2"></i>Pagamentos
                        </button>
                        <button type="button" class="tab-btn border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700" data-tab="configuracoes">
                            <i class="fas fa-cog mr-2"></i>Configurações
                        </button>
                        <button type="button" class="tab-btn border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700" data-tab="regras">
                            <i class="fas fa-file-text mr-2"></i>Regras e Textos
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="mt-6">
                    <!-- Tab 1: Dados Básicos -->
                    <div id="tab-dados" class="tab-content">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Título -->
                            <div class="md:col-span-2">
                                <label for="titulo" class="block text-sm font-medium text-gray-700">Título do Evento *</label>
                                <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('titulo') border-red-300 @enderror" required>
                                @error('titulo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Descrição -->
                            <div class="md:col-span-2">
                                <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                                <textarea name="descricao" id="descricao" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('descricao') border-red-300 @enderror" required>{{ old('descricao') }}</textarea>
                                @error('descricao')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Local -->
                            <div>
                                <label for="local" class="block text-sm font-medium text-gray-700">Local *</label>
                                <input type="text" name="local" id="local" value="{{ old('local') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('local') border-red-300 @enderror" required>
                                @error('local')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cidade -->
                            <div>
                                <label for="cidade" class="block text-sm font-medium text-gray-700">Cidade *</label>
                                <input type="text" name="cidade" id="cidade" value="{{ old('cidade') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('cidade') border-red-300 @enderror" required>
                                @error('cidade')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Endereço -->
                            <div class="md:col-span-2">
                                <label for="endereco" class="block text-sm font-medium text-gray-700">Endereço</label>
                                <input type="text" name="endereco" id="endereco" value="{{ old('endereco') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('endereco') border-red-300 @enderror" required>
                                @error('endereco')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Data de Início -->
                            <div>
                                <label for="data_inicio" class="block text-sm font-medium text-gray-700">Data de Início *</label>
                                <input type="date" name="data_inicio" id="data_inicio" value="{{ old('data_inicio') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('data_inicio') border-red-300 @enderror" required>
                                @error('data_inicio')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Data de Fim -->
                            <div>
                                <label for="data_fim" class="block text-sm font-medium text-gray-700">Data de Fim *</label>
                                <input type="date" name="data_fim" id="data_fim" value="{{ old('data_fim') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('data_fim') border-red-300 @enderror" required>
                                @error('data_fim')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Hora de Início -->
                            <div>
                                <label for="hora_inicio" class="block text-sm font-medium text-gray-700">Hora de Início</label>
                                <input type="time" name="hora_inicio" id="hora_inicio" value="{{ old('hora_inicio') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('hora_inicio') border-red-300 @enderror" required>
                                @error('hora_inicio')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Hora de Fim -->
                            <div>
                                <label for="hora_fim" class="block text-sm font-medium text-gray-700">Hora de Fim</label>
                                <input type="time" name="hora_fim" id="hora_fim" value="{{ old('hora_fim') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('hora_fim') border-red-300 @enderror" required>
                                @error('hora_fim')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Capacidade -->
                            <div>
                                <label for="capacidade" class="block text-sm font-medium text-gray-700">Capacidade Máxima *</label>
                                <input type="number" name="capacidade" id="capacidade" value="{{ old('capacidade') }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('capacidade') border-red-300 @enderror" required>
                                @error('capacidade')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Preço Padrão -->
                            <div>
                                <label for="preco_padrao" class="block text-sm font-medium text-gray-700">Preço Padrão</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">R$</span>
                                    </div>
                                    <input type="text" name="preco_padrao" id="preco_padrao" value="{{ old('preco_padrao') }}" step="0.01" min="0" class="pl-8 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('preco_padrao') border-red-300 @enderror" required>
                                </div>
                                @error('preco_padrao')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Idade Mínima -->
                            <div>
                                <label for="idade_minima" class="block text-sm font-medium text-gray-700">Idade Mínima</label>
                                <input type="number" name="idade_minima" id="idade_minima" value="{{ old('idade_minima') }}" min="0" max="150" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('idade_minima') border-red-300 @enderror" required>
                                @error('idade_minima')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Deixe vazio para não restringir</p>
                            </div>

                            <!-- Idade Máxima -->
                            <div>
                                <label for="idade_maxima" class="block text-sm font-medium text-gray-700">Idade Máxima</label>
                                <input type="number" name="idade_maxima" id="idade_maxima" value="{{ old('idade_maxima') }}" min="0" max="150" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('idade_maxima') border-red-300 @enderror" required>
                                @error('idade_maxima')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Deixe vazio para não restringir</p>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('status') border-red-300 @enderror" required>
                                    <option value="">Selecione um status</option>
                                    <option value="ativo" {{ old('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                    <option value="inativo" {{ old('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                    <option value="cancelado" {{ old('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                    <option value="finalizado" {{ old('status') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Checkboxes -->
                            <div>
                                <fieldset>
                                    <legend class="text-sm font-medium text-gray-700 mb-2">Características</legend>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <input id="ativo" name="ativo" type="checkbox" value="1" {{ old('ativo', true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="ativo" class="ml-2 text-sm text-gray-700">Evento ativo</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="publico" name="publico" type="checkbox" value="1" {{ old('publico') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="publico" class="ml-2 text-sm text-gray-700">Evento público</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="permite_prevenda" name="permite_prevenda" type="checkbox" value="1" {{ old('permite_prevenda') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="permite_prevenda" class="ml-2 text-sm text-gray-700">Permite prevenda</label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Pagamentos -->
                    <div id="tab-pagamento" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <fieldset>
                                    <legend class="text-lg font-medium text-gray-900 mb-4">Modos de Pagamento Aceitos *</legend>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="flex items-center">
                                            <input id="aceita_dinheiro" name="aceita_dinheiro" type="checkbox" value="1" {{ old('aceita_dinheiro') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="aceita_dinheiro" class="ml-2 text-sm text-gray-700">
                                                <i class="fas fa-money-bill-wave text-green-600 mr-1"></i>
                                                Dinheiro
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="aceita_cartao" name="aceita_cartao" type="checkbox" value="1" {{ old('aceita_cartao') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="aceita_cartao" class="ml-2 text-sm text-gray-700">
                                                <i class="fas fa-credit-card text-blue-600 mr-1"></i>
                                                Cartão
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="aceita_pix" name="aceita_pix" type="checkbox" value="1" {{ old('aceita_pix') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="aceita_pix" class="ml-2 text-sm text-gray-700">
                                                <i class="fas fa-qrcode text-purple-600 mr-1"></i>
                                                PIX
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="aceita_gratuito" name="aceita_gratuito" type="checkbox" value="1" {{ old('aceita_gratuito') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="aceita_gratuito" class="ml-2 text-sm text-gray-700">
                                                <i class="fas fa-gift text-yellow-600 mr-1"></i>
                                                Gratuito
                                            </label>
                                        </div>
                                    </div>
                                    @error('modos_pagamento')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-xs text-gray-500">Selecione pelo menos um modo de pagamento</p>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 3: Configurações -->
                    <div id="tab-configuracoes" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Timezone -->
                            <div>
                                <label for="timezone" class="block text-sm font-medium text-gray-700">Fuso Horário</label>
                                <select name="timezone" id="timezone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('timezone') border-red-300 @enderror">
                                    <option value="America/Sao_Paulo" {{ old('timezone', 'America/Sao_Paulo') == 'America/Sao_Paulo' ? 'selected' : '' }}>São Paulo (UTC-3)</option>
                                    <option value="America/Manaus" {{ old('timezone') == 'America/Manaus' ? 'selected' : '' }}>Manaus (UTC-4)</option>
                                    <option value="America/Rio_Branco" {{ old('timezone') == 'America/Rio_Branco' ? 'selected' : '' }}>Rio Branco (UTC-5)</option>
                                </select>
                                @error('timezone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tempo Atualiza -->
                            <div>
                                <label for="tempo_atualiza" class="block text-sm font-medium text-gray-700">Tempo de Atualização (segundos)</label>
                                <input type="number" name="tempo_atualiza" id="tempo_atualiza" value="{{ old('tempo_atualiza', 10) }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tempo_atualiza') border-red-300 @enderror">
                                @error('tempo_atualiza')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Intervalo de atualização automática do sistema</p>
                            </div>

                            <!-- Tempo Tela -->
                            <div>
                                <label for="tempo_tela" class="block text-sm font-medium text-gray-700">Tempo de Tela (segundos)</label>
                                <input type="number" name="tempo_tela" id="tempo_tela" value="{{ old('tempo_tela', 3600) }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tempo_tela') border-red-300 @enderror">
                                @error('tempo_tela')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Tempo de exibição na tela principal</p>
                            </div>

                            <!-- Mostra Tempo -->
                            <div>
                                <fieldset>
                                    <legend class="text-sm font-medium text-gray-700 mb-2">Configurações de Exibição</legend>
                                    <div class="flex items-center">
                                        <input id="mostra_tempo" name="mostra_tempo" type="checkbox" value="1" {{ old('mostra_tempo', true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="mostra_tempo" class="ml-2 text-sm text-gray-700">Mostrar tempo na tela</label>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 4: Regras e Textos -->
                    <div id="tab-regras" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Regras Home -->
                            <div>
                                <label for="regras_home" class="block text-sm font-medium text-gray-700">Regras da Home</label>
                                <textarea name="regras_home" id="regras_home" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('regras_home') border-red-300 @enderror" placeholder="Regras exibidas na página inicial..." required>{{ old('regras_home') }}</textarea>
                                @error('regras_home')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Regras Cadastro -->
                            <div>
                                <label for="regras_cadastro" class="block text-sm font-medium text-gray-700">Regras de Cadastro</label>
                                <textarea name="regras_cadastro" id="regras_cadastro" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('regras_cadastro') border-red-300 @enderror" placeholder="Regras do processo de cadastro..." required>{{ old('regras_cadastro') }}</textarea>
                                @error('regras_cadastro')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Regras Parque -->
                            <div>
                                <label for="regras_parque" class="block text-sm font-medium text-gray-700">Regras do Evento</label>
                                <textarea name="regras_parque" id="regras_parque" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('regras_parque') border-red-300 @enderror" placeholder="Regras gerais do evento..." required>{{ old('regras_parque') }}</textarea>
                                @error('regras_parque')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Regras Comunicação -->
                            <div>
                                <label for="regras_comunica" class="block text-sm font-medium text-gray-700">Regras de Comunicação</label>
                                <textarea name="regras_comunica" id="regras_comunica" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('regras_comunica') border-red-300 @enderror" placeholder="Regras de comunicação e contato..." required>{{ old('regras_comunica') }}</textarea>
                                @error('regras_comunica')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Mensagem Fim Reserva -->
                            <div class="md:col-span-2">
                                <label for="msg_fimreserva" class="block text-sm font-medium text-gray-700">Mensagem de Fim de Reserva</label>
                                <textarea name="msg_fimreserva" id="msg_fimreserva" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('msg_fimreserva') border-red-300 @enderror" placeholder="Mensagem exibida quando as reservas encerram..." required>{{ old('msg_fimreserva') }}</textarea>
                                @error('msg_fimreserva')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Observações -->
                            <div class="md:col-span-2">
                                <label for="observacoes" class="block text-sm font-medium text-gray-700">Observações</label>
                                <textarea name="observacoes" id="observacoes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('observacoes') border-red-300 @enderror" placeholder="Observações internas sobre o evento..." required>{{ old('observacoes') }}</textarea>
                                @error('observacoes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.eventos.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save -ml-1 mr-2 h-4 w-4"></i>
                        Criar Evento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('border-indigo-500', 'text-indigo-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Add active class to clicked button
            this.classList.add('border-indigo-500', 'text-indigo-600');
            this.classList.remove('border-transparent', 'text-gray-500');
            
            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show target tab content
            document.getElementById('tab-' + targetTab).classList.remove('hidden');
        });
    });
    
    // Sincronizar data de início e fim
    document.getElementById('data_inicio').addEventListener('change', function() {
        const dataInicio = this.value;
        const dataFim = document.getElementById('data_fim').value;
        
        if (dataInicio && (!dataFim || dataFim < dataInicio)) {
            document.getElementById('data_fim').value = dataInicio;
        }
    });
    
    // Validação de idade
    function validateAges() {
        const minima = parseInt(document.getElementById('idade_minima').value) || 0;
        const maxima = parseInt(document.getElementById('idade_maxima').value) || 0;
        
        if (minima > 0 && maxima > 0 && minima > maxima) {
            SweetAlert.warning(
                'Atenção!',
                'A idade mínima não pode ser maior que a idade máxima.'
            );
        }
    }
    
    document.getElementById('idade_minima').addEventListener('change', validateAges);
    document.getElementById('idade_maxima').addEventListener('change', validateAges);

    // Form validation
    document.getElementById('evento-form').addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validar se pelo menos um modo de pagamento está selecionado
        const pagamentos = ['aceita_dinheiro', 'aceita_cartao', 'aceita_pix', 'aceita_gratuito'];
        const algumPagamentoSelecionado = pagamentos.some(id => {
            const element = document.getElementById(id);
            return element && element.checked;
        });
        
        if (!algumPagamentoSelecionado) {
            e.preventDefault();
            SweetAlert.error(
                'Erro de validação!',
                'Selecione pelo menos um modo de pagamento.'
            );
            
            // Mudar para a aba de pagamentos
            const pagamentoTab = document.querySelector('[data-tab="pagamento"]');
            if (pagamentoTab) pagamentoTab.click();
            return;
        }
        
        // Validação de campos obrigatórios
        const requiredFields = ['titulo', 'local', 'cidade', 'data_inicio', 'data_fim', 'capacidade', 'status'];
        for (const field of requiredFields) {
            const element = document.getElementById(field);
            if (!element || !element.value.trim()) {
                e.preventDefault();
                SweetAlert.error(
                    'Campos obrigatórios!',
                    `O campo "${field}" é obrigatório e deve ser preenchido.`
                );
                
                // Focar no primeiro campo vazio e ir para primeira aba
                if (element) {
                    const dadosTab = document.querySelector('[data-tab="dados"]');
                    if (dadosTab) dadosTab.click();
                    element.focus();
                }
                return;
            }
        }
        
        // Validação de capacidade
        const capacidade = document.getElementById('capacidade');
        if (capacidade && parseInt(capacidade.value) <= 0) {
            e.preventDefault();
            SweetAlert.error(
                'Capacidade inválida!',
                'A capacidade deve ser maior que zero.'
            );
            capacidade.focus();
            return;
        }
        
        // Validação de idades
        const idadeMin = parseInt(document.getElementById('idade_minima').value) || 0;
        const idadeMax = parseInt(document.getElementById('idade_maxima').value) || 0;
        
        if (idadeMin > 0 && idadeMax > 0 && idadeMin > idadeMax) {
            e.preventDefault();
            SweetAlert.error(
                'Idades inválidas!',
                'A idade mínima não pode ser maior que a máxima.'
            );
            return;
        }
    });
});
</script>

<script>
// Máscara de dinheiro para Real brasileiro
function formatMoeda(input) {
    let valor = input.value.replace(/\D/g, ''); // Remove tudo exceto números
    
    if (valor === '') {
        input.value = '';
        return;
    }
    
    // Converte para centavos e formata
    valor = (parseInt(valor) / 100).toFixed(2);
    
    // Adiciona separadores de milhares e decimais
    valor = valor.replace('.', ',');
    valor = valor.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    
    input.value = valor;
}

// Função para obter valor numérico limpo (para envio ao servidor)
function getCleanValue(input) {
    return input.value.replace(/\./g, '').replace(',', '.');
}

document.addEventListener('DOMContentLoaded', function() {
    const precoPadraoInput = document.getElementById('preco_padrao');
    
    if (precoPadraoInput) {
        // Aplicar máscara enquanto digita
        precoPadraoInput.addEventListener('input', function(e) {
            formatMoeda(e.target);
        });
        
        // Antes de enviar o form, converter para formato numérico
        document.getElementById('evento-form').addEventListener('submit', function(e) {
            const valorLimpo = getCleanValue(precoPadraoInput);
            
            // Criar campo hidden com valor limpo para envio
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'preco_padrao_clean';
            hiddenInput.value = valorLimpo;
            this.appendChild(hiddenInput);
            
            // Temporariamente alterar o valor do campo visível
            const valorOriginal = precoPadraoInput.value;
            precoPadraoInput.value = valorLimpo;
            
            // Restaurar valor formatado após 100ms (caso submission falhe)
            setTimeout(() => {
                if (precoPadraoInput) {
                    precoPadraoInput.value = valorOriginal;
                }
            }, 100);
        });
    }
});
</script>

@endpush
@endsection