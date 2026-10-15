<x-layouts.guest>
    <section class="pt-28 pb-16">
        <div class="container-page">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-brand-600">Dashboard Saya</p>
                    <h1 class="mt-1 font-display text-3xl font-bold text-court-950">
                        Halo, {{ auth()->user()->name }}
                    </h1>
                    <p class="mt-1 text-court-600">Kelola booking lapanganmu di sini.</p>
                </div>
                <a href="{{ route('home') }}#sports" class="btn-primary">Booking Lapangan</a>
            </div>

            <div class="mt-10 rounded-2xl border border-court-100 bg-white p-6 shadow-soft">
                <h2 class="font-display text-lg font-semibold text-court-900">Booking Saya</h2>
                <div class="mt-6 rounded-xl border border-dashed border-court-200 bg-court-50 p-10 text-center">
                    <p class="text-court-600">Belum ada booking. Yuk pesan lapangan pertamamu!</p>
                    <a href="{{ route('home') }}#sports" class="btn-primary mt-5 inline-flex">Lihat Lapangan</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.guest>
