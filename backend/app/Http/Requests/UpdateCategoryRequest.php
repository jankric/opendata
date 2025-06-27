<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit categories');
    }

    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:100',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                Rule::notIn([$category->id]), // Prevent self-reference
            ],
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
            'parent_id.not_in' => 'Kategori tidak boleh menjadi parent dari dirinya sendiri',
        ];
    }
}