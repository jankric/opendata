<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Resources\ResourceResource;
use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function index(Dataset $dataset): JsonResponse
    {
        $resources = $dataset->resources()
            ->with(['creator', 'updater'])
            ->withCount('downloads')
            ->get();

        return response()->json([
            'success' => true,
            'data' => ResourceResource::collection($resources),
            'message' => 'Resources retrieved successfully',
        ]);
    }

    public function show(Resource $resource): JsonResponse
    {
        $resource->load(['dataset', 'creator', 'updater']);
        $resource->loadCount('downloads');

        return response()->json([
            'success' => true,
            'data' => new ResourceResource($resource),
            'message' => 'Resource retrieved successfully',
        ]);
    }

    public function store(StoreResourceRequest $request, Dataset $dataset): JsonResponse
    {
        $this->authorize('update', $dataset);

        $resource = $dataset->resources()->create([
            ...$request->validated(),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        // Handle file upload if present
        if ($request->hasFile('file')) {
            $this->handleFileUpload($resource, $request->file('file'));
        }

        $resource->load(['creator', 'updater']);

        return response()->json([
            'success' => true,
            'data' => new ResourceResource($resource),
            'message' => 'Resource created successfully',
        ], 201);
    }

    public function update(UpdateResourceRequest $request, Resource $resource): JsonResponse
    {
        $this->authorize('update', $resource->dataset);

        $resource->update([
            ...$request->validated(),
            'updated_by' => auth()->id(),
        ]);

        // Handle file upload if present
        if ($request->hasFile('file')) {
            $this->handleFileUpload($resource, $request->file('file'));
        }

        $resource->load(['creator', 'updater']);

        return response()->json([
            'success' => true,
            'data' => new ResourceResource($resource),
            'message' => 'Resource updated successfully',
        ]);
    }

    public function destroy(Resource $resource): JsonResponse
    {
        $this->authorize('update', $resource->dataset);

        // Delete associated files
        if ($resource->file_path && Storage::disk(config('opendata.uploads.disk'))->exists($resource->file_path)) {
            Storage::disk(config('opendata.uploads.disk'))->delete($resource->file_path);
        }

        $resource->delete();

        return response()->json([
            'success' => true,
            'message' => 'Resource deleted successfully',
        ]);
    }

    public function download(Resource $resource): Response
    {
        // Check if dataset is published or user has permission
        if (!$resource->dataset->is_published && (!auth()->check() || !auth()->user()->canManageDataset($resource->dataset))) {
            abort(404);
        }

        // Record download
        $resource->dataset->recordDownload(auth()->user(), request()->ip(), $resource);

        if ($resource->type === 'file' && $resource->file_path) {
            $disk = Storage::disk(config('opendata.uploads.disk'));
            
            if (!$disk->exists($resource->file_path)) {
                abort(404, 'File not found');
            }

            return response()->download(
                $disk->path($resource->file_path),
                $resource->name . '.' . $resource->format,
                [
                    'Content-Type' => $resource->mime_type,
                    'Content-Length' => $resource->file_size,
                ]
            );
        }

        if ($resource->type === 'link' && $resource->url) {
            return redirect($resource->url);
        }

        abort(404, 'Resource not downloadable');
    }

    public function preview(Resource $resource): JsonResponse
    {
        // Check if dataset is published or user has permission
        if (!$resource->dataset->is_published && (!auth()->check() || !auth()->user()->canManageDataset($resource->dataset))) {
            abort(404);
        }

        if (!$resource->is_preview_available) {
            return response()->json([
                'success' => false,
                'message' => 'Preview not available for this resource',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'resource' => new ResourceResource($resource),
                'preview' => $resource->preview_data,
            ],
            'message' => 'Resource preview retrieved successfully',
        ]);
    }

    public function generatePreview(Resource $resource): JsonResponse
    {
        $this->authorize('update', $resource->dataset);

        if ($resource->generatePreview()) {
            return response()->json([
                'success' => true,
                'data' => new ResourceResource($resource),
                'message' => 'Preview generated successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to generate preview',
        ], 400);
    }

    private function handleFileUpload(Resource $resource, $file): void
    {
        $disk = config('opendata.uploads.disk');
        $path = config('opendata.uploads.path');
        
        // Generate unique filename
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $path . '/' . $resource->dataset_id . '/' . $filename;
        
        // Store file
        $storedPath = $file->storeAs($path . '/' . $resource->dataset_id, $filename, $disk);
        
        // Update resource
        $resource->update([
            'file_path' => $storedPath,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'format' => $file->getClientOriginalExtension(),
        ]);

        // Generate preview for supported formats
        if (in_array(strtolower($file->getClientOriginalExtension()), ['csv', 'json'])) {
            $resource->generatePreview();
        }
    }
}