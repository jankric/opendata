<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register services
        $this->app->singleton(\App\Services\DatasetService::class);
        $this->app->singleton(\App\Services\FileUploadService::class);
        $this->app->singleton(\App\Services\AnalyticsService::class);
        $this->app->singleton(\App\Services\SearchService::class);
        $this->app->singleton(\App\Services\NotificationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default string length for older MySQL versions
        Schema::defaultStringLength(191);

        // Use Bootstrap 4 for pagination views
        Paginator::defaultView('pagination::bootstrap-4');
        Paginator::defaultSimpleView('pagination::simple-bootstrap-4');

        // Register custom validation rules
        $this->registerCustomValidationRules();
    }

    private function registerCustomValidationRules(): void
    {
        \Illuminate\Support\Facades\Validator::extend('indonesian_phone', function ($attribute, $value, $parameters, $validator) {
            // Indonesian phone number validation
            return preg_match('/^(\+62|62|0)[0-9]{9,13}$/', $value);
        });

        \Illuminate\Support\Facades\Validator::extend('file_size_mb', function ($attribute, $value, $parameters, $validator) {
            if (!$value instanceof \Illuminate\Http\UploadedFile) {
                return false;
            }
            
            $maxSizeMB = $parameters[0] ?? 50;
            $maxSizeBytes = $maxSizeMB * 1024 * 1024;
            
            return $value->getSize() <= $maxSizeBytes;
        });
    }
}