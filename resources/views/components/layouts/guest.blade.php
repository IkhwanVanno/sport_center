<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sport Center - Penyewaan Lapangan Olahraga Terpercaya. Booking Futsal, Badminton, Basket, Voli, Tenis mudah & cepat.">
    <meta name="theme-color" content="#16a34a">
    <title>{{ config('app.name', 'Sport Center') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="font-sans text-court-950 antialiased bg-white min-h-screen flex flex-col">
    @include('components.navbar')

    <main class="flex-1">
        {{ $slot }}
    </main>

    @include('components.footer')

    @stack('scripts')
</body>
</html>