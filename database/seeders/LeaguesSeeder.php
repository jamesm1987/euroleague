<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\League;

class LeaguesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leagues = [
            [
                'name' => 'Premier League',
                'api_id' => 39,
                'country' => 'England',
                'country_code' => 'gb',
                'active' => 1,
            ],
            [
                'name' => 'La Liga',
                'api_id' => 140,
                'country' => 'Spain',
                'country_code' => 'es',
                'active' => 1,
            ],
            [
                'name' => 'Bundesliga',
                'api_id' => 78,
                'country' => 'Germany',
                'country_code' => 'de',
                'active' => 1,
            ],
            [
                'name' => 'Ligue 1',
                'api_id' => 61,
                'country' => 'France',
                'country_code' => 'fr',
                'active' => 1,
            ],
            [
                'name' => 'Serie A',
                'api_id' => 135,
                'country' => 'Italy',
                'country_code' => 'it',
                'active' => 1,
            ],            
        ];

        foreach ($leagues as $league) {
            League::create($league);
        }
    }
}
