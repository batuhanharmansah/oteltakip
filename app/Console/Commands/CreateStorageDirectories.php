<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateStorageDirectories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:create-directories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create necessary storage directories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directories = [
            'storage/app/public/task_photos',
            'storage/app/public/qrcodes',
        ];

        foreach ($directories as $directory) {
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
                $this->info("Created directory: {$directory}");
            } else {
                $this->info("Directory already exists: {$directory}");
            }
        }

        $this->info('All storage directories are ready!');
    }
}
