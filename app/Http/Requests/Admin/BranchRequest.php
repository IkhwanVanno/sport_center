<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Branch;
use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user) {
            return false;
        }

        $branch = $this->route('branch');

        if ($branch instanceof Branch) {
            return $user->can('update', $branch);
        }

        return $user->can('create', Branch::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:20'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama cabang wajib diisi.',
            'address.required' => 'Alamat lengkap cabang wajib diisi.',
            'phone.required' => 'Nomor telepon kontak cabang wajib diisi.',
        ];
    }
}
