<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('public.home'))->name('home');

// Rute untuk pengunjung yang belum login.
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// Rute yang wajib login.
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // /dashboard meneruskan ke dashboard sesuai role user.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboard khusus tiap role, dibatasi middleware "role".
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('role:superadmin,admin')
        ->name('admin.dashboard');

    Route::get('/operator/dashboard', [DashboardController::class, 'operator'])
        ->middleware('role:operator')
        ->name('operator.dashboard');

    Route::get('/customer/dashboard', [DashboardController::class, 'customer'])
        ->middleware('role:customer')
        ->name('customer.dashboard');
});
