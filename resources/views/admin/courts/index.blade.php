@extends('layouts.admin')
@section('title', 'Daftar Lapangan')

@section('content')
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">Daftar Lapangan</h1>
        <a href="{{ route('admin.courts.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700">+ Tambah Lapangan</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="p-4">Nama Lapangan</th>
                    <th class="p-4">Cabang</th>
                    <th class="p-4">Jenis Olahraga</th>
                    <th class="p-4">Harga Dasar / Jam</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($courts as $court)
                <tr>
                    <td class="p-4 font-semibold text-gray-800">{{ $court->name }}</td>
                    <td class="p-4">{{ $court->branch->name }}</td>
                    <td class="p-4">{{ $court->sport->name }}</td>
                    <td class="p-4 font-semibold text-emerald-600">Rp {{ number_format($court->base_price_per_hour, 0, ',', '.') }}</td>
                    <td class="p-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $court->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                            {{ $court->is_active ? 'Aktif' : 'Maintenance' }}
                        </span>
                    </td>
                    <td class="p-4 text-right space-x-2">
                        <a href="{{ route('admin.courts.edit', $court) }}" class="text-indigo-600 font-semibold hover:underline">Edit</a>
                        <form action="{{ route('admin.courts.destroy', $court) }}" method="POST" class="inline" onsubmit="return confirm('Hapus lapangan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 font-semibold hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-400">Belum ada data lapangan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection