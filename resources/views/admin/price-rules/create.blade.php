@extends('layouts.admin')
@section('title', 'Tambah Aturan Tarif')

@section('content')
<div class="max-w-2xl bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <h1 class="text-xl font-bold text-gray-800 mb-6">Tambah Aturan Tarif Khusus</h1>

    <form action="{{ route('admin.price-rules.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Pilih Lapangan</label>
            <select name="court_id" required class="mt-1 w-full p-2.5 border border-gray-300 rounded-lg text-sm">
                @foreach($courts as $court)
                <option value="{{ $court->id }}">{{ $court->name }} - {{ $court->branch->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Hari Berlaku</label>
            <select name="day_of_week" required class="mt-1 w-full p-2.5 border border-gray-300 rounded-lg text-sm">
                <option value="0">Minggu</option>
                <option value="1">Senin</option>
                <option value="2">Selasa</option>
                <option value="3">Rabu</option>
                <option value="4">Kamis</option>
                <option value="5">Jumat</option>
                <option value="6">Sabtu</option>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                <input type="time" name="start_time" required class="mt-1 w-full p-2.5 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                <input type="time" name="end_time" required class="mt-1 w-full p-2.5 border border-gray-300 rounded-lg text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Harga per Jam pada Waktu Tersebut (Rp)</label>
            <input type="number" name="price_per_hour" required class="mt-1 w-full p-2.5 border border-gray-300 rounded-lg text-sm">
        </div>
        <div class="pt-4 flex gap-3">
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-indigo-700">Simpan</button>
            <a href="{{ route('admin.price-rules.index') }}" class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-semibold">Batal</a>
        </div>
    </form>
</div>
@endsection