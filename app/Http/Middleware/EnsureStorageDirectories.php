<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStorageDirectories
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Create storage directories
            $directories = [
                'storage/app/public/task_photos',
                'storage/app/public/qrcodes',
            ];

            foreach ($directories as $directory) {
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
            }

            // Create storage link if it doesn't exist
            if (!file_exists('public/storage')) {
                try {
                    symlink(storage_path('app/public'), public_path('storage'));
                } catch (\Exception $e) {
                    // Link might already exist or permission issue, ignore
                }
            }
        } catch (\Exception $e) {
            // Log error but don't break the application
            \Log::error('Storage directory creation failed: ' . $e->getMessage());
        }

        return $next($request);
    }
}
