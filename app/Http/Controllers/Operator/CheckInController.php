<?php

declare(strict_types=1);

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Court;
use App\Models\Payment;
use App\Models\User;
use App\Services\AvailabilityService;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CheckInController extends Controller
{
    /**
     * Tampilkan halaman check-in operator dan form pencarian kode booking.
     */
    public function index(Request $request): View
    {
        $booking = null;
        if ($request->filled('code')) {
            $booking = Booking::with(['user', 'court.branch', 'court.sport', 'items', 'payments'])
                ->where('booking_code', trim($request->code))
                ->first();
        }

        return view('operator.checkin', compact('booking'));
    }

    /**
     * Verifikasi kode booking untuk check-in di lokasi cabang.
     */
    public function verify(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'booking_code' => 'requi red|string',
        ]);

        $operator = $request->user();

        $booking = Booking::with(['user', 'court.branch'])
            ->where('booking_code', trim($validated['booking_code']))
            ->first();

        if (!$booking) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Kode booking tidak ditemukan di sistem.'], 404);
            }
            return back()->with('error', 'Kode booking tidak ditemukan.');
        }

        if ($operator?->branch_id && $booking->court->branch_id !== $operator->branch_id) {
            $message = 'Gagal! Kode booking ini milik cabang ' . $booking->court->branch->name . '.';
            if ($request->wantsJson()) {
                return response()->json(['message' => $message], 403);
            }
            return back()->with('error', $message);
        }

        if ($booking->status === 'in_use') {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Booking ini sudah di-check-in sebelumnya.'], 422);
            }
            return back()->with('warning', 'Booking ini sudah berstatus Check-In (Sedang Bermain).');
        }

        if ($booking->status !== 'confirmed') {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Booking belum berstatus lunas / dikonfirmasi.'], 422);
            }
            return back()->with('error', 'Booking belum dikonfirmasi atau belum lunas.');
        }

        $booking->update([
            'status' => 'in_use',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Verifikasi berhasil. Pelanggan dapat masuk ke lapangan.',
                'booking' => $booking,
            ]);
        }

        return redirect()->route('operator.checkin.index', ['code' => $booking->booking_code])
            ->with('success', 'Check-In berhasil! Pelanggan telah diverifikasi untuk masuk ke lapangan.');
    }

    /**
     * Tampilkan form input Walk-In booking tunai.
     */
    public function walkIn(Request $request): View
    {
        $operator = $request->user();
        $branchId = $operator?->branch_id;

        $courts = Court::where('is_active', true)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->with(['sport', 'branch'])
            ->get();

        return view('operator.walk-in', compact('courts'));
    }

    /**
     * Simpan pemesanan walk-in secara langsung di lokasi kasir dengan pembayaran tunai.
     */
    public function storeWalkIn(Request $request, BookingService $bookingService): JsonResponse|RedirectResponse
    {
        $operator = $request->user();

        $validated = $request->validate([
            'court_id' => [
                'required',
                Rule::exists('courts', 'id')->where(function ($query) use ($operator) {
                    $query->where('is_active', true);
                    if ($operator?->branch_id) {
                        $query->where('branch_id', $operator->branch_id);
                    }
                }),
            ],
            'date' => 'required|date',
            'slots' => 'required|array|min:1',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:255',
        ]);

        $operator = $request->user();

        // Cari atau buat user representasi pelanggan walk-in
        $phone = $validated['customer_phone'] ?? '08000000000';
        $emailSanitized = 'walkin.' . preg_replace('/[^0-9]/', '', $phone) . '.' . time() . '@sportcenter.local';

        $walkinCustomer = User::create([
            'name' => $validated['customer_name'] . ' (Walk-In)',
            'email' => $emailSanitized,
            'phone_number' => $phone,
            'password' => Hash::make(Str::random(16)),
            'role' => 'customer',
        ]);

        $booking = $bookingService->book(
            $walkinCustomer,
            (int) $validated['court_id'],
            $validated['date'],
            $validated['slots'],
            '[Walk-In Kasir: ' . ($operator?->name ?? 'Kasir') . '] ' . ($validated['notes'] ?? ''),
        );

        // Langsung tandai lunas karena pembayaran tunai di kasir
        $booking->update([
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'payment_method' => 'cash',
            'amount' => $booking->total_amount,
            'status' => 'success',
            'paid_at' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Booking walk-in berhasil dan lunas.',
                'booking_code' => $booking->booking_code,
            ]);
        }

        return redirect()->route('operator.checkin.index', ['code' => $booking->booking_code])
            ->with('success', 'Booking walk-in berhasil! Kode Booking: ' . $booking->booking_code . ' (Lunas Tunai)');
    }

    /**
     * Tampilkan jadwal lapangan hari ini untuk monitoring kasir / operator.
     */
    public function schedule(Request $request, AvailabilityService $availabilityService): View
    {
        $operator = $request->user();
        $branchId = $operator?->branch_id;
        $date = $request->input('date', today()->toDateString());

        $courts = Court::where('is_active', true)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->with(['sport', 'branch'])
            ->get();

        $scheduleData = [];
        foreach ($courts as $court) {
            $scheduleData[$court->id] = [
                'court' => $court,
                'slots' => $availabilityService->getSlots($court, $date),
            ];
        }

        return view('operator.schedule', compact('courts', 'scheduleData', 'date'));
    }
}
