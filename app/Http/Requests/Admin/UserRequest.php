<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Tentukan apakah user berhak mengelola akun.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user) {
            return false;
        }

        $targetUser = $this->route('user');

        if ($targetUser instanceof User) {
            return $user->can('update', $targetUser);
        }

        return $user->can('create', User::class);
    }

    /**
     * Aturan validasi akun pengguna.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $targetUser = $this->route('user');
        $userId = $targetUser instanceof User ? $targetUser->id : null;

        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:superadmin,admin,operator,customer'],
            'branch_id' => [
                'nullable',
                Rule::requiredIf(fn () => in_array($this->input('role'), ['admin', 'operator'], true)),
                'exists:branches,id',
            ],
        ];

        // Validasi password saat pembuatan akun baru vs pembaruan
        if ($this->isMethod('POST')) {
            $rules['password'] = ['required', 'string', 'min:8'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:8'];
        }

        return $rules;
    }

    /**
     * Pesan validasi kustom.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'role.required' => 'Role pengguna wajib ditentukan.',
            'role.in' => 'Role yang dipilih tidak valid.',
            'branch_id.required_if' => 'Cabang wajib dipilih untuk pengguna berposisi Admin Cabang atau Operator.',
            'branch_id.exists' => 'Cabang yang dipilih tidak ditemukan.',
            'password.required' => 'Password wajib diisi untuk akun baru.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
        ];
    }
}
