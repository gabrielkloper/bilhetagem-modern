<div class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4">
    <!-- Logo -->
    <div class="flex flex-shrink-0 items-center px-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                    </svg>
                </div>
            </div>
            <div class="ml-3">
                <h1 class="text-lg font-semibold text-gray-900">Bilhetagem</h1>
                <p class="text-xs text-gray-500">Sistema Admin</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-8 flex-1 space-y-1 px-2">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium border-l-4 rounded-md">
            <svg class="{{ request()->routeIs('admin.dashboard') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
            </svg>
            Dashboard
        </a>

        <!-- Entradas -->
        <div class="space-y-1">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2">
                Controle de Acesso
            </div>
            
            <a href="{{ route('admin.entradas.create') }}" class="{{ request()->routeIs('admin.entradas.*') ? 'bg-indigo-100 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium border-l-4 rounded-md">
                <svg class="{{ request()->routeIs('admin.entradas.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
                Nova Entrada
            </a>

            <a href="{{ route('admin.controle.index') }}" class="{{ request()->routeIs('admin.controle*') ? 'bg-indigo-100 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium border-l-4 rounded-md">
                <svg class="{{ request()->routeIs('admin.controle*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                </svg>
                Controle de Recreação
            </a>
        </div>

        <!-- Gestão -->
        <div class="space-y-1">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2 mt-6">
                Gestão
            </div>
            
            <a href="{{ route('admin.eventos.index') }}" class="{{ request()->routeIs('admin.eventos.*') ? 'bg-indigo-100 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium border-l-4 rounded-md">
                <svg class="{{ request()->routeIs('admin.eventos.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                </svg>
                Eventos
            </a>

            <a href="{{ route('admin.responsaveis.index') }}" class="{{ request()->routeIs('admin.responsaveis.*') ? 'bg-indigo-100 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium border-l-4 rounded-md">
                <svg class="{{ request()->routeIs('admin.responsaveis.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                Responsáveis
            </a>
        </div>

        <!-- Financeiro -->
        <div class="space-y-1">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2 mt-6">
                Financeiro
            </div>
            
            <a href="{{ route('admin.caixa.index') }}" class="{{ request()->routeIs('admin.caixa.*') ? 'bg-indigo-100 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium border-l-4 rounded-md">
                <svg class="{{ request()->routeIs('admin.caixa.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0119.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                </svg>
                Caixa
            </a>

            <a href="{{ route('admin.relatorios.index') }}" class="{{ request()->routeIs('admin.relatorios.*') ? 'bg-indigo-100 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium border-l-4 rounded-md">
                <svg class="{{ request()->routeIs('admin.relatorios.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                </svg>
                Relatórios
            </a>
        </div>

        <!-- Configurações -->
        <div class="space-y-1">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2 mt-6">
                Configurações
            </div>
            @if(auth()->user()->role === 'admin')   
            <a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios.*') ? 'bg-indigo-100 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium border-l-4 rounded-md">
                <svg class="{{ request()->routeIs('admin.usuarios.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
                Usuários
            </a>
            <a href="{{ route('admin.vinculos.index') }}" class="{{ request()->routeIs('admin.vinculos.*') ? 'bg-indigo-100 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium border-l-4 rounded-md">
                <svg class="{{ request()->routeIs('admin.vinculos.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                </svg>
                Vínculos
            </a>
            @endif
        </div>
    </nav>
</div>