@extends('layouts.customer')

@section('title', 'Dashboard Pelanggan')

@section('content')
<div class="space-y-6">
    <!-- Header Page & Action -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Pelanggan</h1>
        <a href="{{ route('home') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm px-4 py-2.5 rounded-lg transition shadow-sm">
            + Pesan Lapangan Baru
        </a>
    </div>

    <!-- Quick Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Total Pemesanan Saya</p>
            <p class="text-2xl font-bold text-indigo-600 mt-1">12 Transaksi</p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Jadwal Main Mendatang</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">1 Lapangan</p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Menunggu Pembayaran</p>
            <p class="text-2xl font-bold text-amber-500 mt-1">0 Transaksi</p>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h2 class="font-bold text-gray-800 mb-4">Riwayat Pemesanan Saya</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="p-3">Kode Booking</th>
                        <th class="p-3">Lapangan & Cabang</th>
                        <th class="p-3">Tanggal & Jam Main</th>
                        <th class="p-3">Total Biaya</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="p-3 font-semibold text-indigo-600">SPC-20261014-X89B</td>
                        <td class="p-3">
                            <div class="font-medium text-gray-800">Lapangan Futsal A (Sintetis)</div>
                            <div class="text-xs text-gray-400">Cabang MERR Surabaya</div>
                        </td>
                        <td class="p-3">18 OKT 2026 (19:00 - 21:00)</td>
                        <td class="p-3 font-semibold text-gray-800">Rp 300.000</td>
                        <td class="p-3">
                            <span class="bg-emerald-100 text-emerald-800 text-xs px-2.5 py-1 rounded-full font-semibold">LUNAS</span>
                        </td>
                        <td class="p-3">
                            <a href="#" class="text-xs font-semibold text-indigo-600 hover:underline">Detail E-Ticket</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection