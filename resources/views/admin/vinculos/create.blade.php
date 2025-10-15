@extends('admin.layout')

@section('title', 'Novo Vínculo')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Novo Vínculo</h1>
            <p class="mt-1 text-sm text-gray-500">Criar um novo tipo de vínculo no sistema</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:flex-none">
            <a href="{{ route('admin.vinculos.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Voltar
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.vinculos.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="px-4 py-5 sm:p-6 space-y-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <!-- Descrição -->
                    <div class="sm:col-span-1">
                        <label for="descricao" class="block text-sm font-medium text-gray-700">
                            Descrição <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="descricao" id="descricao" value="{{ old('descricao') }}" 
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('descricao') border-red-300 @enderror"
                                placeholder="Ex: Criança, Adolescente, Cônjuge..."
                                maxlength="100">
                        </div>
                        @error('descricao')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">Máximo 100 caracteres</p>
                    </div>

                    <!-- Status -->
                    <div class="sm:col-span-1">
                        <label for="ativo" class="block text-sm font-medium text-gray-700">Status</label>
                        <div class="mt-1">
                            <div class="flex items-center">
                                <input type="checkbox" name="ativo" id="ativo" value="1" {{ old('ativo', true) ? 'checked' : '' }}
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                <label for="ativo" class="ml-2 block text-sm text-gray-900">
                                    Vínculo ativo (disponível para uso)
                                </label>
                            </div>
                        </div>
                        @error('ativo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Help Text -->
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Sobre os vínculos</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Os vínculos definem o tipo de relacionamento entre o responsável e os vinculados. Exemplos comuns:</p>
                                <ul class="list-disc list-inside mt-2 space-y-1">
                                    <li><strong>Criança:</strong> Para crianças de 0-12 anos</li>
                                    <li><strong>Adolescente:</strong> Para jovens de 13-17 anos</li>
                                    <li><strong>Adulto:</strong> Para pessoas de 18-59 anos</li>
                                    <li><strong>Idoso:</strong> Para pessoas com 60+ anos</li>
                                    <li><strong>PCD:</strong> Para pessoas com deficiência</li>
                                    <li><strong>Cônjuge:</strong> Para parceiros/esposos</li>
                                    <li><strong>Familiar:</strong> Para outros familiares</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 space-x-3">
                <a href="{{ route('admin.vinculos.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Salvar Vínculo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection