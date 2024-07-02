<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetFixtures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:fixtures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate the fixtures table to reset fixtures for a new season';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('fixtures')->truncate();
        $this->info('Fixtures have been reset for the new season.');
    }
}
