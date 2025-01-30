<?php

namespace App\Console\Commands;

use App\Http\Controllers\Dashboard\DropboxController;
use Illuminate\Console\Command;

class RefreshDropboxTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dropbox:refresh-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all Dropbox access tokens every 3 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dropboxController = new DropboxController();
        $dropboxController->refreshAllTokens();

        $this->info('All Dropbox access tokens have been refreshed successfully.');    }
}
