<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Sport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $sport = $this->route('sport');
        $sportId = $sport instanceof Sport ? $sport->id : $sport;

        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('sports', 'name')->ignore($sportId),
            ],
            'icon' => [
                'nullable',
                'string',
                'max:100',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama cabang olahraga wajib diisi.',
            'name.string' => 'Nama cabang olahraga harus berupa teks valid.',
            'name.max' => 'Nama cabang olahraga maksimal 50 karakter.',
            'name.unique' => 'Nama cabang olahraga ini sudah terdaftar.',
            'icon.string' => 'Icon olahraga harus berupa string class icon valid.',
            'icon.max' => 'Icon olahraga maksimal 100 karakter.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama olahraga',
            'icon' => 'icon olahraga',
        ];
    }
}
