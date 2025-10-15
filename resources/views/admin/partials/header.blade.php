<div class="sticky top-0 z-10 flex h-16 flex-shrink-0 bg-white shadow">
    <!-- Mobile menu button -->
    <button type="button" class="border-r border-gray-200 px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 lg:hidden" onclick="toggleMobileMenu()">
        <span class="sr-only">Abrir sidebar</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    </button>

    <div class="flex flex-1 justify-between px-4">
        <!-- Breadcrumb / Page Title -->
        <div class="flex items-center">
            <div>
                <nav class="flex" aria-label="Breadcrumb">
                    <ol role="list" class="flex items-center space-x-4">
                        <li>
                            <div class="flex">
                                <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                                    Admin
                                </a>
                            </div>
                        </li>
                        @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
                            @foreach($breadcrumbs as $breadcrumb)
                                <li>
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                        </svg>
                                        @if(!empty($breadcrumb['url']))
                                            <a href="{{ $breadcrumb['url'] }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                                {{ $breadcrumb['title'] }}
                                            </a>
                                        @else
                                            <span class="ml-4 text-sm font-medium text-gray-900">{{ $breadcrumb['title'] }}</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ol>
                </nav>
                @if(isset($pageTitle))
                    <h1 class="mt-2 text-2xl font-semibold text-gray-900">{{ $pageTitle }}</h1>
                @endif
            </div>
        </div>

        <!-- Right side -->
        <div class="ml-4 flex items-center md:ml-6">
            <!-- Notifications -->
            <button type="button" class="rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <span class="sr-only">Ver notificações</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
                <span class="absolute -top-0.5 -right-0.5 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
            </button>

            <!-- Event selector -->
            <div class="ml-3 relative">
                <div class="flex items-center">
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">Evento Ativo</p>
                                <p class="text-xs text-gray-500">{{ session('evento_titulo', 'Nenhum evento selecionado') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile dropdown -->
            <div class="relative ml-3">
                <div>
                    <button type="button" class="flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                        <span class="sr-only">Abrir menu do usuário</span>
                        <div class="h-8 w-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                    </button>
                </div>

                <!-- Dropdown menu (hidden by default) -->
                <div class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" id="user-menu">
                    <div class="px-4 py-2 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Usuário' }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                    </div>
                    
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Meu Perfil</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Configurações</a>
                    <form method="POST" action="{{ route('admin.logout') }}" class="block">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle user menu dropdown
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');
        
        if (userMenuButton && userMenu) {
            userMenuButton.addEventListener('click', function() {
                userMenu.classList.toggle('hidden');
            });
            
            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        }
    });
</script>