<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Court;
use Illuminate\Foundation\Http\FormRequest;

class CourtRequest extends FormRequest
{
    /**
     * Tentukan apakah user memiliki otorisasi untuk melakukan request ini.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user) {
            return false;
        }

        $court = $this->route('court');

        if ($court instanceof Court) {
            return $user->can('update', $court);
        }

        return $user->can('create', Court::class);
    }

    /**
     * Aturan validasi untuk data lapangan.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        $rules = [
            'sport_id' => ['required', 'exists:sports,id'],
            'name' => ['required', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'base_price_per_hour' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'facilities' => ['nullable', 'array'],
            'facilities.*' => ['exists:facilities,id'],
        ];

        // Jika Superadmin, branch_id wajib dipilih dari form.
        // Jika Admin Cabang, branch_id diambil dari session/user login.
        if ($user && $user->isSuperAdmin()) {
            $rules['branch_id'] = ['required', 'exists:branches,id'];
        } else {
            $rules['branch_id'] = ['nullable', 'exists:branches,id'];
        }

        return $rules;
    }

    /**
     * Pesan kustom untuk validasi.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'branch_id.required' => 'Lokasi cabang wajib dipilih.',
            'branch_id.exists' => 'Cabang yang dipilih tidak valid.',
            'sport_id.required' => 'Jenis olahraga wajib dipilih.',
            'sport_id.exists' => 'Jenis olahraga tidak valid.',
            'name.required' => 'Nama lapangan wajib diisi.',
            'base_price_per_hour.required' => 'Harga sewa per jam wajib diisi.',
            'base_price_per_hour.numeric' => 'Harga sewa harus berupa nominal angka.',
        ];
    }
}
