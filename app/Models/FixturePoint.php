<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixturePoint extends Model
{
    
    use HasFactory;

    protected $fillable = [];

    protected $table = 'fixture_point';

    public function pointsRule()
    {
        return $this->belongsTo(PointsRule::class);
    }

    public function fixture()
    {
        return $this->belongsTo(Fixture::class);
    }

    public function homeTeamPoints()
    {
        return $this->calculateFixturePoints('home');
    }

    public function awayTeamPoints()
    {
        return $this->calculateFixturePoints('away');
    }

    public function homeTeamResultPoints()
    {
        return $this->calculateResultPoints('home');
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


    public function calculateScorePoints()
    {
        return $this->pointsRule->threshold ? $this->pointsRule->value : 0;
    }

    public function calculateResultPoints()
    {
        return !$this->pointsRule->threshold ? $this->pointsRule->value : 0;

    }


    public function getResultKey($outcome): string
    {
        return match ($outcome) {
            1 => 'home',
            0 => 'draw',
            -1 => 'away',
        };
    }

}
