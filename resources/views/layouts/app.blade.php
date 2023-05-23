<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <link rel="shortcut icon" href="{{ asset('images/favicon.svg') }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Javascript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script>

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    @livewireStyles
    @stack('styles')
</head>

<body class="font-sans antialiased">

<div class="min-h-screen bg-gray-100">
    @include('pages.partials.nav')
    <!-- Page Content -->
    <main class="bg-white shadow max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 my-2">
        {{ $slot }}
    </main>
</div>

    @stack('scripts')
    @livewireScripts
    @livewire('livewire-ui-modal')
</body>
</html>
