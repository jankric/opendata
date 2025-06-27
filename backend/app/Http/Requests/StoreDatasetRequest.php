<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDatasetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create datasets');
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'notes' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'organization_id' => 'required|exists:organizations,id',
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

    protected function prepareForValidation(): void
    {
        // Set default organization if not provided
        if (!$this->has('organization_id') && $this->user()->organization_id) {
            $this->merge([
                'organization_id' => $this->user()->organization_id,
            ]);
        }

        // Set default status
        if (!$this->has('status')) {
            $this->merge([
                'status' => config('opendata.datasets.auto_approve') ? 'published' : 'draft',
            ]);
        }

        // Set default visibility
        if (!$this->has('visibility')) {
            $this->merge([
                'visibility' => 'public',
            ]);
        }

        // Set default license
        if (!$this->has('license')) {
            $this->merge([
                'license' => config('opendata.datasets.default_license'),
            ]);
        }
    }
}