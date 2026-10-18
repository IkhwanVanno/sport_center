<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Court;
use Carbon\Carbon;

class AvailabilityService
{
    public const OPEN_TIME = '08:00';

    public const CLOSE_TIME = '23:00';

    private const BLOCKING_STATUSES = ['pending', 'confirmed', 'in_use', 'completed'];

    public function __construct(private readonly PricingService $pricing) {}

    /**
     * Daftar slot per jam untuk lapangan pada tanggal tertentu.
     *
     * @return array<int, array{start_time: string, end_time: string, status: string, price_per_hour: float}>
     */
    public function getSlots(int|Court $court, string $date): array
    {
        $court = $this->loadCourt($court);
        $court->loadMissing('priceRules');

        $occupied = $this->occupiedHours($court, $date);
        $slots = [];

        $cursor = Carbon::parse(self::OPEN_TIME);
        $close = Carbon::parse(self::CLOSE_TIME);

        while ($cursor->lt($close)) {
            $hour = $cursor->format('H:00');

            $slots[] = [
                'start_time' => $hour,
                'end_time' => $cursor->copy()->addHour()->format('H:00'),
                'status' => $occupied[$hour] ?? 'available',
                'price_per_hour' => $this->pricing->hourlyPrice($court, $date, $hour),
            ];

            $cursor->addHour();
        }

        return $slots;
    }

    /**
     * Jam yang sudah terpakai, dipetakan dari rentang waktu tiap item booking.
     *
     * @return array<string, string>
     */
    private function occupiedHours(Court $court, string $date): array
    {
        $bookings = Booking::query()
            ->with('items')
            ->where('court_id', $court->id)
            ->where('booking_date', $date)
            ->whereIn('status', self::BLOCKING_STATUSES)
            ->where(function ($query) {
                $query->where('status', '!=', 'pending')
                    ->orWhere('expired_at', '>', now());
            })
            ->get();

        $occupied = [];

        foreach ($bookings as $booking) {
            $status = $booking->status === 'pending' ? 'pending' : 'booked';

            foreach ($booking->items as $item) {
                foreach ($this->hoursBetween((string) $item->start_time, (string) $item->end_time) as $hour) {
                    if (($occupied[$hour] ?? null) !== 'booked') {
                        $occupied[$hour] = $status;
                    }
                }
            }
        }

        return $occupied;
    }

    /**
     * @return array<int, string>
     */
    private function hoursBetween(string $start, string $end): array
    {
        $startMinute = $this->toMinutes($start);
        $endMinute = $this->toMinutes($end);
        $hours = [];

        for ($minute = intdiv($startMinute, 60) * 60; $minute < $endMinute; $minute += 60) {
            $hours[] = sprintf('%02d:00', intdiv($minute, 60));
        }

        return $hours;
    }

    private function toMinutes(string $time): int
    {
        [$hour, $minute] = array_map('intval', explode(':', substr($time, 0, 5)));

        return $hour * 60 + $minute;
    }

    private function loadCourt(int|Court $court): Court
    {
        if ($court instanceof Court) {
            return $court;
        }

        return Court::query()->findOrFail($court);
    }
}
