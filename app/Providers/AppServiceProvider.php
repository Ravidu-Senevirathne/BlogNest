<?php

namespace App\Providers;

use App\Services\CacheService;
use App\Services\ImageService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the image service
        $this->app->singleton(ImageService::class, function () {
            return new ImageService();
        });

        // Register the cache service
        $this->app->singleton(CacheService::class, function () {
            return new CacheService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
