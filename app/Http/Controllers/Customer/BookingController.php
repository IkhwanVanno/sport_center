<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Tampilkan riwayat booking milik pelanggan.
     */
    public function index(Request $request): View
    {
        $query = $request->user()
            ->bookings()
            ->with(['court.branch', 'court.sport', 'items', 'payments']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();

        return view('customer.bookings.index', compact('bookings'));
    }

    /**
     * Simpan booking baru dari pelanggan.
     */
    public function store(StoreBookingRequest $request, BookingService $bookingService): RedirectResponse
    {
        $validated = $request->validated();

        $booking = $bookingService->book(
            $request->user(),
            (int) $validated['court_id'],
            $validated['date'],
            $validated['slots'],
            $validated['notes'] ?? null,
        );

        return redirect()
            ->route('user.bookings.show', $booking->booking_code)
            ->with('success', 'Booking dibuat. Selesaikan pembayaran sebelum pukul '.$booking->expired_at->format('H:i').' WIB.');
    }

    /**
     * Tampilkan detail booking (kode, jadwal, tagihan) milik pelanggan sendiri.
     */
    public function show(Request $request, Booking $booking): View
    {
        abort_unless($booking->user_id === $request->user()?->id, 403);

        $booking->load(['court.branch', 'court.sport', 'items', 'payments']);

        return view('customer.bookings.show', compact('booking'));
    }
}
