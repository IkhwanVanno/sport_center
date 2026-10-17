<aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-slate-900 text-slate-300 transition-all duration-300 flex flex-col justify-between shrink-0">
    <div>
        <div class="p-4 flex items-center justify-between border-b border-slate-800">
            <span x-show="sidebarOpen" class="font-bold text-lg text-white tracking-wide">SPORT CENTER</span>
            <span x-show="!sidebarOpen" class="font-bold text-lg text-white mx-auto">SC</span>
        </div>

        <nav class="mt-4 px-2 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-800 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span x-show="sidebarOpen">Dashboard</span>
            </a>

            <div x-show="sidebarOpen" class="pt-4 pb-1 px-3 text-xs font-semibold text-slate-500 uppercase">Master Data</div>

            <a href="{{ route('admin.branches.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium hover:bg-slate-800 hover:text-white {{ request()->routeIs('admin.branches.*') ? 'bg-indigo-600 text-white' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span x-show="sidebarOpen">Cabang</span>
            </a>

            <a href="{{ route('admin.courts.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium hover:bg-slate-800 hover:text-white {{ request()->routeIs('admin.courts.*') ? 'bg-indigo-600 text-white' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span x-show="sidebarOpen">Lapangan</span>
            </a>

            <a href="{{ route('admin.price-rules.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium hover:bg-slate-800 hover:text-white {{ request()->routeIs('admin.price-rules.*') ? 'bg-indigo-600 text-white' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span x-show="sidebarOpen">Aturan Tarif</span>
            </a>

            <div x-show="sidebarOpen" class="pt-4 pb-1 px-3 text-xs font-semibold text-slate-500 uppercase">Transaksi</div>

            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium hover:bg-slate-800 hover:text-white {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span x-show="sidebarOpen">Pesanan / Booking</span>
            </a>

            <a href="{{ route('admin.reports.revenue') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium hover:bg-slate-800 hover:text-white {{ request()->routeIs('admin.reports.revenue') ? 'bg-indigo-600 text-white' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span x-show="sidebarOpen">Laporan Pendapatan</span>
            </a>
        </nav>
    </div>
</aside>