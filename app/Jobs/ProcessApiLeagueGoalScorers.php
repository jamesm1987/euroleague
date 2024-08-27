<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\ExtractsArrayData;
use Illuminate\Support\Arr;

use Illuminate\Support\Facades\Log;

use App\Models\Team;
use App\Models\PointsRule;
use App\Models\GoalscorerPoint;


class ProcessApiLeagueGoalScorers implements ShouldQueue
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
        $pointsRules = PointsRule::where('type', 'goalscorer')->orderBy('outcome', 'asc')->get()->toArray();
        
        foreach (Arr::take($this->response, 4) as $key => $row) {

            $team_id = $row->statistics[0]->team->id;
        
            $existingTeamIds = Arr::pluck($this->data, 'team_id');
        
            if (!in_array($team_id, $existingTeamIds)) {

                $team = Team::where('id', $team_id)->first();
        
                $this->data[] = [
                    'team_id' => $team_id,
                    'name' => $team->name,
                    'league_id' => $this->league_id,
                    'goals'     => $row->statistics[0]->goals->total,
                    'team_points' => $team->fixture_points,
                    'team_gd'       => $team->goalDifference,
                    'team_goals'    => $team->goalsFor,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        usort($this->data, function ($a, $b) {

            if ($a['goals'] !== $b['goals']) {
                return $b['goals'] <=> $a['goals'];
            }
        

            if ($a['team_points'] !== $b['team_points']) {
                return $a['team_points'] <=> $b['team_points'];
            }
        
            if ($a['team_gd'] !== $b['team_gd']) {
                return $a['team_gd'] <=> $b['team_gd'];
            }
        
            if ($a['team_goals'] !== $b['team_goals']) {
                return $a['team_goals'] <=> $b['team_goals'];
            }
        
            return strcmp($a['name'], $b['name']);
        });
        

        foreach ($this->data as $key => &$item) {
            $item['points_rule_id'] = $pointsRules[$key]['id'];
        }
        
        unset($item);
        

        if (!empty($this->data)) {
            GoalscorerPoint::upsert($this->data, ['league_id', 'points_rule_id'], ['team_id', 'points_rule_id', 'updated_at']);
        }
    }
}
