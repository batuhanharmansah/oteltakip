<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Only set schema default string length if database is available
        try {
            Schema::defaultStringLength(191);
        } catch (\Exception $e) {
            // Database not available during deployment, skip
        }

        // Create storage directories if they don't exist
        $directories = [
            'storage/app/public/task_photos',
            'storage/app/public/qrcodes',
        ];

        foreach ($directories as $directory) {
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
        }
    }
}
