<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Operator Dashboard') - Sport Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans antialiased" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Operator -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-slate-900 text-slate-300 transition-all duration-300 flex flex-col justify-between shrink-0">
            <div>
                <!-- Logo Header -->
                <div class="p-4 flex items-center justify-between border-b border-slate-800">
                    <span x-show="sidebarOpen" class="font-bold text-lg text-white tracking-wide">SPORT CENTER</span>
                    <span x-show="!sidebarOpen" class="font-bold text-lg text-white mx-auto">SC</span>
                </div>

                <!-- Navigasi Menu Operator -->
                <nav class="mt-4 px-2 space-y-1">
                    <a href="{{ route('operator.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('operator.dashboard') ? 'bg-indigo-600 text-white' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span x-show="sidebarOpen">Dashboard</span>
                    </a>

                    <div x-show="sidebarOpen" class="pt-4 pb-1 px-3 text-xs font-semibold text-slate-500 uppercase">Operasional</div>

                    <a href="#" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium hover:bg-slate-800 hover:text-white transition">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span x-show="sidebarOpen">Walk-In Booking</span>
                    </a>

                    <a href="#" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium hover:bg-slate-800 hover:text-white transition">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <span x-show="sidebarOpen">Scan / Check-In</span>
                    </a>

                    <a href="#" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium hover:bg-slate-800 hover:text-white transition">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span x-show="sidebarOpen">Jadwal Hari Ini</span>
                    </a>
                </nav>
            </div>

            <!-- Cabang Tag di bagian bawah Sidebar -->
            <div x-show="sidebarOpen" class="p-4 border-t border-slate-800 text-xs text-slate-400">
                <p class="font-semibold text-slate-300">Lokasi Penugasan:</p>
                <p class="mt-0.5 text-indigo-400 font-medium">{{ Auth::user()->branch->name ?? 'Cabang MERR Surabaya' }}</p>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- Topbar (Persis seperti Admin) -->
            <header class="bg-white shadow-sm p-4 flex justify-between items-center border-b border-gray-200">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 focus:outline-none hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-700">
                        Halo, <strong>{{ Auth::user()->name }}</strong> (OPERATOR)
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 font-semibold hover:underline">Logout</button>
                    </form>
                </div>
            </header>

            <!-- Main Body Content -->
            <main class="p-6">
                @include('components.alert')
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>