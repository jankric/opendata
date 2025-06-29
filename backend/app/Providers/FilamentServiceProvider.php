<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Set Filament colors
        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Slate,
            'info' => Color::Blue,
            'primary' => Color::Red,
            'success' => Color::Emerald,
            'warning' => Color::Orange,
        ]);

        Filament::serving(function () {
            // Custom CSS for admin panel
            FilamentAsset::register([
                Css::make('custom-admin', resource_path('css/filament/admin.css')),
            ]);
        });

        Filament::navigation(function () {
            return [
                NavigationGroup::make('Dashboard')
                    ->items([
                        \App\Filament\Pages\Dashboard::class,
                    ]),
                NavigationGroup::make('Data Management')
                    ->items([
                        \App\Filament\Resources\DatasetResource::class,
                        \App\Filament\Resources\CategoryResource::class,
                        \App\Filament\Resources\TagResource::class,
                        \App\Filament\Resources\GroupResource::class,
                    ]),
                NavigationGroup::make('User Management')
                    ->items([
                        \App\Filament\Resources\UserResource::class,
                        \App\Filament\Resources\OrganizationResource::class,
                    ]),
                NavigationGroup::make('System')
                    ->items([
                        \App\Filament\Resources\ActivityResource::class,
                    ]),
            ];
        });
    }
}