@extends('layouts.operator')

@section('title', 'Operator Dashboard')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 text-center py-5">
        <div class="card card-custom p-5 border-0 shadow-sm">
            <div class="mb-3">
                <span class="fs-1">⚽</span>
            </div>
            <h3 class="fw-bold text-dark mb-2">Portal Operasional & Kasir</h3>
            <p class="text-muted small mb-4">Silakan pilih menu operasional untuk memulai verifikasi kehadiran pelanggan atau melayani pesanan langsung di kasir.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('operator.checkin.index') }}" class="btn btn-indigo px-4 py-2.5 rounded-3 fw-bold">
                    <i class="bi bi-qr-code-scan me-1"></i>Pemeriksaan Check-In
                </a>
                <a href="{{ route('operator.walkin.index') }}" class="btn btn-emerald px-4 py-2.5 rounded-3 fw-bold">
                    <i class="bi bi-plus-circle me-1"></i>Input Walk-In Booking
                </a>
                <a href="{{ route('operator.schedule.index') }}" class="btn btn-outline-primary px-4 py-2.5 rounded-3 fw-bold">
                    <i class="bi bi-calendar3 me-1"></i>Jadwal Hari Ini
                </a>
            </div>
        </div>
    </div>
</div>
@endsection