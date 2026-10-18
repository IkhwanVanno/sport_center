<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Booking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ExpirePendingBookingJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly int $bookingId) {}

    public function handle(): void
    {
        Booking::query()
            ->whereKey($this->bookingId)
            ->where('status', 'pending')
            ->where('expired_at', '<=', now())
            ->update(['status' => 'expired']);
    }
}
