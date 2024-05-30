<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Support\Arr;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'preferred_name'
    ];

    
    /**
     * Get the team name
     *
     * @param  string  $value
     * @return string
     */
    public function getNameAttribute($value)
    {

        if (!empty($this->preferred_name)) {
            return $this->preferred_name;
        }


        return $value;
    }


    public function homeFixtures(): HasMany
    {
        return $this->hasMany(Fixture::class, 'home_team_id', 'api_id');
    }

    public function awayFixtures(): HasMany
    {
        return $this->hasMany(Fixture::class, 'away_team_id', 'api_id');
    }


    public function getPointsAttribute()
    {

        return $this->calculateTotalPoints();
    }

    // public function calculatePoints($fixtures, $team_type): int
    // {
    //     $pointsConfig = config('points');
    
    //     return $fixtures->reduce(function ($points, $fixture) use ($team_type, $pointsConfig) {
    //         $homeScore = $fixture->home_team_score;
    //         $awayScore = $fixture->away_team_score;
    
    //         $outcome = $homeScore <=> $awayScore;

    //         $points += $pointsConfig['result_points'][$this->getResultKey($outcome)];
    
    //         $goalDifference = abs($homeScore - $awayScore);

    //         $points += $this->getScorePoints($goalDifference, $team_type, $pointsConfig);
    
    //         return $points;
    
    //     }, 0);
    // }

    // private function getResultKey($outcome): string
    // {
    //     return match ($outcome) {
    //         1 => 'win',
    //         0 => 'draw',
    //         -1 => 'defeat'
    //     };
    // }
    
    // private function getScorePoints($goalDifference, $team_type, $pointsConfig): int
    // {
    //     $winKey = "{$team_type}_win";
    //     $defeatKey = "{$team_type}_defeat";
    
    //     return collect([
    //         $winKey => Arr::get($pointsConfig, "score_points.$winKey.points", 0),
    //         $defeatKey => Arr::get($pointsConfig, "score_points.$defeatKey.points", 0),
        
    //     ])->map(function ($points, $key) use ($goalDifference, $pointsConfig) {
    //         $goals = Arr::get($pointsConfig, "score_points.$key.goals");
            
    //         if ($goalDifference >= $goals) {
    //             return $points;
    //         } else {

    //             return $points;
    //         }
        
    //     })->sum();
    // }

    public function fixtures()
    {
        return collect($this->homeFixtures, $this->awayFixtures);
    }

    public function calculateTotalPoints(): int
    {
        $points = 0;

        foreach ($this->fixtures() as $fixture) {
            $points += $this->getTeamPoints($fixture, $this->api_id);
        }

        return $points;
    }

    private function getTeamPoints($fixture, $team_id)
    {

        return ($fixture->home_team_id === $team_id) ? $fixture->homeTeamPoints() : $fixture->awayTeamPoints();


    }

    // public function calculateTotalPoints(): int
    // {
    //     $fixtures = array_merge($this->homeFixtures, $this->awayFixtures);
        
    //     foreach ($fixtures as $fixture) {
    //         $totalPoints += $fixture->calculateTeamPoints($this->id);
    //     }

    //     return $totalPoints;
    // }
}
