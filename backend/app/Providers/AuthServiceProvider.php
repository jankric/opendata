<?php

namespace App\Providers;

use App\Models\Dataset;
use App\Models\Category;
use App\Models\Organization;
use App\Policies\DatasetPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\OrganizationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Dataset::class => DatasetPolicy::class,
        Category::class => CategoryPolicy::class,
        Organization::class => OrganizationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}