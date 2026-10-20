@extends('layouts.app')

@section('body-class', 'd-flex flex-column min-vh-100 bg-light')

@section('layout-content')
    <!-- Operator Header -->
    @include('partials.operator-header')

    <!-- Main Operasional Area -->
    <main class="flex-grow-1 py-4">
        <div class="container-fluid px-3 px-md-4">
            @include('components.alert')
            @yield('content')
        </div>
    </main>

    <!-- Simple Operasional Footer -->
    <footer class="bg-white border-top py-2 px-4 text-muted small d-flex justify-content-between align-items-center no-print">
        <div>
            Sistem Kasir & Operasional — <strong>Sport Center</strong>
        </div>
        <div>
            Status: <span class="badge bg-success-subtle text-success border border-success-subtle">Online / Siap Operasi</span>
        </div>
    </footer>
@endsection