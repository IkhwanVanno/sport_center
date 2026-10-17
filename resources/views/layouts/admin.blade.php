<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Sport Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 font-sans antialiased" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen overflow-hidden">
        @include('partials.admin-sidebar')

        <div class="flex-1 flex flex-col overflow-y-auto">
            <header class="bg-white shadow-sm p-4 flex justify-between items-center border-b border-gray-200">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 focus:outline-none hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-700">Halo, <strong>{{ Auth::user()->name ?? 'Admin' }}</strong></span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 font-semibold hover:underline">Logout</button>
                    </form>
                </div>
            </header>

            <main class="p-6">
                @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-lg text-sm font-medium">
                    {{ session('success') }}
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>