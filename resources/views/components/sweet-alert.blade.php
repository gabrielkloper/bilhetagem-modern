{{-- 
    SweetAlert2 Component
    Inclui o SweetAlert2 e utilitários customizados
    
    Uso: <x-sweet-alert />
    
    Opções:
    - include-utils: Incluir arquivo de utilitários (default: true)
    - version: Versão do SweetAlert2 (default: 11)
--}}

@props([
    'includeUtils' => true,
    'version' => '11'
])

<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@{{ $version }}/dist/sweetalert2.min.css" rel="stylesheet">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@{{ $version }}"></script>

@if($includeUtils)
<!-- SweetAlert Config -->
<script src="{{ asset('js/sweetalert-config.js') }}"></script>
@endif

{{-- Slot para scripts adicionais --}}
{{ $slot ?? '' }}