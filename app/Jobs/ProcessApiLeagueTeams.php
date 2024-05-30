<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\ExtractsArrayData;

use App\Models\Team;

use Illuminate\Support\Facades\Log;

class ProcessApiLeagueTeams implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ExtractsArrayData;

    protected $data = [];

    protected $response;

    protected $params;

    protected $league_id;

    protected $model;

    /**
     * Create a new job instance.
     */
    public function __construct($response, $params, $model)
    {
        $this->response = $response;
        $this->params = $params;
        $this->model = $model;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $modelInstance = new $this->model;

        $this->league_id = $modelInstance->where('api_id', $this->params['league'])->pluck('id')->first();


        foreach ($this->response as $row) {
            $this->data[] = [
                'name' => $row->team->name,
                'api_id' => $row->team->id,
                'league_id' => $this->league_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }


        if (!empty($this->data)) {
            Team::upsert($this->data, ['api_id', 'league_id'], ['name', 'updated_at']);
        }
    }
}
