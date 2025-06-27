<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\TagResource;

class TagController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tags = Tag::query()
            ->when($request->get('popular'), function ($query, $limit) {
                $query->popular($limit);
            })
            ->when($request->get('search'), function ($query, $search) {
                $query->where('name', 'ILIKE', "%{$search}%");
            })
            ->orderBy('usage_count', 'desc')
            ->orderBy('name')
            ->limit($request->get('limit', 50))
            ->get();

        return response()->json([
            'success' => true,
            'data' => TagResource::collection($tags),
            'message' => 'Tags retrieved successfully',
        ]);
    }

    public function show(Tag $tag): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new TagResource($tag),
            'message' => 'Tag retrieved successfully',
        ]);
    }

    public function datasets(Tag $tag, Request $request): JsonResponse
    {
        $datasets = \App\Models\Dataset::published()
            ->whereJsonContains('tags', $tag->name)
            ->with(['category', 'organization', 'creator'])
            ->withCount(['downloads', 'views', 'resources'])
            ->orderBy('published_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $datasets,
            'message' => 'Tag datasets retrieved successfully',
        ]);
    }
}