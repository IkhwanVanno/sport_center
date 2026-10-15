<x-layouts.guest>
    @push('head')
        <title>Sport Center — Booking Lapangan Olahraga Online</title>
    @endpush

    {{-- ============ HERO SECTION ============ --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-brand-50 via-white to-white pt-28 pb-16 sm:pt-32 lg:pt-40 lg:pb-24">
        {{-- Decorative mesh / blobs --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
            <div class="absolute -top-40 -right-32 h-[32rem] w-[32rem] rounded-full bg-brand-200/40 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-32 h-[28rem] w-[28rem] rounded-full bg-energy-200/40 blur-3xl"></div>
            <div class="absolute top-1/3 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-brand-100/50 blur-3xl"></div>
            {{-- subtle grid --}}
            <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(15,23,42,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(15,23,42,0.03)_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]"></div>
        </div>

        <div class="container-page relative">
            <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
                {{-- Left: Copy --}}
                <div class="animate-slide-up">
                    <div class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-white/70 px-4 py-1.5 text-sm font-medium text-brand-700 shadow-soft backdrop-blur">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-brand-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-brand-500"></span>
                        </span>
                        Booking real-time, konfirmasi instan
                    </div>

                    <h1 class="mt-6 font-display text-4xl font-bold leading-[1.1] tracking-tight text-court-950 text-balance sm:text-5xl lg:text-6xl">
                        Sewa lapangan olahraga
                        <span class="gradient-text"> tinggal klik.</span>
                    </h1>

                    <p class="mt-6 max-w-xl text-lg leading-relaxed text-court-600">
                        Cek jadwal, pilih jam, dan amankan slot lapangan favoritmu dalam hitungan detik.
                        Futsal, badminton, basket, voli, sampai tenis — semua dalam satu platform.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                        <a href="{{ Route::has('register') ? route('register') : '#' }}" class="btn-primary px-8 py-4 text-lg">
                            Mulai Booking Sekarang
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <a href="#how-it-works" class="btn-secondary px-8 py-4 text-lg">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Lihat Cara Kerja
                        </a>
                    </div>

                    {{-- Trust stats --}}
                    <div class="mt-10 grid grid-cols-3 gap-6 border-t border-court-100 pt-8">
                        <div>
                            <div class="font-display text-3xl font-bold text-court-950">
                                <span x-data="counter" x-intersect.once="startCounting()" data-target="1200">0</span>
                            </div>
                            <div class="mt-1 text-sm text-court-500">Booking / bulan</div>
                        </div>
                        <div>
                            <div class="font-display text-3xl font-bold text-court-950">
                                <span x-data="counter" x-intersect.once="startCounting()" data-target="50">0</span>+
                            </div>
                            <div class="mt-1 text-sm text-court-500">Lapangan aktif</div>
                        </div>
                        <div>
                            <div class="font-display text-3xl font-bold text-court-950">
                                <span x-data="counter" x-intersect.once="startCounting()" data-target="98">0</span>%
                            </div>
                            <div class="mt-1 text-sm text-court-500">Kepuasan pelanggan</div>
                        </div>
                    </div>
                </div>

                {{-- Right: Visual mockup --}}
                <div class="relative animate-scale-in" x-data="hero" @mousemove="handleMouseMove($event)">
                    <div class="relative mx-auto max-w-md">
                        {{-- Floating badge top --}}
                        <div class="absolute -left-6 top-8 z-20 hidden animate-fade-in rounded-2xl border border-court-100 bg-white p-4 shadow-strong sm:block"
                             :style="`transform: translate(${mouseX * 20}px, ${mouseY * 20}px)`">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-100 text-brand-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-court-900">Slot tersedia!</p>
                                    <p class="text-xs text-court-500">Futsal • 19:00 – 20:00</p>
                                </div>
                            </div>
                        </div>

                        {{-- Main card --}}
                        <div class="overflow-hidden rounded-3xl border border-court-100 bg-white shadow-strong">
                            {{-- hero image area --}}
                            <div class="relative h-44 bg-gradient-to-br from-brand-600 via-brand-500 to-energy-500">
                                <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(255,255,255,0.25),transparent_50%)]"></div>
                                <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between">
                                    <div>
                                        <span class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-xs font-medium text-white backdrop-blur">Futsal</span>
                                        <h3 class="mt-2 text-lg font-bold text-white">Lapangan A — Vinyl</h3>
                                    </div>
                                    <div class="rounded-xl bg-white/95 px-3 py-1.5 text-right shadow-soft">
                                        <p class="text-[10px] font-medium uppercase tracking-wide text-court-500">Mulai</p>
                                        <p class="text-sm font-bold text-court-900">Rp 120rb<span class="text-xs font-normal text-court-500">/jam</span></p>
                                    </div>
                                </div>
                            </div>

                            {{-- slot grid --}}
                            <div class="p-5">
                                <div class="mb-3 flex items-center justify-between">
                                    <p class="text-sm font-semibold text-court-900">Sen, 14 Okt 2026</p>
                                    <span class="text-xs text-court-500">5 slot tersisa</span>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach (['16:00', '17:00', '18:00', '19:00', '20:00', '21:00'] as $i => $jam)
                                        <div class="rounded-lg border py-2 text-center text-sm font-medium
                                            {{ in_array($i, [1, 3]) ? 'border-court-100 bg-court-50 text-court-300 line-through' : ($i === 4 ? 'border-brand-500 bg-brand-600 text-white shadow-soft' : 'border-court-200 bg-white text-court-700 hover:border-brand-400') }}">
                                            {{ $jam }}
                                        </div>
                                    @endforeach
                                </div>
                                <button class="btn-primary mt-4 w-full py-3">Konfirmasi Slot</button>
                            </div>
                        </div>

                        {{-- Floating badge bottom --}}
                        <div class="absolute -bottom-5 -right-4 z-20 hidden animate-fade-in rounded-2xl border border-court-100 bg-white p-4 shadow-strong sm:block"
                             :style="`transform: translate(${mouseX * -24}px, ${mouseY * -24}px)`">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-energy-100 text-energy-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-court-900">Bayar aman</p>
                                    <p class="text-xs text-court-500">QRIS • VA • Transfer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Logos / cabang strip --}}
            <div class="mt-16 border-t border-court-100 pt-8 lg:mt-24">
                <p class="text-center text-sm font-medium uppercase tracking-widest text-court-400">Tersedia di berbagai cabang</p>
                <div class="mt-6 flex flex-wrap items-center justify-center gap-x-10 gap-y-4 opacity-70">
                    @foreach (['Merr Surabaya', 'Gubeng', 'Darmo', 'Rungkut', 'Sidoarjo'] as $cabang)
                        <span class="font-display text-lg font-semibold text-court-400">{{ $cabang }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============ FEATURES ============ --}}
    <section id="features" class="section-py bg-white">
        <div class="container-page">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-widest text-brand-600">Kenapa Sport Center</span>
                <h2 class="mt-3 font-display text-3xl font-bold tracking-tight text-court-950 sm:text-4xl lg:text-5xl">
                    Semua yang kamu butuhkan untuk main
                </h2>
                <p class="mt-4 text-lg text-court-600">
                    Dari cek jadwal sampai pembayaran, semuanya dirancang biar kamu nggak buang waktu.
                </p>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @php
                    $features = [
                        ['title' => 'Cek Jadwal Real-Time', 'desc' => 'Lihat slot kosong pada tanggal & jam yang kamu mau, langsung dari kalender interaktif.', 'color' => 'brand', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['title' => 'Anti Double Booking', 'desc' => 'Sistem penguncian slot memastikan satu lapangan tidak dipesan dua orang di waktu yang sama.', 'color' => 'energy', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                        ['title' => 'Pembayaran Fleksibel', 'desc' => 'Bayar via QRIS, Virtual Account, transfer bank, atau tunai di lokasi. Sesuai kamu.', 'color' => 'brand', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                        ['title' => 'E-Ticket + QR Code', 'desc' => 'Bukti booking digital langsung di HP-mu. Tunjukkan QR saat check-in, tanpa cetak.', 'color' => 'energy', 'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z'],
                        ['title' => 'Harga Transparan', 'desc' => 'Tarif dasar, jam sibuk, dan akhir pekan terlihat jelas sebelum kamu bayar.', 'color' => 'brand', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                        ['title' => 'Riwayat & Refund', 'desc' => 'Pantau semua transaksi, ajukan pembatalan, dan kelola refund dalam satu dashboard.', 'color' => 'energy', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                    ];
                @endphp

                @foreach ($features as $i => $f)
                    @php
                        $iconBg = $f['color'] === 'brand' ? 'bg-brand-50 text-brand-600' : 'bg-energy-50 text-energy-600';
                    @endphp
                    <div class="group card-base p-7" x-data="{ show: false }" x-intersect.once="show = true"
                         :class="show ? 'animate-slide-up' : 'opacity-0'" style="animation-delay: {{ $i * 60 }}ms">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $iconBg }} transition-transform duration-300 group-hover:scale-110">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/>
                            </svg>
                        </div>
                        <h3 class="mt-5 text-lg font-bold text-court-950">{{ $f['title'] }}</h3>
                        <p class="mt-2 leading-relaxed text-court-600">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ SPORTS ============ --}}
    <section id="sports" class="section-py bg-court-950 text-white">
        <div class="container-page">
            <div class="flex flex-col items-start justify-between gap-6 lg:flex-row lg:items-end">
                <div class="max-w-2xl">
                    <span class="text-sm font-semibold uppercase tracking-widest text-brand-400">Pilihan Olahraga</span>
                    <h2 class="mt-3 font-display text-3xl font-bold tracking-tight text-balance sm:text-4xl lg:text-5xl">
                        Satu platform, semua jenis lapangan
                    </h2>
                    <p class="mt-4 text-lg text-court-400">
                        Pilih cabangmu, pilih olahraganya. Setiap lapangan punya fasilitas & tarif sendiri.
                    </p>
                </div>
                <a href="#" class="btn-accent shrink-0">Lihat Semua Lapangan</a>
            </div>

            <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-5">
                @php
                    $sports = [
                        ['name' => 'Futsal', 'count' => 18, 'emoji' => '⚽', 'grad' => 'from-brand-600/20 to-brand-400/5'],
                        ['name' => 'Badminton', 'count' => 22, 'emoji' => '🏸', 'grad' => 'from-energy-500/20 to-energy-400/5'],
                        ['name' => 'Basket', 'count' => 9, 'emoji' => '🏀', 'grad' => 'from-brand-600/20 to-brand-400/5'],
                        ['name' => 'Voli', 'count' => 7, 'emoji' => '🏐', 'grad' => 'from-energy-500/20 to-energy-400/5'],
                        ['name' => 'Tenis', 'count' => 6, 'emoji' => '🎾', 'grad' => 'from-brand-600/20 to-brand-400/5'],
                    ];
                @endphp

                @foreach ($sports as $i => $s)
                    <a href="#" class="group relative overflow-hidden rounded-2xl border border-court-800 bg-court-900/50 p-6 transition-all duration-300 hover:border-brand-500/50 hover:bg-court-900"
                       x-data="{ show: false }" x-intersect.once="show = true"
                       :class="show ? 'animate-slide-up' : 'opacity-0'" style="animation-delay: {{ $i * 80 }}ms">
                        <div class="absolute inset-0 bg-gradient-to-br {{ $s['grad'] }} opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                        <div class="relative">
                            <span class="text-3xl">{{ $s['emoji'] }}</span>
                            <h3 class="mt-4 text-lg font-bold text-white">{{ $s['name'] }}</h3>
                            <p class="mt-1 text-sm text-court-400">{{ $s['count'] }} lapangan</p>
                            <div class="mt-5 flex items-center gap-1 text-sm font-medium text-brand-400 opacity-0 transition-all duration-300 group-hover:opacity-100">
                                Booking
                                <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ HOW IT WORKS ============ --}}
    <section id="how-it-works" class="section-py bg-white">
        <div class="container-page">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-widest text-brand-600">Cara Kerja</span>
                <h2 class="mt-3 font-display text-3xl font-bold tracking-tight text-court-950 sm:text-4xl lg:text-5xl">
                    Booking cuma 4 langkah
                </h2>
            </div>

            <div class="relative mt-16">
                {{-- connecting line --}}
                <div class="absolute left-0 right-0 top-8 hidden h-px bg-gradient-to-r from-transparent via-court-200 to-transparent lg:block" aria-hidden="true"></div>

                <div class="grid gap-8 lg:grid-cols-4">
                    @php
                        $steps = [
                            ['n' => '01', 'title' => 'Cari Lapangan', 'desc' => 'Filter berdasarkan cabang, olahraga, dan tanggal yang kamu inginkan.'],
                            ['n' => '02', 'title' => 'Pilih Slot Jam', 'desc' => 'Centang jam yang kosong pada kalender. Bisa lebih dari satu jam.'],
                            ['n' => '03', 'title' => 'Bayar', 'desc' => 'Pilih metode bayar favoritmu. Slot terkunci otomatis saat checkout.'],
                            ['n' => '04', 'title' => 'Datang & Main', 'desc' => 'Tunjukkan QR booking di lokasi. Selesai — tinggal main!'],
                        ];
                    @endphp

                    @foreach ($steps as $i => $step)
                        <div class="relative text-center" x-data="{ show: false }" x-intersect.once="show = true"
                             :class="show ? 'animate-slide-up' : 'opacity-0'" style="animation-delay: {{ $i * 100 }}ms">
                            <div class="relative z-10 mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-600 to-brand-700 font-display text-xl font-bold text-white shadow-strong">
                                {{ $step['n'] }}
                            </div>
                            <h3 class="mt-6 text-lg font-bold text-court-950">{{ $step['title'] }}</h3>
                            <p class="mt-2 text-court-600">{{ $step['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============ TESTIMONIALS ============ --}}
    <section class="section-py bg-court-50">
        <div class="container-page">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-widest text-brand-600">Testimoni</span>
                <h2 class="mt-3 font-display text-3xl font-bold tracking-tight text-court-950 sm:text-4xl">
                    Dipercaya ribuan pemain
                </h2>
            </div>

            <div class="mt-14 grid gap-6 lg:grid-cols-3">
                @php
                    $reviews = [
                        ['name' => 'Rizky Pratama', 'role' => 'Kapten Tim Futsal', 'quote' => 'Dulu harus telepon satu-satu buat cek jadwal. Sekarang tinggal buka, pilih jam, bayar. Beres dalam 2 menit.', 'initials' => 'RP', 'color' => 'brand'],
                        ['name' => 'Sinta Dewi', 'role' => 'Member Badminton', 'quote' => 'Suka banget fitur anti double booking-nya. Nggak pernah lagi sampai lapangan ternyata sudah penuh.', 'initials' => 'SD', 'color' => 'energy'],
                        ['name' => 'Andi Wijaya', 'role' => 'Coach Basket', 'quote' => 'Buat pelatih, riwayat dan jadwal harian di dashboard operator sangat membantu saya atur latihan rutin.', 'initials' => 'AW', 'color' => 'brand'],
                    ];
                @endphp

                @foreach ($reviews as $i => $r)
                    @php $avatar = $r['color'] === 'brand' ? 'bg-brand-100 text-brand-700' : 'bg-energy-100 text-energy-700'; @endphp
                    <figure class="card-base flex flex-col p-7" x-data="{ show: false }" x-intersect.once="show = true"
                            :class="show ? 'animate-slide-up' : 'opacity-0'" style="animation-delay: {{ $i * 80 }}ms">
                        <div class="flex gap-1 text-energy-500" aria-label="Rating 5 dari 5">
                            @for ($s = 0; $s < 5; $s++)
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <blockquote class="mt-5 flex-1 leading-relaxed text-court-700">"{{ $r['quote'] }}"</blockquote>
                        <figcaption class="mt-6 flex items-center gap-3 border-t border-court-100 pt-5">
                            <div class="flex h-11 w-11 items-center justify-center rounded-full {{ $avatar }} font-semibold">
                                {{ $r['initials'] }}
                            </div>
                            <div>
                                <p class="font-semibold text-court-950">{{ $r['name'] }}</p>
                                <p class="text-sm text-court-500">{{ $r['role'] }}</p>
                            </div>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ CTA ============ --}}
    <section id="locations" class="section-py bg-white">
        <div class="container-page">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-700 via-brand-600 to-energy-500 px-6 py-16 text-center shadow-strong sm:px-16">
                <div class="pointer-events-none absolute inset-0" aria-hidden="true">
                    <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
                </div>
                <div class="relative mx-auto max-w-2xl">
                    <h2 class="font-display text-3xl font-bold tracking-tight text-white text-balance sm:text-4xl lg:text-5xl">
                        Lapangan favoritmu menunggu. Yuk mulai!
                    </h2>
                    <p class="mt-4 text-lg text-white/90">
                        Daftar gratis, tanpa biaya langganan. Bayar hanya saat kamu booking.
                    </p>
                    <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                        <a href="{{ Route::has('register') ? route('register') : '#' }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-8 py-4 text-lg font-semibold text-brand-700 shadow-soft transition-all duration-200 hover:bg-court-50 hover:shadow-medium">
                            Daftar Sekarang — Gratis
                        </a>
                        <a href="{{ Route::has('login') ? route('login') : '#' }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/30 px-8 py-4 text-lg font-semibold text-white transition-all duration-200 hover:bg-white/10">
                            Sudah punya akun? Masuk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.guest>