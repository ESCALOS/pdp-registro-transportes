<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => $title ?? config('app.name')])
</head>
<body class="antialiased">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{ $slot }}
</div>
</body>
</html>
