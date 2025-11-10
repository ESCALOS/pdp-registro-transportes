<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="container mx-auto">
        <h1 class="text-4xl font-bold">{{ __('Welcome to :app', ['app' => config('app.name')]) }}</h1>
    </div>
</body>
</html>
