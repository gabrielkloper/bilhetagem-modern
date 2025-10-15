@extends('admin.layout')

@section('title', 'Editar Vínculo')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Editar Vínculo</h1>
            <p class="mt-1 text-sm text-gray-500">Editando: {{ $vinculo->descricao }}</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:flex-none space-x-3">
            <a href="{{ route('admin.vinculos.show', $vinculo) }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Visualizar
            </a>
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
        <form action="{{ route('admin.vinculos.update', $vinculo) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="px-4 py-5 sm:p-6 space-y-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <!-- Descrição -->
                    <div class="sm:col-span-1">
                        <label for="descricao" class="block text-sm font-medium text-gray-700">
                            Descrição <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="descricao" id="descricao" value="{{ old('descricao', $vinculo->descricao) }}" 
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
                                <input type="checkbox" name="ativo" id="ativo" value="1" {{ old('ativo', $vinculo->ativo) ? 'checked' : '' }}
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

                <!-- Usage Warning -->
                @if($vinculo->vinculados_count > 0)
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Vínculo em uso</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Este vínculo está sendo usado por {{ $vinculo->vinculados_count }} vinculado(s). Alterações na descrição afetarão todos os registros existentes.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

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
                                <p>Os vínculos definem o tipo de relacionamento entre o responsável e os vinculados. Ao desativar um vínculo, ele não ficará disponível para novos cadastros, mas os registros existentes permanecerão inalterados.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Audit Info -->
                <div class="border-t border-gray-200 pt-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vinculo->created_at->format('d/m/Y \à\s H:i:s') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Última atualização</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vinculo->updated_at->format('d/m/Y \à\s H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 space-x-3">
                <a href="{{ route('admin.vinculos.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
@endsection