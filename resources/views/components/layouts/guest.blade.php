<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => $title ?? config('app.name')])
</head>
<body class="antialiased bg-secondary-500">
<header class="w-full bg-primary-500 text-white py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 justify-between flex items-center">
        <h1 class="text-xl font-bold">{{ config('app.name') }}</h1>
        <img src="{{ asset('images/logo-pdp.webp') }}" alt="{{ config('app.name') }} Logo" class="h-12" />
    </div>
</header>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{ $slot }}
</div>
@filamentScripts
</body>
</html>
