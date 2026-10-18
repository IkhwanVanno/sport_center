<?php

declare(strict_types=1);

namespace Tests\Concerns;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\Court;
use App\Models\CourtPriceRule;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Support\Str;

trait CreatesCourts
{
    protected function makeCourt(int $basePrice = 120000): Court
    {
        $code = Str::lower(Str::random(8));

        $branch = Branch::create([
            'name' => 'Sport Center Uji',
            'slug' => 'sport-center-uji-'.$code,
            'address' => 'Jl. Pengujian No. 1, Surabaya',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        $sport = Sport::create([
            'name' => 'Futsal Uji '.$code,
            'slug' => 'futsal-uji-'.$code,
            'icon' => 'heroicon-o-futbol',
        ]);

        return Court::create([
            'branch_id' => $branch->id,
            'sport_id' => $sport->id,
            'name' => 'Lapangan Futsal Uji A',
            'slug' => 'futsal-uji-a-'.$code,
            'base_price_per_hour' => $basePrice,
            'is_active' => true,
        ]);
    }

    protected function makeRule(Court $court, int $day, string $start, string $end, int $price): CourtPriceRule
    {
        return CourtPriceRule::create([
            'court_id' => $court->id,
            'day_of_week' => $day,
            'start_time' => $start,
            'end_time' => $end,
            'price_per_hour' => $price,
        ]);
    }

    protected function makeCustomer(array $attributes = []): User
    {
        return User::create(array_merge([
            'name' => 'Pelanggan Uji',
            'email' => 'pelanggan-'.Str::lower(Str::random(8)).'@example.com',
            'phone_number' => '081234567890',
            'password' => 'Password123!',
            'role' => 'customer',
        ], $attributes));
    }

    protected function makeBooking(Court $court, array $attributes = [], array $items = []): Booking
    {
        $booking = Booking::create(array_merge([
            'booking_code' => 'BK-'.Str::upper(Str::random(8)),
            'user_id' => $this->makeCustomer()->id,
            'court_id' => $court->id,
            'booking_date' => '2026-01-05',
            'total_amount' => 100000,
            'status' => 'confirmed',
            'payment_status' => 'unpaid',
            'expired_at' => now()->addMinutes(15),
        ], $attributes));

        foreach ($items as $item) {
            $booking->items()->create(array_merge(['price' => 100000], $item));
        }

        return $booking->load('items');
    }
}
