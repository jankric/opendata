<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\OrganizationResource;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;

class OrganizationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $organizations = Organization::query()
            ->when($request->get('active_only'), function ($query) {
                $query->active();
            })
            ->withCount(['datasets' => function ($query) {
                $query->published();
            }])
            ->withCount('users')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => OrganizationResource::collection($organizations),
            'message' => 'Organizations retrieved successfully',
        ]);
    }

    public function show(Organization $organization): JsonResponse
    {
        $organization->loadCount(['datasets' => function ($query) {
            $query->published();
        }]);
        $organization->loadCount('users');

        return response()->json([
            'success' => true,
            'data' => new OrganizationResource($organization),
            'message' => 'Organization retrieved successfully',
        ]);
    }

    public function store(StoreOrganizationRequest $request): JsonResponse
    {
        $this->authorize('create', Organization::class);

        $organization = Organization::create($request->validated());

        return response()->json([
            'success' => true,
            'data' => new OrganizationResource($organization),
            'message' => 'Organization created successfully',
        ], 201);
    }

    public function update(UpdateOrganizationRequest $request, Organization $organization): JsonResponse
    {
        $this->authorize('update', $organization);

        $organization->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => new OrganizationResource($organization),
            'message' => 'Organization updated successfully',
        ]);
    }

    public function destroy(Organization $organization): JsonResponse
    {
        $this->authorize('delete', $organization);

        // Check if organization has datasets or users
        if ($organization->datasets()->exists() || $organization->users()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete organization with existing datasets or users',
            ], 400);
        }

        $organization->delete();

        return response()->json([
            'success' => true,
            'message' => 'Organization deleted successfully',
        ]);
    }

    public function datasets(Organization $organization, Request $request): JsonResponse
    {
        $datasets = $organization->datasets()
            ->published()
            ->with(['category', 'creator'])
            ->withCount(['downloads', 'views', 'resources'])
            ->orderBy('published_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $datasets,
            'message' => 'Organization datasets retrieved successfully',
        ]);
    }
}