<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DatasetController;
use App\Http\Controllers\Api\V1\ResourceController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\OrganizationController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\StatsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'version' => config('app.version', '1.0.0'),
    ]);
});

// API v1 routes
Route::prefix('v1')->name('api.v1.')->group(function () {
    
    // Public routes (no authentication required)
    Route::group([], function () {
        // Authentication
        Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
        
        // Public datasets
        Route::get('/datasets', [DatasetController::class, 'index'])->name('datasets.index');
        Route::get('/datasets/search', [DatasetController::class, 'search'])->name('datasets.search');
        Route::get('/datasets/popular', [DatasetController::class, 'popular'])->name('datasets.popular');
        Route::get('/datasets/recent', [DatasetController::class, 'recent'])->name('datasets.recent');
        Route::get('/datasets/featured', [DatasetController::class, 'featured'])->name('datasets.featured');
        Route::get('/datasets/{dataset}', [DatasetController::class, 'show'])->name('datasets.show');
        
        // Public resources
        Route::get('/datasets/{dataset}/resources', [ResourceController::class, 'index'])->name('datasets.resources.index');
        Route::get('/resources/{resource}', [ResourceController::class, 'show'])->name('resources.show');
        Route::get('/resources/{resource}/download', [ResourceController::class, 'download'])->name('resources.download');
        Route::get('/resources/{resource}/preview', [ResourceController::class, 'preview'])->name('resources.preview');
        
        // Public categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('/categories/{category}/datasets', [CategoryController::class, 'datasets'])->name('categories.datasets');
        
        // Public organizations
        Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations.index');
        Route::get('/organizations/{organization}', [OrganizationController::class, 'show'])->name('organizations.show');
        Route::get('/organizations/{organization}/datasets', [OrganizationController::class, 'datasets'])->name('organizations.datasets');
        
        // Public tags
        Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
        Route::get('/tags/{tag}', [TagController::class, 'show'])->name('tags.show');
        Route::get('/tags/{tag}/datasets', [TagController::class, 'datasets'])->name('tags.datasets');
        
        // Public statistics
        Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');
    });
    
    // Protected routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        // Authentication
        Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::post('/auth/refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
        Route::get('/auth/profile', [AuthController::class, 'profile'])->name('auth.profile');
        
        // Dataset management
        Route::post('/datasets', [DatasetController::class, 'store'])->name('datasets.store');
        Route::put('/datasets/{dataset}', [DatasetController::class, 'update'])->name('datasets.update');
        Route::delete('/datasets/{dataset}', [DatasetController::class, 'destroy'])->name('datasets.destroy');
        Route::post('/datasets/{dataset}/publish', [DatasetController::class, 'publish'])->name('datasets.publish');
        Route::post('/datasets/{dataset}/unpublish', [DatasetController::class, 'unpublish'])->name('datasets.unpublish');
        Route::post('/datasets/{dataset}/approve', [DatasetController::class, 'approve'])->name('datasets.approve');
        
        // Resource management
        Route::post('/datasets/{dataset}/resources', [ResourceController::class, 'store'])->name('datasets.resources.store');
        Route::put('/resources/{resource}', [ResourceController::class, 'update'])->name('resources.update');
        Route::delete('/resources/{resource}', [ResourceController::class, 'destroy'])->name('resources.destroy');
        Route::post('/resources/{resource}/generate-preview', [ResourceController::class, 'generatePreview'])->name('resources.generate-preview');
        
        // Category management
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        
        // Organization management
        Route::post('/organizations', [OrganizationController::class, 'store'])->name('organizations.store');
        Route::put('/organizations/{organization}', [OrganizationController::class, 'update'])->name('organizations.update');
        Route::delete('/organizations/{organization}', [OrganizationController::class, 'destroy'])->name('organizations.destroy');
        
        // Analytics (admin only)
        Route::middleware('permission:view analytics')->group(function () {
            Route::get('/stats/dashboard', [StatsController::class, 'dashboard'])->name('stats.dashboard');
            Route::get('/stats/downloads', [StatsController::class, 'downloads'])->name('stats.downloads');
            Route::get('/stats/views', [StatsController::class, 'views'])->name('stats.views');
            Route::get('/stats/trends', [StatsController::class, 'trends'])->name('stats.trends');
        });
    });
    
    // Rate limited routes
    Route::middleware(['throttle:api'])->group(function () {
        // Add any routes that need rate limiting
    });
});

// Fallback route for API
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found',
    ], 404);
});