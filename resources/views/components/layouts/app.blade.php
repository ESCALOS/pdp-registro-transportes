<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => $title ?? config('app.name')])
</head>
<body class="antialiased bg-secondary-500">
<header class="w-full text-white py-4" style="background-color: #8B2D23;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 justify-between flex items-center">
        <h1 class="text-xl font-bold">Autogestión Paracas</h1>
        <div class="flex items-center space-x-4">
            @auth
                <div class="text-right">
                    <div class="font-medium">{{ auth()->user()->full_name }}</div>
                    @if(auth()->user()->is_company_representative)
                        <div class="text-sm opacity-90">Representante - {{ optional(auth()->user()->company)->business_name }}</div>
                    @else
                    <div class="text-sm opacity-90">{{ optional(auth()->user()->company)->business_name }}</div>
                    @endif
                </div>

                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button aria-label="Cerrar sesión" class="topbar-logout-button">
                        Cerrar sesión
                    </button>
                </form>
            @else
                <img src="{{ asset('images/logo-pdp.webp') }}" alt="{{ config('app.name') }} Logo" class="h-12" />
            @endauth
        </div>
    </div>
</header>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    {{ $slot }}
</div>
@filamentScripts
</body>
</html>
