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

    

    public function getPointsAttribute()
    {

        return $this->calculateTotalPoints();
    }


    public function fixtures()
    {
        return collect($this->homeFixtures)->merge($this->awayFixtures);
    }

    public function calculateTotalPoints(): int
    {
        $points = 0;


        foreach ($this->fixtures() as $fixture) {
            $points += $this->getTeamPoints($fixture, $this->api_id);
        }


        return $points;
    }

    public function calculateMatchPoints(): int
    {
        $points = 0;

        foreach ($this->fixtures() as $fixture) {
            $points += $this->getMatchPoints($fixture, $this->api_id);
        }

        return $points;
    }

    public function calculateScorePoints(): int
    {
        $points = 0;

        foreach ($this->fixtures() as $fixture) {
            $points += $this->getScorePoints($fixture, $this->api_id);
        }

        return $points;
    }

    private function getTeamPoints($fixture, $team_id)
    {
        return ($fixture->home_team_id === $team_id) ? $fixture->homeTeamPoints() : $fixture->awayTeamPoints();
    }


    private function getMatchPoints($fixture, $team_id)
    {
        return ($fixture->home_team_id === $team_id) ? $fixture->homeTeamResultPoints() : $fixture->awayTeamResultPoints();
    }

    private function getScorePoints($fixture, $team_id)
    {
        return ($fixture->home_team_id === $team_id) ? $fixture->homeTeamScorePoints() : $fixture->awayTeamScorePoints();
    }

    public function getMatchesWon()
    {
        $won = 0;

        foreach ($this->fixtures() as $fixture) {

            $outcome = $fixture->getResultKey($fixture->home_team_score <=> $fixture->away_team_score);
            $isHomeTeam = $this->api_id === $fixture->home_team_id;

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

    public function getMatchesDrawn(): int
    {

        $drawn = 0;

        foreach ($this->fixtures() as $fixture) {

            $outcome = $fixture->getResultKey($fixture->home_team_score <=> $fixture->away_team_score);

            if ($outcome === 'draw') {
                $drawn++;
            }
        }

        return $drawn;
    }

    public function getMatchesLost()
    {
        $lost = 0;

        foreach ($this->fixtures() as $fixture) {

            $outcome = $fixture->getResultKey($fixture->home_team_score <=> $fixture->away_team_score);
            $isHomeTeam = $this->api_id === $fixture->home_team_id;

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

    public function getGoalDifference(): int
    {
        $goalsFor = 0;
        $goalsAgainst = 0;
    
        foreach ($this->fixtures() as $fixture) {
            
            if ($fixture->home_team_id === $this->api_id) {
                
                $goalsFor += $fixture->home_team_score;
                $goalsAgainst += $fixture->away_team_score;
            
            } else {
                
                $goalsFor += $fixture->away_team_score;
                $goalsAgainst += $fixture->home_team_score;
            }
        }
    
        return $goalsFor - $goalsAgainst;
    }
    public function getLogoUrlAttribute()
    {
        return config('services.api-football.team_logo_url') . '/' . $this->team_api_id . '.png';
    }

}
