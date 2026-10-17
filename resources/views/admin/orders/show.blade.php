@extends('layouts.admin')
@section('title', 'Detail Pesanan')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">Detail Kode Booking: {{ $booking->booking_code }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold">Kembali</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 space-y-3">
            <h2 class="font-bold text-gray-800 border-b pb-2">Informasi Pemesan</h2>
            <p class="text-sm"><strong>Nama:</strong> {{ $booking->user->name }}</p>
            <p class="text-sm"><strong>Email:</strong> {{ $booking->user->email }}</p>
            <p class="text-sm"><strong>Status Booking:</strong> <span class="uppercase font-bold text-indigo-600">{{ $booking->status }}</span></p>
            <p class="text-sm"><strong>Status Pembayaran:</strong> <span class="uppercase font-bold text-emerald-600">{{ $booking->payment_status }}</span></p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 space-y-3">
            <h2 class="font-bold text-gray-800 border-b pb-2">Detail Lapangan & Jadwal</h2>
            <p class="text-sm"><strong>Lapangan:</strong> {{ $booking->court->name }}</p>
            <p class="text-sm"><strong>Cabang:</strong> {{ $booking->court->branch->name }}</p>
            <p class="text-sm"><strong>Tanggal Sewa:</strong> {{ $booking->booking_date }}</p>
            <p class="text-sm"><strong>Total Tagihan:</strong> <span class="text-lg font-bold text-emerald-600">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span></p>
        </div>
    </div>

    @if($booking->payment_status === 'pending_verification')
    <div class="bg-amber-50 border border-amber-200 p-5 rounded-xl flex justify-between items-center">
        <div>
            <p class="font-bold text-amber-900">Membutuhkan Verifikasi Pembayaran</p>
            <p class="text-sm text-amber-700">Pelanggan telah mengunggah bukti pembayaran manual.</p>
        </div>
        <form action="{{ route('admin.orders.confirm-payment', $booking) }}" method="POST">
            @csrf
            <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-emerald-700">Konfirmasi Lunas</button>
        </form>
    </div>
    @endif
</div>
@endsection