<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'court.branch']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $bookings = $query->latest()->paginate(15);
        return view('admin.orders.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'court.branch', 'items', 'payments']);
        return view('admin.orders.show', compact('booking'));
    }

    public function confirmPayment(Booking $booking)
    {
        $booking->update([
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);

        if ($booking->payments()->exists()) {
            $booking->payments()->latest()->first()->update([
                'status' => 'success',
                'paid_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi secara manual.');
    }
}
