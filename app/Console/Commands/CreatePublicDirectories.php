<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreatePublicDirectories extends Command
{
    protected $signature = 'public:create-directories';
    protected $description = 'Create necessary public directories for Railway deployment';

    public function handle()
    {
        $directories = [
            'public/task_photos',
            'public/qrcodes',
        ];

        foreach ($directories as $directory) {
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
                $this->info("Created directory: {$directory}");
            } else {
                $this->info("Directory already exists: {$directory}");
            }
        }

        $this->info('All public directories are ready!');
    }
} 