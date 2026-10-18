<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class ExpireBookings extends Command
{
    protected $signature = 'bookings:expire';

    protected $description = 'Membatalkan booking pending yang melewati batas waktu pembayaran 15 menit';

    public function handle(): int
    {
        $expired = Booking::query()
            ->where('status', 'pending')
            ->where('expired_at', '<=', now())
            ->update(['status' => 'expired']);

        $this->info($expired.' booking pending dikembalikan menjadi slot kosong.');

        return self::SUCCESS;
    }
}
