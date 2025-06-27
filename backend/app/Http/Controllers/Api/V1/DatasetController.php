<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Category;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use App\Http\Resources\DatasetResource;
use App\Http\Resources\DatasetCollection;
use App\Http\Requests\StoreDatasetRequest;
use App\Http\Requests\UpdateDatasetRequest;

class DatasetController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $datasets = QueryBuilder::for(Dataset::class)
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('visibility'),
                AllowedFilter::exact('featured'),
                AllowedFilter::exact('category_id'),
                AllowedFilter::exact('organization_id'),
                AllowedFilter::scope('category', 'byCategory'),
                AllowedFilter::scope('organization', 'byOrganization'),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->search($value);
                }),
                AllowedFilter::callback('tags', function ($query, $value) {
                    $tags = is_array($value) ? $value : [$value];
                    foreach ($tags as $tag) {
                        $query->whereJsonContains('tags', $tag);
                    }
                }),
            ])
            ->allowedSorts([
                'title',
                'created_at',
                'updated_at',
                'published_at',
                AllowedSort::callback('downloads', function ($query, $direction) {
                    $query->withCount('downloads')->orderBy('downloads_count', $direction);
                }),
                AllowedSort::callback('views', function ($query, $direction) {
                    $query->withCount('views')->orderBy('views_count', $direction);
                }),
            ])
            ->with(['category', 'organization', 'creator'])
            ->withCount(['downloads', 'views', 'resources'])
            ->when(!auth()->check() || !auth()->user()->can('view datasets'), function ($query) {
                $query->published();
            })
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => new DatasetCollection($datasets),
            'message' => 'Datasets retrieved successfully',
        ]);
    }

    public function show(Dataset $dataset): JsonResponse
    {
        // Check if user can view this dataset
        if (!$dataset->is_published && (!auth()->check() || !auth()->user()->canManageDataset($dataset))) {
            abort(404);
        }

        // Record view
        $dataset->recordView(auth()->user(), request()->ip());

        $dataset->load(['category', 'organization', 'creator', 'resources', 'groups']);
        $dataset->loadCount(['downloads', 'views', 'resources']);

        return response()->json([
            'success' => true,
            'data' => new DatasetResource($dataset),
            'message' => 'Dataset retrieved successfully',
        ]);
    }

    public function store(StoreDatasetRequest $request): JsonResponse
    {
        $dataset = Dataset::create([
            ...$request->validated(),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $dataset->load(['category', 'organization', 'creator']);

        return response()->json([
            'success' => true,
            'data' => new DatasetResource($dataset),
            'message' => 'Dataset created successfully',
        ], 201);
    }

    public function update(UpdateDatasetRequest $request, Dataset $dataset): JsonResponse
    {
        $this->authorize('update', $dataset);

        $dataset->update([
            ...$request->validated(),
            'updated_by' => auth()->id(),
        ]);

        $dataset->load(['category', 'organization', 'creator']);

        return response()->json([
            'success' => true,
            'data' => new DatasetResource($dataset),
            'message' => 'Dataset updated successfully',
        ]);
    }

    public function destroy(Dataset $dataset): JsonResponse
    {
        $this->authorize('delete', $dataset);

        $dataset->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dataset deleted successfully',
        ]);
    }

    public function publish(Dataset $dataset): JsonResponse
    {
        $this->authorize('publish', $dataset);

        $dataset->publish();

        return response()->json([
            'success' => true,
            'data' => new DatasetResource($dataset),
            'message' => 'Dataset published successfully',
        ]);
    }

    public function unpublish(Dataset $dataset): JsonResponse
    {
        $this->authorize('publish', $dataset);

        $dataset->unpublish();

        return response()->json([
            'success' => true,
            'data' => new DatasetResource($dataset),
            'message' => 'Dataset unpublished successfully',
        ]);
    }

    public function approve(Dataset $dataset): JsonResponse
    {
        $this->authorize('approve', $dataset);

        $dataset->approve(auth()->user());

        return response()->json([
            'success' => true,
            'data' => new DatasetResource($dataset),
            'message' => 'Dataset approved successfully',
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:3',
            'category' => 'sometimes|string',
            'organization' => 'sometimes|string',
            'format' => 'sometimes|string',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ]);

        $datasets = QueryBuilder::for(Dataset::class)
            ->allowedFilters([
                AllowedFilter::exact('category_id'),
                AllowedFilter::exact('organization_id'),
                AllowedFilter::scope('category', 'byCategory'),
                AllowedFilter::scope('organization', 'byOrganization'),
            ])
            ->with(['category', 'organization', 'creator'])
            ->withCount(['downloads', 'views', 'resources'])
            ->published()
            ->search($request->q)
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => new DatasetCollection($datasets),
            'message' => 'Search results retrieved successfully',
        ]);
    }

    public function popular(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);

        $datasets = Dataset::published()
            ->with(['category', 'organization', 'creator'])
            ->withCount(['downloads', 'views', 'resources'])
            ->orderBy('downloads_count', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => DatasetResource::collection($datasets),
            'message' => 'Popular datasets retrieved successfully',
        ]);
    }

    public function recent(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);

        $datasets = Dataset::published()
            ->with(['category', 'organization', 'creator'])
            ->withCount(['downloads', 'views', 'resources'])
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => DatasetResource::collection($datasets),
            'message' => 'Recent datasets retrieved successfully',
        ]);
    }

    public function featured(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);

        $datasets = Dataset::published()
            ->featured()
            ->with(['category', 'organization', 'creator'])
            ->withCount(['downloads', 'views', 'resources'])
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => DatasetResource::collection($datasets),
            'message' => 'Featured datasets retrieved successfully',
        ]);
    }
}