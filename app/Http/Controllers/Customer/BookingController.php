<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
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
     * Tampilkan detail booking (kode, jadwal, tagihan).
     */
    public function show(Booking $booking): View
    {
        $booking->load(['court.branch', 'court.sport', 'items', 'payments']);

        return view('customer.bookings.show', compact('booking'));
    }
}
