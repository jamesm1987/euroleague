<?php

namespace App\Services;

use App\Models\Fixture;
use App\Models\Team;
use App\Models\PointsRule;

class PointsCalculator
{

    protected $fixturePoints = [];

    protected Fixture $fixture;

    /**
     * Create a new PointsCalculator instance.
     *
     * @param Fixture $fixture
     */

    public function __construct(Fixture $fixture)
    {
        $this->fixture = $fixture;
    }


    
    /**
     * Calculate points for a given fixture.
     *
     * @return mixed
     */

    public function calculate(): mixed
    {

        

        if ($this->fixture->homeTeam && $this->fixture->awayTeam) {
            
            $outcome = $this->getOutcome();

                switch ($outcome['result']) {

                    case 'draw':

                        $this->fixturePoints[] = $this->createFixturePoint($this->fixture->homeTeam->id, 'draw');
                        $this->fixturePoints[] = $this->createFixturePoint($this->fixture->awayTeam->id, 'draw');
                    

                    break;


                    case 'home':

                        $this->fixturePoints[] = $this->createFixturePoint($this->fixture->homeTeam->id, 'win');

                        if ($outcome['score_points']) {

                            $this->fixturePoints[] = $this->createFixturePoint($this->fixture->homeTeam->id, 'home_win_score_points');
                            $this->fixturePoints[] = $this->createFixturePoint($this->fixture->awayTeam->id, 'away_defeat_score_points');
                        }

                    break;

                    case 'away':

                        $this->fixturePoints[] = $this->createFixturePoint($this->fixture->awayTeam->id, 'win');

                        if ($outcome['score_points']) {

                            $this->fixturePoints[] = $this->createFixturePoint($this->fixture->homeTeam->id,'home_defeat_score_points');
                            $this->fixturePoints[] = $this->createFixturePoint($this->fixture->awayTeam->id, 'away_win_score_points');
                        }

                    break;

                }

                return $this->fixturePoints;
            
            }

            return false;
    }

 
    /**
     * Determine the outcome of the fixture.
     *
     * @param Fixture $this->fixture
     * @return string
     */

    protected function getOutcome(): array
    {
        
        return [
            'result' => $this->fixture->getResultKey($this->fixture->home_team_score <=> $this->fixture->away_team_score),
            'goal_difference' => $this->fixture->goalDifference,
            'score_points' => $this->exceedsThreshold(),
        ];

    }

    /**
     * Check if the goal difference exceeds the threshold.
     *
     * @return bool
     */

    protected function exceedsThreshold(): bool
    {
        $threshold = config('points.score.threshold');
        
        return $this->fixture->goalDifference >= $threshold || $this->fixture->goalDifference <= -$threshold;
    }

    /**
     * Get the ID of a points rule by its key.
     *
     * @param string $key
     * @return int
     * @throws \Exception
     */

    private function getPointsByKey($key): int
    {
        $rule = PointsRule::where('key', $key)->first();

        if (!$rule) {
            throw new \Exception("Points rule not found for key: {$key}");
        }
        
        return $rule->id;
    }

    /**
     * Create an array for a fixture point record.
     *
     * @param int $team_id
     * @param string $key
     * @return array
     */

    private function createFixturePoint($team_id, $key): array
    {
        return [
            'fixture_id' => $this->fixture->id,
            'team_id' => $team_id,
            'points_rule_id' => $this->getPointsByKey($key),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}

