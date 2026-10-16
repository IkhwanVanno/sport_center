@extends('layouts.operator')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- QR Scan / Search Box -->
    <div class="bg-slate-800 p-6 rounded-xl border border-slate-700 shadow-lg">
        <h2 class="text-lg font-bold text-indigo-300 mb-4">Pemeriksaan / Check-In Pelanggan</h2>
        <form class="flex gap-3">
            <input type="text" placeholder="Masukkan Kode Booking (contoh: SPC-20261014-X89B)" class="flex-1 bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-indigo-500">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 font-bold px-6 py-3 rounded-lg transition">Cari / Check-In</button>
        </form>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 gap-4">
        <a href="#" class="bg-emerald-600 hover:bg-emerald-500 p-5 rounded-xl text-center font-bold text-lg transition">
            + Input Walk-In Booking (Bayar Tunai)
        </a>
        <a href="#" class="bg-slate-800 hover:bg-slate-700 p-5 rounded-xl text-center font-bold text-lg border border-slate-700 transition">
            Jadwal Lapangan Hari Ini
        </a>
    </div>
</div>
@endsection