<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\ExpirePendingBookingJob;
use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public const HOLD_MINUTES = 15;

    public function __construct(
        private readonly PricingService $pricing,
        private readonly AvailabilityService $availability,
    ) {}

    /**
     * Membuat booking baru untuk pelanggan dengan penguncian baris lapangan.
     *
     * @param  array<int, string>  $slots  daftar jam mulai slot, contoh ['08:00', '09:00']
     */
    public function book(User $user, Court|int $court, string $date, array $slots, ?string $notes = null): Booking
    {
        $date = $this->normalizeDate($date);
        $hours = $this->normalizeHours($slots);

        return DB::transaction(function () use ($user, $court, $date, $hours, $notes) {
            $court = $this->lockCourt($court);

            $ranges = collect($hours)
                ->map(fn (string $hour): array => [
                    'start_time' => $hour,
                    'end_time' => Carbon::parse($hour)->addHour()->format('H:i'),
                ])
                ->all();

            $this->ensureAvailable($court, $date, $hours);

            $pricing = $this->pricing->calculate($court, $date, $ranges);

            $booking = Booking::create([
                'booking_code' => $this->generateCode($date),
                'user_id' => $user->id,
                'court_id' => $court->id,
                'booking_date' => $date,
                'total_amount' => $pricing['total'],
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'expired_at' => now()->addMinutes(self::HOLD_MINUTES),
                'notes' => $notes,
            ]);

            foreach ($ranges as $index => $range) {
                $booking->items()->create([
                    'start_time' => $range['start_time'],
                    'end_time' => $range['end_time'],
                    'price' => $pricing['items'][$index]['subtotal'],
                ]);
            }

            ExpirePendingBookingJob::dispatch($booking->id)
                ->delay(now()->addMinutes(self::HOLD_MINUTES))
                ->afterCommit();

            return $booking->load('items');
        });
    }

    private function ensureAvailable(Court $court, string $date, array $hours): void
    {
        $terpakai = collect($this->availability->getSlots($court, $date))
            ->reject(fn (array $slot): bool => $slot['status'] === 'available')
            ->pluck('start_time')
            ->all();

        $bentrok = array_values(array_intersect($hours, $terpakai));

        if ($bentrok === []) {
            return;
        }

        throw ValidationException::withMessages([
            'slots' => 'Slot '.implode(', ', $bentrok).' tanggal '.$date.' sudah dipesan. Silakan pilih jadwal lain.',
        ]);
    }

    private function lockCourt(Court|int $court): Court
    {
        $id = $court instanceof Court ? $court->id : $court;
        $locked = Court::query()->lockForUpdate()->find($id);

        if ($locked === null || ! $locked->is_active) {
            throw ValidationException::withMessages([
                'court_id' => 'Lapangan tidak ditemukan atau sedang tidak aktif.',
            ]);
        }

        return $locked;
    }

    private function normalizeDate(string $date): string
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) !== 1 || date('Y-m-d', strtotime($date)) !== $date) {
            throw ValidationException::withMessages([
                'date' => 'Tanggal sewa tidak valid.',
            ]);
        }

        return $date;
    }

    /**
     * @param  array<int, string>  $slots
     * @return array<int, string>
     */
    private function normalizeHours(array $slots): array
    {
        $hours = [];

        foreach ($slots as $slot) {
            if (! is_string($slot) || preg_match('/^(\d{1,2}):(\d{2})$/', $slot, $matches) !== 1) {
                throw ValidationException::withMessages([
                    'slots' => 'Format jam slot tidak valid, gunakan HH:MM.',
                ]);
            }

            $hour = (int) $matches[1];
            $minute = (int) $matches[2];

            if ($hour > 23 || $minute > 59 || $minute !== 0) {
                throw ValidationException::withMessages([
                    'slots' => 'Slot harus berdurasi satu jam penuh, contoh 08:00.',
                ]);
            }

            $normalized = sprintf('%02d:00', $hour);

            if ($normalized < AvailabilityService::OPEN_TIME || $normalized >= AvailabilityService::CLOSE_TIME) {
                throw ValidationException::withMessages([
                    'slots' => 'Slot di luar jam operasional '.AvailabilityService::OPEN_TIME.'-'.AvailabilityService::CLOSE_TIME.'.',
                ]);
            }

            $hours[$normalized] = $normalized;
        }

        if ($hours === []) {
            throw ValidationException::withMessages([
                'slots' => 'Pilih minimal satu slot jadwal.',
            ]);
        }

        ksort($hours);

        return array_values($hours);
    }

    private function generateCode(string $date): string
    {
        do {
            $code = 'BK-'.substr($date, 2, 2).substr($date, 5, 2).substr($date, 8, 2).'-'.Str::upper(Str::random(6));
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }
}
