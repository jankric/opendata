<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create categories');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'metadata' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi',
            'name.unique' => 'Nama kategori sudah digunakan',
            'parent_id.exists' => 'Kategori parent tidak valid',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (!$this->has('is_active')) {
            $this->merge(['is_active' => true]);
        }

        if (!$this->has('sort_order')) {
            $this->merge(['sort_order' => 0]);
        }
    }
}