<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Court;
use App\Models\CourtPriceRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PriceRuleRequest extends FormRequest
{
    /**
     * Tentukan otorisasi user.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user) {
            return false;
        }

        return $user->can('create', CourtPriceRule::class);
    }

    /**
     * Aturan validasi input.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'court_id' => ['required', 'exists:courts,id'],
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'price_per_hour' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Validasi tambahan: Admin Cabang hanya boleh mengatur tarif untuk lapangan cabangnya sendiri.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($v) {
            $user = $this->user();
            if ($user && $user->isAdmin() && $this->filled('court_id')) {
                $court = Court::find($this->input('court_id'));
                if ($court && $court->branch_id !== $user->branch_id) {
                    $v->errors()->add('court_id', 'Anda hanya memiliki wewenang mengatur tarif untuk lapangan di cabang Anda sendiri.');
                }
            }
        });
    }

    /**
     * Pesan kustom validasi.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'court_id.required' => 'Lapangan wajib dipilih.',
            'court_id.exists' => 'Lapangan yang dipilih tidak valid.',
            'day_of_week.required' => 'Hari berlaku tarif wajib dipilih.',
            'start_time.required' => 'Jam mulai slot wajib diisi.',
            'end_time.required' => 'Jam selesai slot wajib diisi.',
            'end_time.after' => 'Jam selesai harus lebih akhir daripada jam mulai.',
            'price_per_hour.required' => 'Nominal tarif per jam wajib diisi.',
            'price_per_hour.numeric' => 'Nominal tarif harus berupa angka.',
        ];
    }
}
