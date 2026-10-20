<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Tampilkan halaman instruksi pembayaran / checkout dengan timer 15 menit.
     */
    public function checkout(Request $request, Booking $booking): View
    {
        abort_unless($booking->user_id === $request->user()?->id, 403);

        $booking->load(['court.branch', 'court.sport', 'items', 'payments']);

        return view('customer.bookings.checkout', compact('booking'));
    }

    /**
     * Tampilkan form upload bukti transfer manual.
     */
    public function uploadProofForm(Request $request, Booking $booking): View
    {
        abort_unless($booking->user_id === $request->user()?->id, 403);

        $booking->load(['court.branch', 'court.sport', 'items', 'payments']);

        return view('customer.payments.upload-proof', compact('booking'));
    }

    /**
     * Proses upload bukti transfer manual dari pelanggan.
     */
    public function uploadProof(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()?->id, 403);

        if ($booking->status === 'cancelled' || $booking->status === 'expired') {
            return redirect()->route('user.bookings.show', $booking->booking_code)
                ->with('error', 'Booking ini sudah tidak berlaku (telah kedaluwarsa atau dibatalkan).');
        }

        $request->validate([
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'bank_sender' => 'nullable|string|max:100',
        ], [
            'proof_of_payment.required' => 'Pilih file bukti transfer terlebih dahulu.',
            'proof_of_payment.image' => 'File harus berupa foto / gambar.',
            'proof_of_payment.mimes' => 'Format file yang didukung: JPG, PNG, WEBP.',
            'proof_of_payment.max' => 'Ukuran file bukti transfer maksimal 3MB.',
        ]);

        $path = $request->file('proof_of_payment')->store('payment-proofs', 'public');

        Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'payment_method' => 'transfer',
                'amount' => $booking->total_amount,
                'proof_of_payment' => $path,
                'status' => 'pending',
            ]
        );

        $booking->update([
            'payment_status' => 'pending_verification',
        ]);

        return redirect()->route('user.bookings.show', $booking->booking_code)
            ->with('success', 'Bukti transfer berhasil diunggah! Mohon menunggu verifikasi dari admin cabang.');
    }

    /**
     * Webhook notification dari Payment Gateway (contoh Midtrans / QRIS).
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $booking = Booking::where('booking_code', $orderId)->first();
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            $booking->update([
                'status' => 'confirmed',
                'payment_status' => 'paid',
            ]);

            Payment::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'payment_method' => 'qris',
                    'amount' => $booking->total_amount,
                    'status' => 'success',
                    'paid_at' => now(),
                ]
            );
        }

        return response()->json(['message' => 'Webhook processed successfully']);
    }
}
