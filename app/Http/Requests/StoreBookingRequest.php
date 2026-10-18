<?php

declare(strict_types=1);

namespace App\Http\Requests;

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
            'date' => ['required', 'date', 'after_or_equal:today'],
            'slots' => ['required', 'array', 'min:1', 'max:15'],
            'slots.*' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'date.after_or_equal:today' => 'Tanggal sewa tidak boleh sebelum hari ini.',
            'slots.min' => 'Pilih minimal satu slot jadwal.',
            'slots.max' => 'Maksimal 15 slot per booking, sesuai jam operasional 08:00-23:00.',
            'slots.*.date_format' => 'Format jam slot harus HH:MM, contoh 08:00.',
        ];
    }
}
