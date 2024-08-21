<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Services\PointsCalculator;
use Carbon\Carbon;




class Fixture extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_id',
        'home_team_id',
        'away_team_id',
        'home_team_score',
        'away_team_score',
    ];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id', 'api_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id', 'api_id');
    }

    public function homeTeamPoints()
    {
        return $this->calculateFixturePoints('home');
    }

    public function awayTeamPoints()
    {
        return $this->calculateFixturePoints('away');
    }

    public function teamResultPoints()
    {
        return $this->calculateResultPoints();
    }

    public function awayTeamResultPoints()
    {
        return $this->calculateResultPoints('away');
    }


    public function homeTeamScorePoints()
    {
        return $this->calculateScorePoints('home');
    }

    public function awayTeamScorePoints()
    {
        return $this->calculateScorePoints('away');
    }

    public function resultScore()
    {
        return "{$this->home_team_score} - {$this->away_team_score}";
    }


    public function getGoalDifferenceAttribute()
    {
        return $this->home_team_score - $this->away_team_score;
    }

    public function calculateFixturePoints($team_type)
    {

        
        if (is_null($this->home_team_score) && is_null($this->away_team_score)) {
            return;
        }


            $points = 0;
            $teamGoals = ($team_type === 'home') ? $this->home_team_score : $this->away_team_score;
            $opponentGoals = ($team_type === 'home') ? $this->away_team_score : $this->home_team_score;


            $outcome = $this->getResultKey($this->home_team_score <=> $this->away_team_score);

            $drawPoints = config('points.result.draw');
            $winPoints = config('points.result.win');
            $goalDifferenceThreshold = config('points.score.threshold');
            $defeatPoints = config("points.score.{$team_type}.defeat");
            $winScorePoints = config("points.score.{$team_type}.win");

            if ($outcome === 'draw') {
                return $drawPoints;
            }
        

            if ($outcome === $team_type) {
                $points += $winPoints;
            }
        

            $goalDifference = abs($this->home_team_score - $this->away_team_score);
            $hasScorePoints = $goalDifference >= $goalDifferenceThreshold;
        

            if ($hasScorePoints) {
                if ($outcome !== $team_type) {
                    $points += $defeatPoints;
                } else {
                    $points += $winScorePoints;
                }
            }
    
    
        return $points;

    }

    public function fixturePoints(): HasMany
    {
        return $this->hasMany(FixturePoint::class, 'fixture_id');
    }

    public function calculateResultPoints($team_type)
    {
        $points = 0;


        $teamGoals = ($team_type === 'home') ? $this->home_team_score : $this->away_team_score;
        $opponentGoals = ($team_type === 'home') ? $this->away_team_score : $this->home_team_score;


        $outcome = $this->getResultKey($this->home_team_score <=> $this->away_team_score);

        $drawPoints = config('points.result_points.draw');
        $winPoints = config('points.result_points.win');


        if ($outcome === 'draw') {
            return $drawPoints;
        }
    

        if ($outcome === $team_type) {
            $points += $winPoints;
        }
        
    
        return $points;
    }

    public function calculateScorePoints($team_type)
    {
        $points = 0;


        $teamGoals = ($team_type === 'home') ? $this->home_team_score : $this->away_team_score;
        $opponentGoals = ($team_type === 'home') ? $this->away_team_score : $this->home_team_score;


        $outcome = $this->getResultKey($this->home_team_score <=> $this->away_team_score);

        $goalDifferenceThreshold = config('points.score_points.goal_difference');
        $defeatPoints = config('points.score_points.defeat.'.$team_type);
        $winScorePoints = config('points.score_points.win.'.$team_type);
        

        $goalDifference = abs($this->home_team_score - $this->away_team_score);
        $hasScorePoints = $goalDifference >= $goalDifferenceThreshold;
    
        if ($hasScorePoints) {
            if ($outcome !== $team_type) {
                $points += $defeatPoints;
            } else {
                $points += $winScorePoints;
            }
        }
    
        return $points;
    }


    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function scopeWithResults($query)
    {
        return $query->whereNotNull('home_team_score')
                     ->whereNotNull('away_team_score');
    }

    public function getResultKey($outcome): string
    {
        return match ($outcome) {
            1 => 'home',
            0 => 'draw',
            -1 => 'away',
        };
    }

    public static function calculatePoints()
    {
        $results = self::withResults()->get();
        $fixture_points = [];

        foreach ($results as $result) {    
            $fixture_points[] = (new PointsCalculator($result))->calculate();
        }
        
        if (!empty($fixture_points)) {
            
            $data = collect($fixture_points)->flatten(1)->toArray();

            FixturePoint::upsert($data, ['fixture_id', 'team_id'], ['points_rule_id', 'updated_at']);
        }
    }
}
