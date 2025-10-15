@extends('admin.layout')

@section('title', 'Gestão de Caixa')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestão de Caixa</h1>
            <p class="text-gray-600">Controle financeiro e movimentações</p>
        </div>
        <div class="flex gap-3">
            <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nova Movimentação
            </button>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Fechar Caixa
            </button>
        </div>
    </div>

    <!-- Status do Caixa -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Saldo Atual</p>
                    <p class="text-2xl font-bold text-gray-900">R$ 12.450,75</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                <span class="text-green-600 text-sm font-medium">Caixa Aberto</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Entradas Hoje</p>
                    <p class="text-2xl font-bold text-green-600">R$ 8.750,00</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-gray-600 text-sm">127 transações</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Saídas Hoje</p>
                    <p class="text-2xl font-bold text-red-600">R$ 1.280,00</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-gray-600 text-sm">8 transações</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Resultado</p>
                    <p class="text-2xl font-bold text-green-600">R$ 7.470,00</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-green-600 text-sm font-medium">+12% vs ontem</span>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-left">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <h3 class="font-medium text-gray-900">Entrada</h3>
                <p class="text-sm text-gray-500">Registrar entrada</p>
            </button>

            <button class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-left">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                </div>
                <h3 class="font-medium text-gray-900">Saída</h3>
                <p class="text-sm text-gray-500">Registrar saída</p>
            </button>

            <button class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-left">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 6v6m0 0v-6m0 6l3-3m-3 3l-3-3" />
                    </svg>
                </div>
                <h3 class="font-medium text-gray-900">Sangria</h3>
                <p class="text-sm text-gray-500">Retirada de dinheiro</p>
            </button>

            <button class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-left">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                </div>
                <h3 class="font-medium text-gray-900">Relatório</h3>
                <p class="text-sm text-gray-500">Gerar relatório</p>
            </button>
        </div>
    </div>

    <!-- Formas de Pagamento -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Formas de Pagamento - Hoje</h3>
            <p class="text-gray-600 text-sm">Distribuição dos recebimentos por forma de pagamento</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900">Dinheiro</h4>
                    <p class="text-2xl font-bold text-green-600">R$ 4.850,00</p>
                    <p class="text-sm text-gray-500">55% do total</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900">Cartão</h4>
                    <p class="text-2xl font-bold text-blue-600">R$ 3.200,00</p>
                    <p class="text-sm text-gray-500">37% do total</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900">PIX</h4>
                    <p class="text-2xl font-bold text-purple-600">R$ 700,00</p>
                    <p class="text-sm text-gray-500">8% do total</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Movimentações Recentes -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Movimentações Recentes</h3>
                    <p class="text-gray-600 text-sm">Últimas transações registradas</p>
                </div>
                <div class="flex gap-2">
                    <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option>Todas</option>
                        <option>Entradas</option>
                        <option>Saídas</option>
                        <option>Sangrias</option>
                    </select>
                    <button class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                        Filtrar
                    </button>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horário</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Forma Pagamento</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operador</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">14:32</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Entrada</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Ingresso - Maria Silva
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Dinheiro</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600 text-right">+R$ 45,00</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">João Santos</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">14:28</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Entrada</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Pacote Premium - Pedro Costa  
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Cartão</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600 text-right">+R$ 120,00</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Ana Oliveira</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">14:15</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Saída</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Despesa - Material de limpeza
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Dinheiro</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600 text-right">-R$ 85,00</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Carlos Silva</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">14:02</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">Entrada</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Ingresso - Grupo 5 pessoas
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">PIX</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600 text-right">+R$ 225,00</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">João Santos</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">13:45</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">Sangria</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Sangria autorizada - Depósito
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Dinheiro</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-orange-600 text-right">-R$ 2.000,00</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Gerente</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">13:30</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Entrada</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Ingresso - Família Costa
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Cartão</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600 text-right">+R$ 180,00</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Ana Oliveira</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-500">Mostrando 6 de 127 movimentações</p>
                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Ver todas</button>
            </div>
        </div>
    </div>

    <!-- Conferência de Caixa -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Conferência de Caixa</h3>
            <p class="text-gray-600 text-sm">Confira os valores físicos com o sistema</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-900">Valores no Sistema</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Dinheiro:</span>
                            <span class="font-medium">R$ 4.850,00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Cartão:</span>
                            <span class="font-medium">R$ 3.200,00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">PIX:</span>
                            <span class="font-medium">R$ 700,00</span>
                        </div>
                        <div class="pt-2 border-t">
                            <div class="flex justify-between items-center font-semibold">
                                <span>Total Sistema:</span>
                                <span>R$ 8.750,00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="font-medium text-gray-900">Conferência Física</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Dinheiro:</span>
                            <input type="text" class="text-right border border-gray-300 rounded px-2 py-1 w-24" placeholder="0,00">
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Cartão:</span>
                            <input type="text" class="text-right border border-gray-300 rounded px-2 py-1 w-24" placeholder="0,00">
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">PIX:</span>
                            <input type="text" class="text-right border border-gray-300 rounded px-2 py-1 w-24" placeholder="0,00">
                        </div>
                        <div class="pt-2 border-t">
                            <div class="flex justify-between items-center font-semibold">
                                <span>Total Físico:</span>
                                <span class="text-blue-600">R$ 0,00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="font-medium text-gray-900">Diferença</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-400 mb-2">R$ 0,00</div>
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500">Informe os valores para conferir</p>
                        </div>
                    </div>
                    <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Conferir Caixa
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection