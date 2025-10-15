<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin') - Sistema de Bilhetagem</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/sweetalert-config.js') }}"></script>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional styles from views -->
    @stack('styles')
</head>
<body class="h-full font-sans antialiased bg-gray-50">
    <div id="app" class="min-h-full">
        <!-- Off-canvas menu for mobile -->
        <div class="fixed inset-0 z-40 flex lg:hidden" id="mobile-menu" style="display: none;">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75" aria-hidden="true"></div>
            <div class="relative flex w-full max-w-xs flex-1 flex-col bg-white">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button type="button" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" onclick="toggleMobileMenu()">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="h-0 flex-1 overflow-y-auto pt-5 pb-4">
                    @include('admin.partials.sidebar')
                </div>
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
            <div class="flex min-h-0 flex-1 flex-col border-r border-gray-200 bg-white">
                @include('admin.partials.sidebar')
            </div>
        </div>

        <!-- Main content area -->
        <div class="lg:pl-64 flex flex-col min-h-screen">
            <!-- Top navigation -->
            @include('admin.partials.header')

            <!-- Page content -->
            <main class="flex-1">
                <div class="py-6">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        @if(session('success'))
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    SweetAlert.toast('success', '{{ session('success') }}');
                                });
                            </script>
                        @endif

                        @if(session('error'))
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    SweetAlert.toast('error', '{{ session('error') }}');
                                });
                            </script>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            if (menu.style.display === 'none') {
                menu.style.display = 'flex';
            } else {
                menu.style.display = 'none';
            }
        }
    </script>
    
    <!-- Additional scripts from views -->
    @stack('scripts')
</body>
</html>