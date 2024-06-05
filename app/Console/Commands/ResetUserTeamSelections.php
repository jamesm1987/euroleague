<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetUserTeamSelections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:user-team-selections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate the user_team_selections table to reset team selections for a new season';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('team_user')->truncate();
        $this->info('User team selections have been reset for the new season.');
    }
}
