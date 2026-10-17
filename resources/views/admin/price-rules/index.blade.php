@extends('layouts.admin')
@section('title', 'Aturan Tarif Khusus')

@section('content')
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">Aturan Tarif Dinamis (Peak Hours)</h1>
        <a href="{{ route('admin.price-rules.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700">+ Tambah Aturan</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="p-4">Lapangan</th>
                    <th class="p-4">Hari</th>
                    <th class="p-4">Jam Rentang</th>
                    <th class="p-4">Harga Khusus / Jam</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; @endphp
                @forelse($priceRules as $rule)
                <tr>
                    <td class="p-4 font-semibold text-gray-800">{{ $rule->court->name }} ({{ $rule->court->branch->name }})</td>
                    <td class="p-4">{{ $days[$rule->day_of_week] }}</td>
                    <td class="p-4">{{ substr($rule->start_time, 0, 5) }} - {{ substr($rule->end_time, 0, 5) }}</td>
                    <td class="p-4 font-bold text-emerald-600">Rp {{ number_format($rule->price_per_hour, 0, ',', '.') }}</td>
                    <td class="p-4 text-right">
                        <form action="{{ route('admin.price-rules.destroy', $rule) }}" method="POST" class="inline" onsubmit="return confirm('Hapus aturan tarif ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 font-semibold hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-400">Belum ada aturan tarif khusus.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection