<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Facility;
use Illuminate\Foundation\Http\FormRequest;

class FacilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user) {
            return false;
        }

        $facility = $this->route('facility');

        if ($facility instanceof Facility) {
            return $user->can('update', $facility);
        }

        return $user->can('create', Facility::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama fasilitas wajib diisi.',
            'name.max' => 'Nama fasilitas maksimal 100 karakter.',
        ];
    }
}
