<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Pengaman kedua: slot booking pending yang lewat 15 menit dikembalikan
// menjadi kosong walau job tertinggal di antrean.
Schedule::command('bookings:expire')->everyMinute();
