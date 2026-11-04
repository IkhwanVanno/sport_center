<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('customer') ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'court_id' => ['required', 'integer', Rule::exists('courts', 'id')->where('is_active', true)],
            'date'     => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'slots'    => ['required', 'array', 'min:1', 'max:15'],
            'slots.*'  => ['required', 'date_format:H:i'],
            'notes'    => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Validasi tambahan setelah aturan dasar lolos:
     * - Jika tanggal = hari ini, semua slot jam harus belum lewat.
     * - Slot jam harus dalam urutan yang logis (tidak duplikat sudah ditangani BookingService).
     *
     * @param  \Illuminate\Validation\Validator  $validator
     */
    public function withValidator(\Illuminate\Validation\Validator $validator): void
    {
        $validator->after(function (\Illuminate\Validation\Validator $v) {
            $date  = $this->input('date');
            $slots = $this->input('slots');

            if (! is_string($date) || ! is_array($slots)) {
                return;
            }

            // Pastikan tanggal benar-benar valid sebagai tanggal kalender
            if (! Carbon::hasFormat($date, 'Y-m-d')) {
                $v->errors()->add('date', 'Tanggal sewa tidak valid.');
                return;
            }

            $today = Carbon::today();
            $bookingDate = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();

            if ($bookingDate->lt($today)) {
                $v->errors()->add('date', 'Tanggal sewa tidak boleh sebelum hari ini.');
                return;
            }

            // Jika booking hari ini, cek slot jam sudah lewat atau belum
            if ($bookingDate->equalTo($today)) {
                $now = Carbon::now();

                foreach ($slots as $slot) {
                    if (! is_string($slot) || ! preg_match('/^\d{1,2}:\d{2}$/', $slot)) {
                        continue; // biar rule 'date_format:H:i' yang tangani
                    }

                    $slotTime = Carbon::createFromFormat('H:i', $slot);

                    if ($slotTime !== false && $slotTime->lte($now)) {
                        $v->errors()->add(
                            'slots',
                            'Slot jam '.$slot.' sudah lewat. Pilih jam yang belum berlalu untuk hari ini.',
                        );
                    }
                }
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'date.required'          => 'Tanggal sewa wajib diisi.',
            'date.date_format'       => 'Format tanggal sewa harus YYYY-MM-DD, contoh 2026-11-10.',
            'date.after_or_equal'    => 'Tanggal sewa tidak boleh sebelum hari ini.',
            'slots.required'         => 'Pilih minimal satu slot jadwal.',
            'slots.min'              => 'Pilih minimal satu slot jadwal.',
            'slots.max'              => 'Maksimal 15 slot per booking, sesuai jam operasional 08:00-23:00.',
            'slots.*.required'       => 'Slot jam tidak boleh kosong.',
            'slots.*.date_format'    => 'Format jam slot harus HH:MM, contoh 08:00.',
        ];
    }
}
