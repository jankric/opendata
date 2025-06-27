<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit organizations');
    }

    public function rules(): array
    {
        $organization = $this->route('organization');

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('organizations', 'name')->ignore($organization->id),
            ],
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:100',
            'website' => 'nullable|url',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'logo_url' => 'nullable|url',
            'is_active' => 'nullable|boolean',
            'metadata' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama organisasi wajib diisi',
            'name.unique' => 'Nama organisasi sudah digunakan',
            'website.url' => 'Format website tidak valid',
            'email.email' => 'Format email tidak valid',
            'logo_url.url' => 'Format URL logo tidak valid',
        ];
    }
}