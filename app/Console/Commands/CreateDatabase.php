<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create SQLite database file if it does not exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $databasePath = database_path('database.sqlite');

        if (!file_exists($databasePath)) {
            // Create the database directory if it doesn't exist
            $databaseDir = dirname($databasePath);
            if (!file_exists($databaseDir)) {
                mkdir($databaseDir, 0755, true);
            }

            // Create empty SQLite database file
            touch($databasePath);
            chmod($databasePath, 0644);

            $this->info('SQLite database file created successfully at: ' . $databasePath);
        } else {
            $this->info('SQLite database file already exists at: ' . $databasePath);
        }
    }
}
