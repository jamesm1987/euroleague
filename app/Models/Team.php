<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\CollectsPoints;

use Illuminate\Support\Arr;


class Team extends Model
{
    use HasFactory, CollectsPoints;

    protected $fillable = [
        'name',
        'preferred_name',
        'price'
    ];

    protected $table = 'teams';
    
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

    /**
     * Get the team price html
     *
     * @return string
     */
    public function priceHtml()
    {

        return "£{$this->price}m";
    }

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function homeFixtures(): HasMany
    {
        return $this->hasMany(Fixture::class, 'home_team_id', 'api_id');
    }

    public function awayFixtures(): HasMany
    {
        return $this->hasMany(Fixture::class, 'away_team_id', 'api_id');
    }

    public function homeResults(): HasMany
    {
        return $this->hasMany(Fixture::class, 'home_team_id', 'api_id')
        ->whereNotNull('home_team_score')
        ->whereNotNull('away_team_score');
    }

    public function awayResults(): HasMany
    {
        return $this->hasMany(Fixture::class, 'away_team_id', 'api_id')
        ->whereNotNull('home_team_score')
        ->whereNotNull('away_team_score');
    }

    public function fixturePoints(): HasMany
    {
        return $this->hasMany(FixturePoint::class);
    }

    public function getPointsAttribute()
    {
        return $this->calculateTotalPoints();
    }

    public function getFixturePointsAttribute()
    {
        return $this->calculateMatchPoints();
    }

    public function fixtures()
    {
        return collect($this->homeFixtures)->merge($this->awayFixtures);
    }

    public function results()
    {
        return collect($this->homeResults)->merge($this->awayResults);
    }


    private function isHomeTeam($fixture): bool
    {
        return $fixture->home_team_id === $this->api_id;
    }

    public function calculateTotalPoints(): int
    {
        $fixturePoints = $this->fixturePoints()->with('pointsRule')->get();

        return $fixturePoints->sum(fn ($fixturePoint) => $fixturePoint->pointsRule->value ?? 0);

    }

    public function calculateScorePoints(): int
    {
        $scorePoints = $this->fixturePoints()->with('pointsRule')
        ->whereHas('pointsRule', function($query) {
            $query->whereNotNull('threshold');
        })->get();

        return $scorePoints->sum(fn ($scorePoint) => $scorePoint->pointsRule->value ?? 0);

    }

    public function calculateMatchPoints(): int
    {
        $points = 0;

        $this->fixturePoints()->each(function ($fixture) use (&$points) {

            $points += $this->getMatchPoints($fixture, $this->id);
        });

        return $points;
    }

    // public function calculateScorePoints(): int
    // {
    //     $points = 0;

    //     $this->fixturePoints()->each(function ($fixture) use (&$points) {
    //         $points += $this->getScorePoints($fixture, $this->id);
    //     });

    //     return $points;
    // }

    private function getTeamPoints($fixture, $team_id)
    {
        return ($fixture->team_id === $team_id) ? $fixture->homeTeamPoints() : $fixture->awayTeamPoints();
    }

    private function getMatchPoints($fixture, $team_id)
    {
        return $fixture->teamResultPoints($team_id);
    }

    private function getScorePoints($fixture, $team_id)
    {
        return ($fixture->team_id === $team_id) ? $fixture->homeTeamScorePoints() : $fixture->awayTeamScorePoints();
    }

    public function getMatchesWonAttribute()
    {
        $won = 0;

        foreach ($this->results() as $result) {

            $outcome = $result->getResultKey($result->home_team_score <=> $result->away_team_score);
            $isHomeTeam = $this->api_id === $result->home_team_id;

            if ($outcome === 'draw') {
                continue;
            }

            if (($outcome === 'home' && $isHomeTeam) 
                
                || $outcome === 'away' && !$isHomeTeam
            
             ) {

                $won++;
            
            }
        }

        return $won;
    }

    public function getMatchesDrawnAttribute(): int
    {

        $drawn = 0;

        foreach ($this->results() as $result) {

            $outcome = $result->getResultKey($result->home_team_score <=> $result->away_team_score);
            
            if ($outcome === 'draw') {
                $drawn++;
            }
        }

        return $drawn;
    }

    public function getMatchesLostAttribute()
    {
        $lost = 0;

        foreach ($this->results() as $result) {

            $outcome = $result->getResultKey($result->home_team_score <=> $result->away_team_score);
            $isHomeTeam = $this->api_id === $result->home_team_id;

            if ($outcome === 'draw') {
                continue;
            }

            if (($outcome === 'home' && !$isHomeTeam) 
                
                || $outcome === 'away' && $isHomeTeam
            
             ) {

                $lost++;
            
            }
        }

        return $lost;
    }

    public function getPlayedAttribute()
    {
        return ($this->matches_won + $this->matches_drawn + $this->matches_lost);
    }

    public function getHomeGoalsForAttribute()
    {
        $goals = 0;

        foreach ($this->results() as $result) {

            if ($result->home_team_id === $this->api_id) {
                $goals += $result->home_team_score;
            }
        }

        return $goals;
    }

    public function getHomeGoalsAgainstAttribute()
    {
        $goals = 0;

        foreach ($this->results() as $result) {

            if ($result->home_team_id === $this->api_id) {
                $goals += $result->away_team_score;
            }
        }

        return $goals;
    }

    public function getAwayGoalsForAttribute()
    {
        $goals = 0;

        foreach ($this->results() as $result) {

            if ($result->away_team_id === $this->api_id) {
                $goals += $result->away_team_score;
            }
        }

        return $goals;
    }

    public function getAwayGoalsAgainstAttribute()
    {
        $goals = 0;

        foreach ($this->results() as $result) {

            if ($result->away_team_id === $this->api_id) {
                $goals += $result->home_team_score;
            }
        }

        return $goals;
    }

    public function getGoalsForAttribute()
    {
        return ($this->home_goals_for + $this->away_goals_for);
    }

    public function getGoalsAgainstAttribute()
    {
        return ($this->home_goals_against + $this->away_goals_against);
    }

    public function getGoalDifferenceAttribute()
    {
        return $this->getGoalDifference();
    }


    public function getGoalDifference(): int
    {
        $goalsFor = 0;
        $goalsAgainst = 0;
    
        foreach ($this->results() as $result) {
            
            if ($result->home_team_id === $this->api_id) {
                
                $goalsFor += $result->home_team_score;
                $goalsAgainst += $result->away_team_score;
            
            } else {
                
                $goalsFor += $result->away_team_score;
                $goalsAgainst += $result->home_team_score;
            }
        }
    
        return $goalsFor - $goalsAgainst;
    }
    public function getLogoUrlAttribute()
    {
        return config('services.api-football.team_logo_url') . '/' . $this->team_api_id . '.png';
    }


    public function scopeWithPointsSum(Builder $query, $league_id)
    {
        return $query->leftJoin('fixture_point', 'teams.id', '=', 'fixture_point.team_id')
                     ->leftJoin('points_rules', 'fixture_point.points_rule_id', '=', 'points_rules.id')
                     ->where('teams.league_id', $league_id)
                     ->select('teams.*')
                     ->selectRaw('COALESCE(SUM(points_rules.value), 0) as points_sum')
                     ->groupBy('teams.id');
    }
}


