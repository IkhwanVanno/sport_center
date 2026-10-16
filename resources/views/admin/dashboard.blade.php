@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">Ringkasan Statistik</h1>

    <!-- Quick Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Total Pendapatan</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">Rp 48.500.000</p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Booking Hari Ini</p>
            <p class="text-2xl font-bold text-indigo-600 mt-1">18 Transaksi</p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Menunggu Verifikasi</p>
            <p class="text-2xl font-bold text-amber-500 mt-1">3 Transaksi</p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Lapangan Aktif</p>
            <p class="text-2xl font-bold text-slate-700 mt-1">12 Unit</p>
        </div>
    </div>

    <!-- Recent Orders Table Placeholder -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h2 class="font-bold text-gray-800 mb-4">Transaksi Terbaru</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="p-3">Kode Booking</th>
                        <th class="p-3">Pelanggan</th>
                        <th class="p-3">Tanggal Main</th>
                        <th class="p-3">Total Biaya</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="p-3 font-semibold text-indigo-600">SPC-20261014-X89B</td>
                        <td class="p-3">Budi Santoso</td>
                        <td class="p-3">2026-10-18 (19:00 - 21:00)</td>
                        <td class="p-3">Rp 300.000</td>
                        <td class="p-3"><span class="bg-green-100 text-green-800 text-xs px-2.5 py-1 rounded-full font-semibold">PAID</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection