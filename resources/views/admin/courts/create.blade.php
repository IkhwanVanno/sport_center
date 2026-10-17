@extends('layouts.admin')
@section('title', 'Tambah Lapangan')

@section('content')
<div class="max-w-2xl bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <h1 class="text-xl font-bold text-gray-800 mb-6">Tambah Lapangan Baru</h1>

    <form action="{{ route('admin.courts.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Cabang</label>
            <select name="branch_id" required class="mt-1 w-full p-2.5 border border-gray-300 rounded-lg text-sm">
                @foreach($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Jenis Olahraga</label>
            <select name="sport_id" required class="mt-1 w-full p-2.5 border border-gray-300 rounded-lg text-sm">
                @foreach($sports as $sport)
                <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Lapangan</label>
            <input type="text" name="name" required class="mt-1 w-full p-2.5 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Harga Dasar per Jam (Rp)</label>
            <input type="number" name="base_price_per_hour" required class="mt-1 w-full p-2.5 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Deskripsi / Fasilitas Tambahan</label>
            <textarea name="description" class="mt-1 w-full p-2.5 border border-gray-300 rounded-lg text-sm" rows="3"></textarea>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" id="is_active" checked class="rounded border-gray-300 text-indigo-600">
            <label for="is_active" class="text-sm font-medium text-gray-700">Status Aktif & Dapat Dipesan</label>
        </div>
        <div class="pt-4 flex gap-3">
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-indigo-700">Simpan</button>
            <a href="{{ route('admin.courts.index') }}" class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-semibold">Batal</a>
        </div>
    </form>
</div>
@endsection