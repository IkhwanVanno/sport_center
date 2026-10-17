@extends('layouts.admin')
@section('title', 'Daftar Cabang')

@section('content')
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">Daftar Cabang</h1>
        <a href="{{ route('admin.branches.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700">+ Tambah Cabang</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="p-4">Nama Cabang</th>
                    <th class="p-4">Alamat</th>
                    <th class="p-4">Telepon</th>
                    <th class="p-4">Total Lapangan</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($branches as $branch)
                <tr>
                    <td class="p-4 font-semibold text-gray-800">{{ $branch->name }}</td>
                    <td class="p-4">{{ $branch->address }}</td>
                    <td class="p-4">{{ $branch->phone }}</td>
                    <td class="p-4">{{ $branch->courts_count }} Unit</td>
                    <td class="p-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $branch->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                            {{ $branch->is_active ? 'Aktif' : 'Non-aktif' }}
                        </span>
                    </td>
                    <td class="p-4 text-right space-x-2">
                        <a href="{{ route('admin.branches.edit', $branch) }}" class="text-indigo-600 font-semibold hover:underline">Edit</a>
                        <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" class="inline" onsubmit="return confirm('Hapus cabang ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 font-semibold hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-400">Data cabang belum tersedia.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection