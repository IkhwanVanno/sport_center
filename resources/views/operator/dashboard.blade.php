@extends('layouts.operator')

@section('title', 'Operator Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header Page & Action -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Kasir & Operasional Lapangan</h1>
        <a href="#" class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm px-4 py-2.5 rounded-lg transition shadow-sm">
            + Input Walk-In (Tunai)
        </a>
    </div>

    <!-- Quick Metric Cards (Persis seperti Admin) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Check-In Hari Ini</p>
            <p class="text-2xl font-bold text-indigo-600 mt-1">24 Slot</p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Walk-In (Kasir)</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">8 Pesanan</p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Belum Check-In</p>
            <p class="text-2xl font-bold text-amber-500 mt-1">5 Slot</p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Kapasitas Terisi</p>
            <p class="text-2xl font-bold text-slate-700 mt-1">75%</p>
        </div>
    </div>

    <!-- Search / Scanner Section -->
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 space-y-3">
        <h2 class="font-bold text-gray-800">Pemeriksaan Kode Booking</h2>
        <form class="flex gap-3">
            <input type="text" placeholder="Masukkan Kode Booking (contoh: SPC-20261014-X89B)" 
                   class="flex-1 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm px-5 py-2.5 rounded-lg transition">
                Cari & Verifikasi
            </button>
        </form>
    </div>

    <!-- Today's Schedule Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h2 class="font-bold text-gray-800 mb-4">Jadwal Penggunaan Hari Ini</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="p-3">Jam Slot</th>
                        <th class="p-3">Lapangan</th>
                        <th class="p-3">Kode & Nama Pelanggan</th>
                        <th class="p-3">Metode Bayar</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="p-3 font-semibold text-gray-800">19:00 - 20:00</td>
                        <td class="p-3">Lapangan Futsal A</td>
                        <td class="p-3">
                            <span class="font-semibold text-indigo-600">SPC-20261014-X89B</span>
                            <div class="text-xs text-gray-400">Budi Santoso</div>
                        </td>
                        <td class="p-3">QRIS (Online)</td>
                        <td class="p-3">
                            <span class="bg-amber-100 text-amber-800 text-xs px-2.5 py-1 rounded-full font-semibold">BELUM CHECK-IN</span>
                        </td>
                        <td class="p-3">
                            <button type="button" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                Process Check-In
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-3 font-semibold text-gray-800">20:00 - 21:00</td>
                        <td class="p-3">Lapangan Badminton 1</td>
                        <td class="p-3">
                            <span class="font-semibold text-indigo-600">SPC-20261014-Y92C</span>
                            <div class="text-xs text-gray-400">Andi Pratama</div>
                        </td>
                        <td class="p-3">Tunai (Kasir)</td>
                        <td class="p-3">
                            <span class="bg-green-100 text-green-800 text-xs px-2.5 py-1 rounded-full font-semibold">CHECKED-IN</span>
                        </td>
                        <td class="p-3">
                            <span class="text-xs text-gray-400 font-medium">Selesai</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection