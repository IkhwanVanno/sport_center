<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use App\Services\OrderManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class OrderManagementController extends Controller
{
    public function __construct(
        protected OrderManagementService $orderService
    ) {}

    /**
     * Menampilkan daftar pesanan sewa lapangan dengan scoping cabang.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Booking::class);

        $status = $request->query('status');
        $paymentStatus = $request->query('payment_status');
        $branchId = $request->filled('branch_id') ? (int) $request->query('branch_id') : null;
        $search = $request->query('search');

        $bookings = $this->orderService->listOrders(
            $request->user(),
            $status,
            $paymentStatus,
            $branchId,
            $search,
            15
        );

        $branches = $request->user()->isSuperAdmin()
            ? Branch::where('is_active', true)->orderBy('name')->get()
            : Branch::where('id', $request->user()->branch_id)->get();

        return view('admin.orders.index', compact('bookings', 'branches', 'status', 'paymentStatus', 'branchId', 'search'));
    }

    /**
     * Menampilkan detail pesanan.
     * Mencegah IDOR: Gate::authorize('view', $booking).
     */
    public function show(Booking $booking): View
    {
        Gate::authorize('view', $booking);

        $booking->load(['user', 'court.branch', 'items', 'payments']);

        return view('admin.orders.show', compact('booking'));
    }

    /**
     * Mengonfirmasi pembayaran pesanan secara manual via service layer (pessimistic locking).
     * Mencegah IDOR: Gate::authorize('confirmPayment', $booking).
     */
    public function confirmPayment(Booking $booking): RedirectResponse
    {
        Gate::authorize('confirmPayment', $booking);

        $this->orderService->confirmPayment($booking);

        return redirect()
            ->back()
            ->with('success', 'Pembayaran berhasil dikonfirmasi secara manual.');
    }
}
