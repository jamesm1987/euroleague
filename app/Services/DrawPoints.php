<?php

namespace App\Services;

use App\Models\Fixture;

class DrawPoints implements FixturePoints
{
    public static function apply(Fixture $fixture): int
    {
        return [
            'fixture_id' => $fixture->id,
            'home_team_id' => $fixture->hometeam->id,
            'away_team_id' => $fixture->awayteam->id,
        ];
    }
}