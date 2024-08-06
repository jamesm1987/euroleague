<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\ExtractsArrayData;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;


use App\Models\Fixture;
use App\Models\FixturePoint;


use Illuminate\Support\Facades\Log;

use App\Services\PointsCalculator;

class ProcessApiLeagueFixtures implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ExtractsArrayData;

    protected $data = [];

    protected $fixture_ids = []; 
    
    protected EloquentCollection $fixtures;
    
    protected $fixture_points = [];

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
                'api_id' => $row->fixture->id,
                'league_id' => $this->league_id, 
                'home_team_id' => $row->teams->home->id,
                'away_team_id' => $row->teams->away->id,
                'home_team_score' => ($row->fixture->status->short) === 'FT' ? $row->score->fulltime->home : null,
                  'away_team_score' => ($row->fixture->status->short) === 'FT' ? $row->score->fulltime->away : null,
                'date' => $row->fixture->date,
                'created_at' => now(),
                'updated_at' => now(),
            ];


            $this->fixture_ids[] = $row->fixture->id;            

        }

        if (!empty($this->data)) {
            Fixture::upsert($this->data, ['api_id'], ['home_team_score', 'away_team_score', 'updated_at']);
        }

        $this->fixtures = Fixture::whereIn('api_id', $this->fixture_ids)->get();

        if (!empty($this->fixtures)) {

            $this->fixtures->each(function ($fixture) {

                $this->fixture_points[] = (new PointsCalculator($fixture))->calculate();
            });

        }
        
        if (!empty($this->fixture_points)) {

            $data = collect($this->fixture_points)->flatten(1)->toArray();

            FixturePoint::upsert($data, ['fixture_id', 'team_id'], ['points_rule_id', 'updated_at']);
        }


        
    }
}
