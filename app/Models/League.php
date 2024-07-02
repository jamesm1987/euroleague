<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class League extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'api_id',
        'country_code',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function fixtures()
    {
        return $this->hasMany(Fixture::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public static function leagueTable()
    {
        $query = DB::table('teams')
        ->select('teams.*', DB::raw('SUM(fixtures.fixture_points) as total_fixture_points'))
        ->leftJoin('fixtures', function ($join) {
            $join->on('teams.api_id', '=', 'fixtures.home_team_id')
                 ->orWhere('teams.api_id', '=', 'fixtures.away_team_id');
        })
        ->groupBy('teams.api_id')
        ->get();

    
    return $query;
    }


}


