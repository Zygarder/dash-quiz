<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Dasher;

class DeleteDasher extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'dasher:delete {identifier : Email or ID of the dasher}';

    /**
     * The console command description.
     */
    protected $description = 'Delete a dasher using email or ID';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $identifier = $this->argument('identifier');

        // Detect if input is email or ID
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $dasher = Dasher::where('email', $identifier)->first();
        } else {
            $dasher = Dasher::find($identifier);
        }

        if (!$dasher) {
            $this->error('Dasher not found.');
            return;
        }

        // Confirm before deleting
        if (!$this->confirm("Are you sure you want to delete {$dasher->email}?")) {
            $this->info('Cancelled.');
            return;
        }

        $dasher->delete();

        $this->info('Dasher deleted successfully.');
    }
}