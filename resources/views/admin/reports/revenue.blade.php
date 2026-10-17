@extends('layouts.admin')
@section('title', 'Laporan Pendapatan')

@section('content')
<div class="space-y-6">
    <h1 class="text-xl font-bold text-gray-800">Laporan Pendapatan Transaksi</h1>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <form method="GET" action="{{ route('admin.reports.revenue') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="mt-1 w-full p-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="mt-1 w-full p-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Cabang</label>
                <select name="branch_id" class="mt-1 w-full p-2 border border-gray-300 rounded-lg text-sm">
                    <option value="">Semua Cabang</option>
                    @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ $branchId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-indigo-600 text-white p-2.5 rounded-lg text-sm font-semibold hover:bg-indigo-700">Filter Laporan</button>
        </form>
    </div>

    <div class="bg-emerald-50 border border-emerald-200 p-5 rounded-xl">
        <p class="text-sm font-medium text-emerald-800">Total Omzet Terverifikasi (Lunas)</p>
        <p class="text-3xl font-extrabold text-emerald-600 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="p-4">Kode Booking</th>
                    <th class="p-4">Tanggal Main</th>
                    <th class="p-4">Pelanggan</th>
                    <th class="p-4">Lapangan / Cabang</th>
                    <th class="p-4 text-right">Nominal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reports as $report)
                <tr>
                    <td class="p-4 font-semibold text-indigo-600">{{ $report->booking_code }}</td>
                    <td class="p-4">{{ $report->booking_date }}</td>
                    <td class="p-4">{{ $report->user->name }}</td>
                    <td class="p-4">{{ $report->court->name }} ({{ $report->court->branch->name }})</td>
                    <td class="p-4 text-right font-bold text-gray-800">Rp {{ number_format($report->total_amount, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-400">Tidak ada data transaksi pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection