<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Court;
use App\Models\CourtPriceRule;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class PricingService
{
    /**
     * Menghitung biaya sewa per slot berdasarkan harga dasar dan aturan tarif.
     *
     * @param  array<int, array{start_time: string, end_time: string}>  $slots
     * @return array{items: array<int, array{start_time: string, end_time: string, hours: float, subtotal: float, breakdown: array<int, array{hour: string, price_per_hour: float, subtotal: float}>}>, total: float}
     */
    public function calculate(int|Court $court, string $date, array $slots): array
    {
        $court = $this->loadCourt($court);
        $rules = $this->loadRules($court);
        $day = $this->dayOfWeek($date);

        $items = [];
        $total = 0.0;

        foreach ($slots as $slot) {
            $item = $this->priceSlot($court, $rules, $day, $date, $slot);
            $items[] = $item;
            $total += $item['subtotal'];
        }

        return [
            'items' => $items,
            'total' => round($total, 2),
        ];
    }

    /**
     * Tarif satu jam pada tanggal tertentu, dipakai untuk menampilkan harga per slot.
     */
    public function hourlyPrice(int|Court $court, string $date, string $hour): float
    {
        $court = $this->loadCourt($court);
        $rules = $this->loadRules($court);

        $normalized = $this->normalizeTime($hour);
        if ($normalized === null) {
            throw new InvalidArgumentException('Jam tidak valid, gunakan format HH:MM.');
        }

        return $this->priceForHour($court, $rules, $this->dayOfWeek($date), $normalized);
    }

    /**
     * @param  array{start_time?: string|null, end_time?: string|null}  $slot
     * @return array{start_time: string, end_time: string, hours: float, subtotal: float, breakdown: array<int, array{hour: string, price_per_hour: float, subtotal: float}>}
     */
    private function priceSlot(Court $court, Collection $rules, int $day, string $date, array $slot): array
    {
        $start = $this->normalizeTime($slot['start_time'] ?? null);
        $end = $this->normalizeTime($slot['end_time'] ?? null);

        if ($start === null || $end === null || $start >= $end) {
            throw new InvalidArgumentException('Rentang waktu slot tidak valid.');
        }

        $cursor = Carbon::parse($date.' '.$start);
        $finish = Carbon::parse($date.' '.$end);

        $breakdown = [];
        $subtotal = 0.0;
        $hours = 0.0;

        while ($cursor->lt($finish)) {
            $hour = $cursor->format('H:00');
            $bucketEnd = $cursor->copy()->startOfHour()->addHour();

            if ($bucketEnd->gt($finish)) {
                $bucketEnd = $finish;
            }

            $minutes = (int) round($cursor->diffInMinutes($bucketEnd));
            $rate = $this->priceForHour($court, $rules, $day, $hour);
            $line = $rate * $minutes / 60;

            $breakdown[] = [
                'hour' => $hour,
                'price_per_hour' => $rate,
                'subtotal' => round($line, 2),
            ];

            $subtotal += $line;
            $hours += $minutes / 60;
            $cursor = $bucketEnd;
        }

        return [
            'start_time' => $start,
            'end_time' => $end,
            'hours' => $hours,
            'subtotal' => round($subtotal, 2),
            'breakdown' => $breakdown,
        ];
    }

    private function priceForHour(Court $court, Collection $rules, int $day, string $hour): float
    {
        $rule = $rules->first(function (CourtPriceRule $rule) use ($day, $hour): bool {
            if ((int) $rule->day_of_week !== $day) {
                return false;
            }

            $start = $this->normalizeTime((string) $rule->start_time);
            $end = $this->normalizeTime((string) $rule->end_time);

            return $start !== null && $end !== null && $start <= $hour && $hour < $end;
        });

        if ($rule === null) {
            return (float) $court->base_price_per_hour;
        }

        return (float) $rule->price_per_hour;
    }

    private function dayOfWeek(string $date): int
    {
        return (int) Carbon::parse($date)->format('w');
    }

    private function loadCourt(int|Court $court): Court
    {
        if ($court instanceof Court) {
            return $court;
        }

        return Court::query()->findOrFail($court);
    }

    private function loadRules(Court $court): Collection
    {
        return $court->relationLoaded('priceRules')
            ? $court->priceRules
            : $court->priceRules()->get();
    }

    private function normalizeTime(?string $time): ?string
    {
        if ($time === null || $time === '') {
            return null;
        }

        $time = trim($time);

        if (preg_match('/^(\d{1,2}):(\d{2})(?::\d{2})?$/', $time, $matches) !== 1) {
            return null;
        }

        $hour = (int) $matches[1];

        if ($hour > 23 || (int) $matches[2] > 59) {
            return null;
        }

        return sprintf('%02d:%s', $hour, $matches[2]);
    }
}
