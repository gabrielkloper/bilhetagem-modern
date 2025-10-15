# SweetAlert2 Implementation Guide

Este documento descreve como implementar e usar SweetAlert2 no projeto de forma padronizada.

## Arquivos Criados

### 1. Utilitários JavaScript (`public/js/sweetalert-utils.js`)
Classe `SweetAlertUtils` com funções pré-configuradas para casos comuns.

### 2. Componente Blade (`resources/views/components/sweet-alert.blade.php`)
Componente reutilizável para incluir SweetAlert2 em qualquer página.

## Como Implementar em uma Nova Página

### 1. Incluir o Componente

No topo da sua view Blade, adicione:

```blade
@push('head')
    <x-sweet-alert />
@endpush
```

### 2. Uso Automático (Recomendado)

#### Confirmação de Formulário
Adicione o atributo `data-confirm` no formulário:

```blade
<form method="POST" action="..." data-confirm="Confirma o salvamento dos dados?">
    <!-- campos do formulário -->
    <button type="submit">Salvar</button>
</form>
```

#### Confirmação de Exclusão
Adicione os atributos `data-action="delete"` e `data-confirm`:

```blade
<!-- Para links -->
<a href="/delete/1" data-action="delete" data-confirm="Este item será excluído permanentemente!">
    Excluir
</a>

<!-- Para botões com AJAX -->
<button data-action="delete" 
        data-url="/api/delete/1" 
        data-method="DELETE"
        data-redirect="/lista"
        data-confirm="Este item será excluído!">
    Excluir
</button>
```

### 3. Uso Manual (JavaScript)

#### Métodos Disponíveis

```javascript
// Confirmação de exclusão
const result = await SweetAlertUtils.confirmDelete('Título', 'Mensagem', 'Botão');
if (result.isConfirmed) {
    // Executar ação
}

// Confirmação de status
const result = await SweetAlertUtils.confirmStatusChange('Alterar Status', 'Confirma?');

// Confirmação de salvamento
const result = await SweetAlertUtils.confirmSave('Salvar', 'Confirma o salvamento?');

// Mensagem de sucesso
SweetAlertUtils.showSuccess('Sucesso!', 'Operação realizada!');

// Mensagem de erro
SweetAlertUtils.showError('Erro!', 'Algo deu errado!');

// Loading
SweetAlertUtils.showLoading('Processando...', 'Aguarde...');
SweetAlertUtils.hideLoading();

// Toast (notificação discreta)
SweetAlertUtils.showToast('Mensagem', 'success'); // success, error, warning, info
```

## Exemplos de Implementação

### Exemplo 1: Formulário de Cadastro

```blade
@push('head')
    <x-sweet-alert />
@endpush

<form method="POST" action="{{ route('users.store') }}" 
      data-confirm="Confirma o cadastro do usuário?">
    @csrf
    <input type="text" name="name" placeholder="Nome">
    <button type="submit">Cadastrar</button>
</form>
```

### Exemplo 2: Botão de Exclusão com AJAX

```blade
@push('head')
    <x-sweet-alert />
@endpush

<button data-action="delete"
        data-url="{{ route('users.destroy', $user) }}"
        data-method="DELETE"
        data-redirect="{{ route('users.index') }}"
        data-confirm="O usuário {{ $user->name }} será excluído permanentemente!"
        class="btn btn-danger">
    Excluir Usuário
</button>
```

### Exemplo 3: Validação Manual

```blade
@push('head')
    <x-sweet-alert />
@endpush

<form id="custom-form">
    <input type="text" name="email" required>
    <button type="submit">Enviar</button>
</form>

@push('scripts')
<script>
document.getElementById('custom-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    // Validação personalizada
    const valid = SweetAlertUtils.validateForm(e.target, {
        email: {
            required: true,
            email: true,
            label: 'E-mail'
        }
    });
    
    if (valid) {
        const result = await SweetAlertUtils.confirmSave();
        if (result.isConfirmed) {
            e.target.submit();
        }
    }
});
</script>
@endpush
```

## Configurações Padrão

As configurações padrão podem ser alteradas em `SweetAlertUtils.defaultConfig`:

- `confirmButtonColor: '#3085d6'` (azul)
- `cancelButtonColor: '#d33'` (vermelho)
- `confirmButtonText: 'Sim, confirmar!'`
- `cancelButtonText: 'Cancelar'`

## Personalização

Para casos especiais, use `SweetAlertUtils.confirmCustom()`:

```javascript
const result = await SweetAlertUtils.confirmCustom({
    title: 'Título Personalizado',
    text: 'Mensagem personalizada',
    icon: 'warning',
    confirmButtonText: 'Confirmar',
    confirmButtonColor: '#28a745',
    // ... outras opções do SweetAlert2
});
```

## Integração com Laravel

### Mensagens de Sessão

Para exibir mensagens vindas do Laravel automaticamente, adicione no layout:

```blade
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
```

### Validação de Formulários

Para erros de validação, o Laravel já exibe naturalmente. Mas você pode personalizar:

```blade
@if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            SweetAlertUtils.showError('Erro de Validação', 
                @json(implode('\n', $errors->all()))
            );
        });
    </script>
@endif
```

## Padrão de Implementação

Para manter consistência, **sempre use este padrão** em novas páginas:

1. Inclua `<x-sweet-alert />` no push('head')
2. Use atributos `data-confirm` para formulários
3. Use atributos `data-action="delete"` para exclusões  
4. Use métodos da classe `SweetAlertUtils` para casos específicos
5. Sempre teste a funcionalidade após implementação

## Exemplos de Páginas Implementadas

- ✅ `resources/views/admin/responsaveis/show.blade.php`
- ✅ `resources/views/admin/responsaveis/edit.blade.php` 
- ✅ `resources/views/admin/responsaveis/index.blade.php` (parcial)

Siga estes padrões para manter a consistência em todo o projeto.