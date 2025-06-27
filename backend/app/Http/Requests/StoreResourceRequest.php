<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canManageDataset($this->route('dataset'));
    }

    public function rules(): array
    {
        $allowedTypes = config('opendata.uploads.allowed_types');
        $maxSize = config('opendata.uploads.max_size');

        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:file,api,link',
            'format' => 'nullable|string|max:20',
            'url' => 'required_if:type,link,api|nullable|url',
            'file' => [
                'required_if:type,file',
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
            'url.required_if' => 'URL wajib diisi untuk tipe link atau API',
            'file.required_if' => 'File wajib diupload untuk tipe file',
            'file.max' => "Ukuran file maksimal {$maxSizeMB}MB",
            'file.mimes' => 'Format file yang diizinkan: ' . implode(', ', $allowedTypes),
        ];
    }
}