<?php

namespace App\Services;

use App\Models\Fixture;
use App\Models\Team;
use App\Models\PointsRule;

class PointsCalculator
{

    protected $pointsRules;

    public function __construct()
    {
        $this->pointsRules = PointsRule::all();
    }
    
    /**
     * Calculate points for a given fixture.
     *
     * @param Fixture $fixture
     * @return int
     */

    public function calculatePoints(Fixture $fixture)
    {

        $homeTeamPoints = 0;
        $awayTeamPoints = 0;

        foreach ($this->pointsRules as $rule) {
            if ($this->matchesCondition($fixture, $rule, $fixture->home_team_id, true)) {
                $homeTeamPoints += $rule->value;
            }

            if ($this->matchesCondition($fixture, $rule, $fixture->away_team_id, false)) {
                $awayTeamPoints += $rule->value;
            }
        }


        return [
            'home_team_points' => $homeTeamPoints,
            'away_team_points' => $awayTeamPoints
        ];
    }

    protected function matchesCondition(Fixture $fixture, PointsRule $rule, $team_id, bool $is_home_team)
    {
        $condition = $rule->condition;
        // $thresh
    }

    /**
     * Calculate the result points for a given fixture.
     *
     * @param Fixture $fixture
     * @return int
     */
    
     public function calculateResultPoints(Fixture $fixture)
     {
        $homeTeamPoints = 0;
        $awayTeamPoints = 0;

        // Determine the fixture outcome
        $outcome = $this->getOutcome($fixure);

        // Get the appropriate rule based on the outcome
        $rule = PointsRule::where('outcome', $outcome['outcome'])
            ->whereNull('condition')
            ->first();

        if (!$outcome['result']) {
            return [
                'homeTeamPoints' => $rule->value,
                'awayTeamPoints' => $rule->value,
            ];
        }

        return [
            'homeTeamPoints' => $outcome['result'] === 'home' ? $rule->value : 0,
            'awayTeamPoints' => $outcome['result'] === 'away' ? $rule->value : 0
        ];
        
     }

    /**
     * Calculate home team score points for a given fixture.
     *
     * @param Fixture $fixture
     * @return int
     */
    
     public function calculateHomeScorePoints(Fixture $fixture)
     {

        // Determine the fixture outcome
        $result = $this->getOutcome($fixure);

        if (!$result['score_points']) {
            return;
        }


        // Get the appropriate rule based on the outcome
        $rule = PointsRule::where('outcome', $result['outcome'])
            ->whereNull('condition')
            ->first();

        return $rule ? $rule->value : 0;
     }   
     
     
    /**
     * Calculate away team score points for a given fixture.
     *
     * @param Fixture $fixture
     * @return int
     */
    
     public function calculateAwayScorePoints(Fixture $fixture)
     {

        // Determine the fixture outcome
        $result = $this->getOutcome($fixure);

        if (!$result['score_points']) {
            return;
        }

        // Get the appropriate rule based on the outcome
        $rule = PointsRule::where('outcome', $result['outcome'])
            ->whereNull('condition')
            ->first();

        return $rule ? $rule->value : 0;
     }   

    /**
     * Determine the outcome of the fixture.
     *
     * @param Fixture $fixture
     * @return string
     */
    protected function getOutcome(Fixture $fixture): array
    {
        return [
            'goal_difference' => $fixture->goalDifference,
            'score_points' => $this->exceedsThreshold($fixture),
        ];

    }

    protected function exceedsThreshold($fixture)
    {
        $threshold = config('points.score.threshold');

        return $fixture->goalDifference > $threshold || $fixture->goalDifference < -$threshold;
    }

    private function getPointsByKey($key)
    {
        $rule = PointsRule::where('key', $key)->first();
    }
}

