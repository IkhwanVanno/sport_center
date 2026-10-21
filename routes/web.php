<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CourtController;
use App\Http\Controllers\Admin\FacilitiesController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\PriceRuleController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Customer\CatalogController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Operator\CheckInController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. PUBLIC ROUTES (Pengunjung)
// ==========================================
Route::get('/', [CatalogController::class, 'index'])->name('home');
Route::get('/courts', [CatalogController::class, 'list'])->name('courts.index');
Route::get('/courts/{court:slug}', [CatalogController::class, 'show'])->name('courts.show');
Route::get('/api/courts/{court}/availability', [CatalogController::class, 'checkAvailability']);

// ==========================================
// 2. AUTHENTICATION ROUTES (Guest)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// ==========================================
// 3. AUTHENTICATED SHARED ROUTES
// ==========================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Central dashboard router: mengarahkan user sesuai role masing-masing
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// ==========================================
// 4. CUSTOMER ROUTES (Authenticated Customer)
// ==========================================
Route::middleware(['auth', 'role:customer'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [CustomerDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking:booking_code}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking:booking_code}/pay', [PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::get('/bookings/{booking:booking_code}/upload-proof', [PaymentController::class, 'uploadProofForm'])->name('payments.upload-proof-form');
    Route::post('/bookings/{booking:booking_code}/upload-proof', [PaymentController::class, 'uploadProof'])->name('payments.upload-proof');
});

// Fallback alias jika ada komponen yang memanggil customer.dashboard
Route::get('/customer/dashboard', fn () => redirect()->route('user.dashboard'))
    ->middleware(['auth', 'role:customer'])
    ->name('customer.dashboard');

// ==========================================
// 5. OPERATOR ROUTES (Kasir / Staff Lapangan)
// ==========================================
Route::middleware(['auth', 'role:operator|superadmin'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/dashboard', fn () => redirect()->route('operator.checkin.index'))->name('dashboard');
    Route::get('/checkin', [CheckInController::class, 'index'])->name('checkin.index');
    Route::post('/checkin/verify', [CheckInController::class, 'verify'])->name('checkin.verify');
    Route::get('/walk-in', [CheckInController::class, 'walkIn'])->name('walkin.index');
    Route::post('/walk-in-booking', [CheckInController::class, 'storeWalkIn'])->name('walkin.store');
    Route::get('/schedule', [CheckInController::class, 'schedule'])->name('schedule.index');
});

// ==========================================
// 6. ADMIN & SUPERADMIN ROUTES
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    // 6.1 Shared Routes (Superadmin & Admin Cabang Terisolasi)
    Route::middleware(['auth', 'role:admin|superadmin', 'admin.branch'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('courts', CourtController::class)->except(['show']);
        Route::resource('price-rules', PriceRuleController::class)->except(['show', 'edit', 'update']);
        Route::resource('facilities', FacilitiesController::class)->except(['show']);
        Route::get('/orders', [OrderManagementController::class, 'index'])->name('orders.index');
        Route::get('/orders/{booking}', [OrderManagementController::class, 'show'])->name('orders.show');
        Route::post('/orders/{booking}/confirm-payment', [OrderManagementController::class, 'confirmPayment'])->name('orders.confirm-payment');
        Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    });

    // 6.2 Superadmin Only Routes (Akses Global Seluruh Cabang & Master Data Kunci)
    Route::middleware(['auth', 'role:superadmin'])->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::resource('branches', BranchController::class)->except(['show']);
        Route::resource('sports', SportController::class);
    });
});
