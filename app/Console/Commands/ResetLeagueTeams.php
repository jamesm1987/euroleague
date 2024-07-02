<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetLeagueTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:league-teams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate the teams table to reset teams for a new season';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('teams')->truncate();
        $this->info('Teams have been reset for the new season.');
    }
}
