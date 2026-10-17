@extends('layouts.admin')
@section('title', 'Kelola Pesanan')

@section('content')
<div class="space-y-4">
    <h1 class="text-xl font-bold text-gray-800">Daftar Transaksi Pesanan</h1>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="p-4">Kode Booking</th>
                    <th class="p-4">Pelanggan</th>
                    <th class="p-4">Lapangan</th>
                    <th class="p-4">Tgl Main</th>
                    <th class="p-4">Total Biaya</th>
                    <th class="p-4">Status Bayar</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bookings as $booking)
                <tr>
                    <td class="p-4 font-semibold text-indigo-600">{{ $booking->booking_code }}</td>
                    <td class="p-4">{{ $booking->user->name }}</td>
                    <td class="p-4">{{ $booking->court->name }} ({{ $booking->court->branch->name }})</td>
                    <td class="p-4">{{ $booking->booking_date }}</td>
                    <td class="p-4 font-bold text-gray-800">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                    <td class="p-4">
                        <span class="px-2.5 py-1 text-xs rounded-full font-semibold 
                                {{ $booking->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ($booking->payment_status === 'pending_verification' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ strtoupper(str_replace('_', ' ', $booking->payment_status)) }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <a href="{{ route('admin.orders.show', $booking) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded text-xs font-semibold">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-4 text-center text-gray-400">Belum ada pesanan terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection