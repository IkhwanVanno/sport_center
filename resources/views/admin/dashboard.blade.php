@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">Ringkasan Statistik</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Total Pendapatan</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Booking Hari Ini</p>
            <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $todayBookings ?? 0 }} Transaksi</p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Menunggu Verifikasi</p>
            <p class="text-2xl font-bold text-amber-500 mt-1">{{ $pendingVerifications ?? 0 }} Transaksi</p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Lapangan Aktif</p>
            <p class="text-2xl font-bold text-slate-700 mt-1">{{ $activeCourts ?? 0 }} Unit</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h2 class="font-bold text-gray-800 mb-4">Transaksi Terbaru</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="p-3">Kode Booking</th>
                        <th class="p-3">Pelanggan</th>
                        <th class="p-3">Lapangan</th>
                        <th class="p-3">Tanggal Main</th>
                        <th class="p-3">Total Biaya</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentBookings ?? [] as $booking)
                    <tr>
                        <td class="p-3 font-semibold text-indigo-600">{{ $booking->booking_code }}</td>
                        <td class="p-3">{{ $booking->user->name }}</td>
                        <td class="p-3">{{ $booking->court->name }}</td>
                        <td class="p-3">{{ $booking->booking_date }}</td>
                        <td class="p-3">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                        <td class="p-3">
                            <span class="px-2.5 py-1 text-xs rounded-full font-semibold {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ strtoupper($booking->payment_status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-400">Belum ada transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection