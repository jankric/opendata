<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = Category::query()
            ->when($request->get('active_only'), function ($query) {
                $query->active();
            })
            ->when($request->get('root_only'), function ($query) {
                $query->rootCategories();
            })
            ->with(['parent', 'children'])
            ->withCount(['datasets' => function ($query) {
                $query->published();
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($categories),
            'message' => 'Categories retrieved successfully',
        ]);
    }

    public function show(Category $category): JsonResponse
    {
        $category->load(['parent', 'children']);
        $category->loadCount(['datasets' => function ($query) {
            $query->published();
        }]);

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category),
            'message' => 'Category retrieved successfully',
        ]);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $this->authorize('create', Category::class);

        $category = Category::create($request->validated());

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category),
            'message' => 'Category created successfully',
        ], 201);
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $this->authorize('update', $category);

        $category->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category),
            'message' => 'Category updated successfully',
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->authorize('delete', $category);

        // Check if category has datasets
        if ($category->datasets()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with existing datasets',
            ], 400);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ]);
    }

    public function datasets(Category $category, Request $request): JsonResponse
    {
        $datasets = $category->datasets()
            ->published()
            ->with(['organization', 'creator'])
            ->withCount(['downloads', 'views', 'resources'])
            ->orderBy('published_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $datasets,
            'message' => 'Category datasets retrieved successfully',
        ]);
    }
}