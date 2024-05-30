<?php

namespace App\Jobs;

use App\Services\ApiFootballService;
use App\Jobs\ProcessApiLeagueTeams;
use App\Jobs\ProcessApiLeagueResults;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MakeApiRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $endpoint;
    protected array $params;
    protected $model;
    protected $apiFootballService;

    /**
     * Create a new job instance.
     */
    public function __construct(ApiFootballService $apiFootballService, string $endpoint, array $params = [], $model)
    {
        $this->endpoint = $endpoint;
        $this->params = $params;
        $this->apiFootballService = $apiFootballService;
        $this->model = $model;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = $this->apiFootballService->makeRequest($this->endpoint, $this->params);
        switch ($this->endpoint) {
            case 'teams':
                ProcessApiLeagueTeams::dispatch($data->response, $this->params, $this->model);
            break;

            case 'fixtures':
                ProcessApiLeagueFixtures::dispatch($data->response, $this->params, $this->model);
            break;
        }

    }
}
