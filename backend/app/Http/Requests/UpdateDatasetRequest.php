<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDatasetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canManageDataset($this->route('dataset'));
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'notes' => 'nullable|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'organization_id' => 'sometimes|required|exists:organizations,id',
            'license' => 'nullable|string|max:100',
            'status' => 'nullable|in:draft,review,published,archived',
            'visibility' => 'nullable|in:public,private',
            'featured' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'metadata' => 'nullable|array',
            'schema' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul dataset wajib diisi',
            'description.required' => 'Deskripsi dataset wajib diisi',
            'category_id.required' => 'Kategori dataset wajib dipilih',
            'category_id.exists' => 'Kategori yang dipilih tidak valid',
            'organization_id.required' => 'Organisasi dataset wajib dipilih',
            'organization_id.exists' => 'Organisasi yang dipilih tidak valid',
        ];
    }
}