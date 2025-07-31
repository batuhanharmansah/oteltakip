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
            symlink(storage_path('app/public'), public_path('storage'));
        }

        return $next($request);
    }
}
