<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sewa Lapangan Olahraga Online') - Sport Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 font-sans antialiased flex flex-col min-h-screen">

    <!-- Header / Navbar -->
    @include('partials.public-navbar')

    <!-- Main Body Content -->
    <main class="flex-1">
        @include('components.alert')
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-10 border-t border-slate-800 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs">
            <div>
                <p class="font-bold text-slate-200 text-sm mb-1">SPORT CENTER INDONESIA</p>
                <p>© {{ date('Y') }} Sport Center System. All rights reserved.</p>
            </div>
            <div class="flex gap-6">
                <a href="#" class="hover:text-white transition">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-white transition">Kebijakan Privasi</a>
                <a href="#" class="hover:text-white transition">Bantuan / FAQ</a>
            </div>
        </div>
    </footer>

</body>
</html>