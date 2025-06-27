<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canManageDataset($this->route('resource')->dataset);
    }

    public function rules(): array
    {
        $allowedTypes = config('opendata.uploads.allowed_types');
        $maxSize = config('opendata.uploads.max_size');

        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|required|in:file,api,link',
            'format' => 'nullable|string|max:20',
            'url' => 'nullable|url',
            'file' => [
                'nullable',
                'file',
                'max:' . ($maxSize / 1024), // Convert bytes to KB for validation
                'mimes:' . implode(',', $allowedTypes),
            ],
            'encoding' => 'nullable|string|max:50',
            'schema' => 'nullable|array',
            'metadata' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        $allowedTypes = config('opendata.uploads.allowed_types');
        $maxSizeMB = config('opendata.uploads.max_size') / 1024 / 1024;

        return [
            'name.required' => 'Nama resource wajib diisi',
            'type.required' => 'Tipe resource wajib dipilih',
            'file.max' => "Ukuran file maksimal {$maxSizeMB}MB",
            'file.mimes' => 'Format file yang diizinkan: ' . implode(', ', $allowedTypes),
        ];
    }
}