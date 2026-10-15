<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Masuk atau daftar akun Sport Center untuk booking lapangan olahraga favoritmu.">
    <meta name="theme-color" content="#16a34a">
    <title>{{ $title ?? 'Sport Center' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="font-sans text-court-950 antialiased">
    <div class="flex min-h-screen">

        {{-- ============ LEFT: Branding Panel ============ --}}
        <aside class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-gradient-to-br from-court-950 via-brand-900 to-brand-700 p-10 lg:flex xl:p-14">
            {{-- decorative --}}
            <div class="pointer-events-none absolute inset-0" aria-hidden="true">
                <div class="absolute -top-32 -right-32 h-96 w-96 rounded-full bg-brand-500/20 blur-3xl"></div>
                <div class="absolute -bottom-32 -left-32 h-96 w-96 rounded-full bg-energy-500/20 blur-3xl"></div>
                <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.04)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.04)_1px,transparent_1px)] bg-[size:3rem_3rem] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_40%,#000_60%,transparent_100%)]"></div>
            </div>

            {{-- Logo --}}
            <a href="{{ Route::has('home') ? route('home') : '/' }}" class="relative flex items-center gap-2.5 text-white">
                <svg class="h-9 w-9 text-brand-400" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                    <rect width="32" height="32" rx="8" fill="currentColor"/>
                    <path d="M8 16L14 22L24 10" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="font-display text-xl font-bold">Sport Center</span>
            </a>

            {{-- Center content --}}
            <div class="relative my-auto max-w-md">
                <h1 class="font-display text-4xl font-bold leading-tight text-white text-balance xl:text-5xl">
                    Lapangan siap, tinggal kamu datang.
                </h1>
                <p class="mt-5 text-lg leading-relaxed text-brand-100/80">
                    Bergabung dengan ribuan pemain yang sudah beralih ke booking online. Cepat, aman, tanpa antri.
                </p>

                {{-- mini feature list --}}
                <ul class="mt-10 space-y-4" role="list">
                    @foreach ([
                        'Cek ketersediaan real-time',
                        'Anti double booking',
                        'E-ticket dengan QR code',
                        'Bayar QRIS, VA, atau transfer',
                    ] as $item)
                        <li class="flex items-center gap-3 text-brand-50/90">
                            <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-brand-500/30">
                                <svg class="h-3.5 w-3.5 text-brand-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Floating mini card --}}
            <div class="relative mt-10 flex items-center gap-4 rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-md">
                <div class="flex -space-x-2">
                    @foreach (['bg-brand-400', 'bg-energy-400', 'bg-brand-300'] as $c)
                        <span class="inline-block h-9 w-9 rounded-full border-2 border-brand-900 {{ $c }}"></span>
                    @endforeach
                </div>
                <div>
                    <p class="text-sm font-semibold text-white">1.200+ booking bulan ini</p>
                    <p class="text-xs text-brand-200/80">dan terus bertambah tiap hari</p>
                </div>
            </div>
        </aside>

        {{-- ============ RIGHT: Form Panel ============ --}}
        <main class="flex w-full flex-col justify-center bg-white px-6 py-10 sm:px-10 lg:w-1/2 xl:px-20">
            {{-- mobile logo --}}
            <a href="{{ Route::has('home') ? route('home') : '/' }}" class="mb-8 flex items-center gap-2.5 lg:hidden">
                <svg class="h-8 w-8 text-brand-600" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                    <rect width="32" height="32" rx="8" fill="currentColor"/>
                    <path d="M8 16L14 22L24 10" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="font-display text-lg font-bold text-court-900">Sport Center</span>
            </a>

            <div class="mx-auto w-full max-w-md animate-slide-up">
                {{ $slot }}
            </div>

            {{-- footer note --}}
            <p class="mx-auto mt-10 w-full max-w-md text-center text-sm text-court-400">
                &copy; {{ date('Y') }} Sport Center. Dengan melanjutkan, kamu setuju dengan
                <a href="#" class="link-base">Syarat</a> & <a href="#" class="link-base">Kebijakan Privasi</a>.
            </p>
        </main>
    </div>

    @stack('scripts')
</body>
</html>