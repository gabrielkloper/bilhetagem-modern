{{-- 
    EXEMPLO PRÁTICO: Como implementar SweetAlert em uma nova página
    
    Este arquivo demonstra todas as formas de uso do SweetAlert implementado no projeto.
    Cole este código em qualquer nova view e adapte conforme necessário.
--}}

@extends('admin.layout')

@section('title', 'Exemplo SweetAlert')

{{-- 1. SEMPRE incluir o componente SweetAlert no head --}}
@push('head')
    <x-sweet-alert />
@endpush

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Exemplos SweetAlert</h1>
    
    {{-- 2. FORMULÁRIO COM CONFIRMAÇÃO AUTOMÁTICA --}}
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium mb-4">Formulário com Confirmação Automática</h2>
        
        {{-- O atributo data-confirm ativa a confirmação automática --}}
        <form method="POST" action="/exemplo/salvar" 
              data-confirm="Confirma o salvamento dos dados do usuário?">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nome</label>
                <input type="text" name="nome" required 
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            
            <button type="submit" 
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                Salvar
            </button>
        </form>
    </div>
    
    {{-- 3. BOTÕES DE EXCLUSÃO COM CONFIRMAÇÃO AUTOMÁTICA --}}
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium mb-4">Botões de Exclusão</h2>
        
        <div class="space-y-4">
            {{-- Exclusão com link simples --}}
            <a href="/exemplo/delete/1" 
               data-action="delete" 
               data-confirm="O usuário João será excluído permanentemente!"
               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                Excluir com Link
            </a>
            
            {{-- Exclusão com AJAX --}}
            <button data-action="delete"
                    data-url="/api/users/1" 
                    data-method="DELETE"
                    data-redirect="/usuarios"
                    data-confirm="Este usuário será excluído permanentemente!"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                Excluir com AJAX
            </button>
        </div>
    </div>
    
    {{-- 4. BOTÕES COM JAVASCRIPT MANUAL --}}
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium mb-4">JavaScript Manual</h2>
        
        <div class="space-y-4">
            <button onclick="exemploConfirmacao()" 
                    class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Confirmação Personalizada
            </button>
            
            <button onclick="exemploSucesso()" 
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                Mostrar Sucesso
            </button>
            
            <button onclick="exemploErro()" 
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                Mostrar Erro
            </button>
            
            <button onclick="exemploLoading()" 
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                Loading
            </button>
            
            <button onclick="exemploToast()" 
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                Toast
            </button>
        </div>
    </div>
    
    {{-- 5. LISTA COM AÇÕES --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg font-medium">Lista com Ações SweetAlert</h2>
        </div>
        
        <div class="border-t border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            João Silva
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Ativo
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="/usuarios/1" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                            <a href="/usuarios/1/edit" class="text-green-600 hover:text-green-900">Editar</a>
                            <button onclick="alterarStatus(1, true)" 
                                    class="text-orange-600 hover:text-orange-900">
                                Desativar
                            </button>
                            <button data-action="delete"
                                    data-url="/api/usuarios/1"
                                    data-method="DELETE"
                                    data-redirect="/usuarios"
                                    data-confirm="O usuário João Silva será excluído!"
                                    class="text-red-600 hover:text-red-900">
                                Excluir
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 6. MENSAGENS DE SESSÃO AUTOMÁTICAS --}}
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            SweetAlertUtils.showSuccess('Sucesso!', '{{ session('success') }}');
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            SweetAlertUtils.showError('Erro!', '{{ session('error') }}');
        });
    </script>
@endif

@if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            SweetAlertUtils.showError('Erro de Validação', 
                {!! json_encode(implode('\n', $errors->all())) !!}
            );
        });
    </script>
@endif
@endsection

{{-- 7. SCRIPTS PERSONALIZADOS --}}
@push('scripts')
<script>
// Exemplos de funções JavaScript personalizadas

async function exemploConfirmacao() {
    const result = await SweetAlertUtils.confirmSave(
        'Salvar Configurações',
        'As configurações do sistema serão atualizadas. Continuar?'
    );
    
    if (result.isConfirmed) {
        console.log('Usuário confirmou!');
        // Executar ação aqui
    }
}

function exemploSucesso() {
    SweetAlertUtils.showSuccess(
        'Operação Concluída!',
        'Os dados foram salvos com sucesso no sistema.'
    );
}

function exemploErro() {
    SweetAlertUtils.showError(
        'Falha na Operação',
        'Não foi possível conectar com o servidor. Tente novamente.'
    );
}

async function exemploLoading() {
    // Mostrar loading
    SweetAlertUtils.showLoading('Processando...', 'Salvando dados no servidor...');
    
    // Simular operação assíncrona
    await new Promise(resolve => setTimeout(resolve, 2000));
    
    // Esconder loading e mostrar sucesso
    SweetAlertUtils.hideLoading();
    SweetAlertUtils.showSuccess('Concluído!', 'Dados processados com sucesso!');
}

function exemploToast() {
    SweetAlertUtils.showToast('Notificação discreta!', 'info');
}

async function alterarStatus(userId, isActive) {
    const action = isActive ? 'desativar' : 'ativar';
    const result = await SweetAlertUtils.confirmStatusChange(
        `${action.charAt(0).toUpperCase() + action.slice(1)} Usuário`,
        `Confirma ${action} o usuário?`
    );
    
    if (result.isConfirmed) {
        // Aqui faria a requisição AJAX
        console.log(`${action} usuário ${userId}`);
        SweetAlertUtils.showToast(`Usuário ${action}do!`, 'success');
    }
}

// Exemplo de validação personalizada
document.getElementById('form-exemplo')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const valid = SweetAlertUtils.validateForm(e.target, {
        nome: {
            required: true,
            minLength: 3,
            label: 'Nome'
        },
        email: {
            required: true,
            email: true,
            label: 'E-mail'
        }
    });
    
    if (valid) {
        const result = await SweetAlertUtils.confirmSave();
        if (result.isConfirmed) {
            // Submeter formulário
            e.target.submit();
        }
    }
});
</script>
@endpush