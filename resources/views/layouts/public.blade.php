@extends('layouts.app')

@section('body-class', 'd-flex flex-column min-vh-100 bg-light')

@section('layout-content')
    <!-- Public Header / Navbar -->
    @include('partials.public-navbar')

    <!-- Main Body Content -->
    <main class="flex-grow-1">
        <div class="container pt-3">
            @include('components.alert')
        </div>
        @yield('content')
    </main>

    <!-- Public Footer -->
    @include('partials.public-footer')
@endsection