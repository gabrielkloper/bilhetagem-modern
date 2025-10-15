@extends('admin.layout')

@section('title', 'Editar Usuário')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Editar Usuário</h1>
            <p class="mt-1 text-sm text-gray-500">
                Edite as informações de {{ $usuario->name }}
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.usuarios.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                <i class="fas fa-arrow-left -ml-1 mr-2 h-4 w-4"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST" id="user-form">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nome completo</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $usuario->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-300 @enderror" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $usuario->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('email') border-red-300 @enderror" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Função -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Função</label>
                        <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('role') border-red-300 @enderror" required>
                            <option value="">Selecione uma função</option>
                            <option value="admin" {{ old('role', $usuario->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="supervisor" {{ old('role', $usuario->role) == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                            <option value="operador" {{ old('role', $usuario->role) == 'operador' ? 'selected' : '' }}>Operador</option>
                            <option value="caixa" {{ old('role', $usuario->role) == 'caixa' ? 'selected' : '' }}>Caixa</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('status') border-red-300 @enderror" required>
                            <option value="">Selecione um status</option>
                            <option value="ativo" {{ old('status', $usuario->status) == 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ old('status', $usuario->status) == 'inativo' ? 'selected' : '' }}>Inativo</option>
                            <option value="suspenso" {{ old('status', $usuario->status) == 'suspenso' ? 'selected' : '' }}>Suspenso</option>
                            <option value="bloqueado" {{ old('status', $usuario->status) == 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Evento -->
                    <div class="md:col-span-2">
                        <label for="evento_id" class="block text-sm font-medium text-gray-700">Evento</label>
                        <select name="evento_id" id="evento_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('evento_id') border-red-300 @enderror">
                            <option value="">Todos os eventos</option>
                            @foreach($eventos as $evento)
                                <option value="{{ $evento->id }}" {{ old('evento_id', $usuario->evento_id) == $evento->id ? 'selected' : '' }}>
                                    {{ $evento->titulo }}
                                </option>
                            @endforeach
                        </select>
                        @error('evento_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Deixe em branco para dar acesso a todos os eventos
                        </p>
                    </div>

                    <!-- Senha -->
                    <div class="md:col-span-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">Nova Senha</label>
                        <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('password') border-red-300 @enderror" minlength="6">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Deixe em branco para manter a senha atual
                        </p>
                    </div>
                </div>

                <!-- User Info -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                        <div>
                            <strong>Criado em:</strong><br>
                            {{ $usuario->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div>
                            <strong>Última atualização:</strong><br>
                            {{ $usuario->updated_at->format('d/m/Y H:i') }}
                        </div>
                        <div>
                            <strong>E-mail verificado:</strong><br>
                            @if($usuario->email_verified_at)
                                <span class="text-green-600">
                                    <i class="fas fa-check-circle"></i> 
                                    {{ $usuario->email_verified_at->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="text-red-600">
                                    <i class="fas fa-times-circle"></i> 
                                    Não verificado
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.usuarios.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save -ml-1 mr-2 h-4 w-4"></i>
                        Atualizar Usuário
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('#user-form').submit(function(e) {
        let isValid = true;
        
        // Clear previous errors
        $('.border-red-300').removeClass('border-red-300');
        $('.text-red-600').remove();
        
        // Name validation
        const name = $('#name').val().trim();
        if (!name) {
            showError('#name', 'Nome é obrigatório');
            isValid = false;
        }
        
        // Email validation
        const email = $('#email').val().trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email) {
            showError('#email', 'E-mail é obrigatório');
            isValid = false;
        } else if (!emailRegex.test(email)) {
            showError('#email', 'E-mail inválido');
            isValid = false;
        }
        
        // Role validation
        if (!$('#role').val()) {
            showError('#role', 'Função é obrigatória');
            isValid = false;
        }
        
        // Status validation
        if (!$('#status').val()) {
            showError('#status', 'Status é obrigatório');
            isValid = false;
        }
        
        // Password validation (if provided)
        const password = $('#password').val();
        if (password && password.length < 6) {
            showError('#password', 'Senha deve ter pelo menos 6 caracteres');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            SweetAlert.error(
                'Dados inválidos!',
                'Por favor, corrija os campos destacados antes de continuar.'
            );
        }
    });
    
    function showError(selector, message) {
        $(selector).addClass('border-red-300');
        $(selector).parent().append(`<p class="mt-1 text-sm text-red-600">${message}</p>`);
    }
});
</script>
@endpush
@endsection