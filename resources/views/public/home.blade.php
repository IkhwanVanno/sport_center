@extends('layouts.public')

@section('title', 'Sewa Lapangan Futsal, Badminton & Basket Online')

@section('content')
<div class="space-y-16 py-8">

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-900 rounded-3xl p-8 sm:p-12 lg:p-16 text-white relative overflow-hidden shadow-xl">
            <div class="relative z-10 max-w-2xl space-y-6">
                <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs px-3 py-1.5 rounded-full font-bold uppercase tracking-wider">
                    Sistem Reservasi Online Real-Time
                </span>
                <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                    Main Olahraga Tanpa Ribet Antre Slot
                </h1>
                <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                    Cek jadwal ketersediaan lapangan futsal, badminton, basket, dan tenis secara instan. Pesan per jam dan bayar otomatis dari mana saja.
                </p>
                <div class="pt-2 flex flex-wrap gap-4 items-center">
                    <a href="#katalog" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-6 py-3.5 rounded-xl text-sm transition shadow-lg shadow-emerald-600/30">
                        Cari Lapangan Sekarang
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="bg-white/10 hover:bg-white/20 text-white font-semibold px-6 py-3.5 rounded-xl text-sm border border-white/20 transition">
                            Daftar Akun Gratis
                        </a>
                    @endguest
                </div>
            </div>
            
            <!-- Hiasan Background / Visual Pattern -->
            <div class="absolute -right-10 -bottom-10 opacity-10 font-black text-[18rem] select-none pointer-events-none">
                ⚽
            </div>
        </div>
    </section>

    <!-- Quick Stats Bar -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 text-center">
                <p class="text-2xl sm:text-3xl font-black text-indigo-600">10+</p>
                <p class="text-xs text-gray-500 font-medium mt-1">Cabang Fasilitas</p>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 text-center">
                <p class="text-2xl sm:text-3xl font-black text-emerald-600">40+</p>
                <p class="text-xs text-gray-500 font-medium mt-1">Lapangan Fisik</p>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 text-center">
                <p class="text-2xl sm:text-3xl font-black text-amber-500">100%</p>
                <p class="text-xs text-gray-500 font-medium mt-1">Bebas Double Booking</p>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 text-center">
                <p class="text-2xl sm:text-3xl font-black text-slate-700">15rb+</p>
                <p class="text-xs text-gray-500 font-medium mt-1">Pengguna Terdaftar</p>
            </div>
        </div>
    </section>

    <!-- Katalog Lapangan Utama -->
    <section id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Lapangan Populer</h2>
                <p class="text-xs text-gray-500 mt-1">Pilih lapangan favorit dan periksa slot waktu yang tersedia</p>
            </div>
            <a href="#" class="text-xs font-semibold text-indigo-600 hover:underline flex items-center gap-1">
                Lihat Selengkapnya &rarr;
            </a>
        </div>

        <!-- Grid Lapangan Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Sample Card 1 -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden space-y-4 hover:shadow-md transition">
                <div class="h-44 bg-slate-200 relative">
                    <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=600&q=80" alt="Lapangan Futsal A" class="w-full h-full object-cover">
                    <span class="absolute top-3 right-3 bg-emerald-600 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase">
                        Futsal
                    </span>
                </div>
                <div class="p-5 space-y-3">
                    <div>
                        <h3 class="font-bold text-gray-800 text-base">Lapangan Futsal A (Rumput Sintetis)</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Cabang MERR Surabaya</p>
                    </div>
                    <div class="pt-3 border-t border-gray-100 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] text-gray-400 uppercase font-semibold block">Harga Sewa</span>
                            <span class="text-base font-bold text-emerald-600">Rp 120.000</span>
                            <span class="text-xs text-gray-400">/jam</span>
                        </div>
                        <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
                            Pesan Slot
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sample Card 2 -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden space-y-4 hover:shadow-md transition">
                <div class="h-44 bg-slate-200 relative">
                    <img src="https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=600&q=80" alt="Lapangan Badminton 1" class="w-full h-full object-cover">
                    <span class="absolute top-3 right-3 bg-indigo-600 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase">
                        Badminton
                    </span>
                </div>
                <div class="p-5 space-y-3">
                    <div>
                        <h3 class="font-bold text-gray-800 text-base">Lapangan Badminton 1 (Vinyil BWF)</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Cabang MERR Surabaya</p>
                    </div>
                    <div class="pt-3 border-t border-gray-100 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] text-gray-400 uppercase font-semibold block">Harga Sewa</span>
                            <span class="text-base font-bold text-emerald-600">Rp 50.000</span>
                            <span class="text-xs text-gray-400">/jam</span>
                        </div>
                        <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
                            Pesan Slot
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sample Card 3 -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden space-y-4 hover:shadow-md transition">
                <div class="h-44 bg-slate-200 relative">
                    <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=600&q=80" alt="Lapangan Basket" class="w-full h-full object-cover">
                    <span class="absolute top-3 right-3 bg-amber-500 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase">
                        Basket
                    </span>
                </div>
                <div class="p-5 space-y-3">
                    <div>
                        <h3 class="font-bold text-gray-800 text-base">Lapangan Basket Outdoor A</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Cabang Barat Surabaya</p>
                    </div>
                    <div class="pt-3 border-t border-gray-100 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] text-gray-400 uppercase font-semibold block">Harga Sewa</span>
                            <span class="text-base font-bold text-emerald-600">Rp 100.000</span>
                            <span class="text-xs text-gray-400">/jam</span>
                        </div>
                        <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
                            Pesan Slot
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Banner Call-to-Action Registrasi (Khusus Guest) -->
    @guest
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-8 sm:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="space-y-2 text-center md:text-left">
                <h3 class="text-xl font-bold text-gray-800">Belum Punya Akun Sport Center?</h3>
                <p class="text-xs text-gray-500">Daftarkan diri Anda sekarang untuk kemudahan pemesanan slot dan riwayat transaksi.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs px-5 py-3 rounded-lg transition shadow-sm">
                    Buat Akun Baru
                </a>
                <a href="{{ route('login') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-semibold text-xs px-5 py-3 rounded-lg border border-gray-200 transition">
                    Masuk
                </a>
            </div>
        </div>
    </section>
    @endguest

</div>
@endsection