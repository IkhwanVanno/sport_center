<x-layouts.guest>
    <section class="pt-28 pb-16">
        <div class="container-page">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-brand-600">Dashboard Admin</p>
                    <h1 class="mt-1 font-display text-3xl font-bold text-court-950">
                        Halo, {{ auth()->user()->name }}
                    </h1>
                    <p class="mt-1 text-court-600">Ringkasan operasional Sport Center.</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-brand-100 px-4 py-1.5 text-sm font-semibold capitalize text-brand-700">
                    {{ auth()->user()->role }}
                </span>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['label' => 'Booking Hari Ini', 'value' => '0'],
                    ['label' => 'Pendapatan Bulan Ini', 'value' => 'Rp 0'],
                    ['label' => 'Lapangan Aktif', 'value' => '0'],
                    ['label' => 'Total Pelanggan', 'value' => '0'],
                ] as $stat)
                    <div class="rounded-2xl border border-court-100 bg-white p-6 shadow-soft">
                        <p class="text-sm text-court-500">{{ $stat['label'] }}</p>
                        <p class="mt-2 font-display text-3xl font-bold text-court-950">{{ $stat['value'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-10 rounded-2xl border border-dashed border-court-200 bg-court-50 p-10 text-center">
                <p class="text-court-600">Modul manajemen lapangan, jadwal, dan laporan akan ditambahkan pada tahap berikutnya.</p>
            </div>
        </div>
    </section>
</x-layouts.guest>
